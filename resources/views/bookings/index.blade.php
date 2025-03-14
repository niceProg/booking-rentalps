@extends('layouts/bookings')

@section('content')
	<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
		<h1 class="text-2xl font-bold text-center mb-4">Daftar Booking</h1>

		<div class="flex justify-end mb-4">
			<a href="{{ route('bookings.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
				Buat Booking Baru
			</a>
		</div>

		<div class="overflow-x-auto">
			<table class="w-full border-collapse bg-white shadow-md rounded-lg">
				<thead>
					<tr class="bg-blue-500 text-white">
						<th class="px-4 py-2">Nomor</th>
						<th class="px-4 py-2">Nama</th>
						<th class="px-4 py-2">Tanggal Dibuat</th>
						<th class="px-4 py-2">Tanggal Booking</th>
						<th class="px-4 py-2">Jenis</th>
						<th class="px-4 py-2">Total Harga</th>
						<th class="px-4 py-2">Status Pembayaran</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($bookings as $index => $booking)
						@php
							$paymentStatus = session('payment_status_' . $booking->id, 'pending');
						@endphp
						<tr class="border-b hover:bg-gray-100 transition">
							<td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
							<td class="px-4 py-2 text-center">{{ $booking->name }}</td>
							<td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y H:i') }}</td>
							<td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}</td>
							<td class="px-4 py-2 text-center">{{ $booking->service }}</td>
							<td class="px-4 py-2 text-center">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
							<td class="px-4 py-2 text-center">
								@if ($booking->status == 'paid')
									<span class="text-green-500 font-bold">Lunas</span>
								@elseif ($booking->status == 'pending')
									<span class="text-yellow-500 font-bold">Menunggu Pembayaran</span>
								@else
									<span class="text-red-500 font-bold">Gagal</span>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
