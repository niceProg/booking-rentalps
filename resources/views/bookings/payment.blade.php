@extends('layouts/bookings')

@section('content')
	<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md text-center">
		<h1 class="text-2xl font-bold mb-4">Pembayaran Booking</h1>
		<p class="mb-2">Nama: <strong>{{ $booking->name }}</strong></p>
		<p class="mb-4">Total Bayar: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>

		@if (!empty($snapToken))
			<button id="pay-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
				Bayar Sekarang
			</button>
		@else
			<p class="text-red-500">Gagal mendapatkan token pembayaran. Silakan coba lagi.</p>
		@endif
	</div>

	<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

	<script>
		document.getElementById("pay-button").addEventListener("click", function() {
			let snapToken = "{{ $snapToken }}";
			let bookingId = "{{ $booking->id }}";

			if (!snapToken) {
				Swal.fire("Error!", "Snap Token tidak ditemukan!", "error");
				return;
			}

			snap.pay(snapToken, {
				onSuccess: function(result) {
					Swal.fire({
						title: "Sukses!",
						text: "Pembayaran berhasil!",
						icon: "success",
						showConfirmButton: false,
						timer: 1500
					}).then(() => {
						// Kirim request ke backend untuk update status booking
						fetch("{{ route('midtrans.callback') }}", {
								method: "POST",
								headers: {
									"Content-Type": "application/json",
									"X-CSRF-TOKEN": "{{ csrf_token() }}"
								},
								body: JSON.stringify({
									order_id: result.order_id,
									transaction_status: result.transaction_status
								})
							})
							.then(response => response.json())
							.then(data => {
								console.log("Response dari server:", data);
								if (data.message ===
									'Midtrans callback processed successfully') {
									window.location.href = "{{ route('bookings.index') }}";
								} else {
									Swal.fire("Error!", data.message, "error");
								}
							})
							.catch(error => {
								console.error("Terjadi kesalahan:", error);
								Swal.fire("Gagal!", "Tidak dapat memperbarui status.",
								"error");
							});
					});
				},
				onPending: function(result) {
					Swal.fire("Pending!", "Pembayaran sedang diproses.", "warning");
				},
				onError: function(result) {
					Swal.fire("Gagal!", "Pembayaran gagal.", "error");
				}
			});
		});
	</script>
@endsection
