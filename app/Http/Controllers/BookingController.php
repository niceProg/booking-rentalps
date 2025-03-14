<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        $title = 'Daftar Booking';
        return view('bookings.index', compact('bookings', 'title'));
    }

    public function create()
    {
        $title = 'Buat Booking';
        return view('bookings.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'service' => 'required|in:PS4,PS5',
        ]);

        $totalPrice = Booking::calculatePrice($request->booking_date, $request->service);
        $uuid = Str::uuid();

        // Generate order_id yang sesuai dengan Midtrans
        $orderId = 'BOOKING-' . substr($uuid, 0, 8) . '-' . rand(1000, 9999);

        $booking = Booking::create([
            'id' => $uuid, // Simpan UUID asli
            'name' => $request->name,
            'booking_date' => $request->booking_date,
            'service' => $request->service,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'order_id' => $orderId, // Simpan order_id untuk tracking pembayaran
        ]);

        return redirect()->route('bookings.checkout', ['id' => $booking->id])
            ->with('success', 'Booking berhasil!');
    }

    public function checkout($id)
    {
        $booking = Booking::findOrFail($id);
        $title = 'Pembayaran Booking';

        if (!in_array($booking->service, ['PS4', 'PS5'])) {
            return back()->with('error', 'Jenis layanan tidak valid.');
        }

        // Tentukan harga
        $price = $booking->service === 'PS4' ? 30000 : 40000;
        $isWeekend = in_array(date('N', strtotime($booking->booking_date)), [6, 7]);
        $surcharge = $isWeekend ? 50000 : 0;
        $totalAmount = $price + $surcharge;

        // Item details Midtrans
        $itemDetails = [
            [
                'id' => 'PS-' . $booking->service,
                'name' => 'Rental ' . $booking->service,
                'price' => $price,
                'quantity' => 1,
            ]
        ];

        if ($surcharge > 0) {
            $itemDetails[] = [
                'id' => 'WEEKEND_SURCHARGE',
                'name' => 'Weekend Surcharge',
                'price' => $surcharge,
                'quantity' => 1,
            ];
        }

        // Pastikan order_id telah ada dan valid
        if (!$booking->order_id) {
            return back()->with('error', 'Order ID tidak ditemukan.');
        }

        // Buat parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $booking->order_id, // Ambil dari database
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $booking->name,
                'email' => 'mail@noreply.com',
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan dalam memproses pembayaran: ' . $e->getMessage());
        }

        return view('bookings.payment', compact('booking', 'snapToken', 'title'));
    }

    public function midtransCallback(Request $request)
    {
        $notif = $request->all();
        Log::info('Midtrans Callback:', $notif);

        // Validasi callback
        if (!isset($notif['order_id']) || !isset($notif['transaction_status'])) {
            return response()->json(['message' => 'Invalid Midtrans callback data'], 400);
        }

        $orderId = $notif['order_id'];
        $transactionStatus = $notif['transaction_status'];

        // Cari booking berdasarkan order_id
        $booking = Booking::where('order_id', $orderId)->first();

        if (!$booking) {
            Log::error('Booking not found: Order ID ' . $orderId);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Update status pembayaran
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $booking->status = 'paid';
                break;
            case 'pending':
                $booking->status = 'pending';
                break;
            case 'expire':
            case 'cancel':
                $booking->status = 'failed';
                break;
            case 'refund':
            case 'chargeback':
                $booking->status = 'refunded';
                break;
            default:
                Log::warning("Unhandled Midtrans status: " . $transactionStatus);
                return response()->json(['message' => 'Unhandled status'], 400);
        }

        $booking->save();
        return response()->json(['message' => 'Midtrans callback processed successfully']);
    }
}