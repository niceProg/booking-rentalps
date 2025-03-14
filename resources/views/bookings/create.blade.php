@extends('layouts/bookings')

@section('content')
	<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
		<h1 class="text-2xl font-bold text-center mb-4">Buat Booking Baru</h1>
		<form id="booking-form" action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
			@csrf

			<div>
				<label for="name" class="block font-medium">Nama:</label>
				<input type="text" name="name" required class="w-full p-2 border rounded-lg">
			</div>

			<div>
				<label for="booking_date" class="block font-medium">Tanggal Booking:</label>
				<input type="text" name="booking_date" id="booking_date" required autocomplete="off"
					class="w-full p-2 border rounded-lg">
			</div>

			<div>
				<label for="service" class="block font-medium">Pilih Layanan:</label>
				<select name="service" required class="w-full p-2 border rounded-lg">
					<option value="PS4">PS4 (Rp 30.000)</option>
					<option value="PS5">PS5 (Rp 40.000)</option>
				</select>
			</div>

			<div class="text-center">
				<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
					Submit
				</button>
			</div>
		</form>
	</div>

	<!-- jQuery UI -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script>
		$(document).ready(function() {
			$("#booking_date").datepicker({
				dateFormat: "dd-mm-yy", // Format tampilan di UI
				minDate: 0
			});

			// Saat form dikirim, ubah format tanggal ke yyyy-mm-dd sebelum dikirim ke backend
			$("#booking-form").submit(function(e) {
				e.preventDefault(); // Mencegah pengiriman form langsung

				let rawDate = $("#booking_date").val(); // Ambil nilai input
				let parts = rawDate.split("-"); // Pisah menjadi [dd, mm, yyyy]
				let formattedDate = parts[2] + "-" + parts[1] + "-" + parts[0]; // Susun ulang ke yyyy-mm-dd
				$("#booking_date").val(formattedDate); // Update input dengan format yang benar

				// SweetAlert Konfirmasi
				Swal.fire({
					title: "Konfirmasi",
					text: "Apakah Anda yakin ingin melakukan booking?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya, booking sekarang!"
				}).then((result) => {
					if (result.isConfirmed) {
						// Kirim form setelah konfirmasi
						$("#booking-form")[0].submit();
					}
				});
			});
		});

		// Jika ada pesan sukses dari session, tampilkan SweetAlert
		@if (session('success'))
			Swal.fire({
				title: "Berhasil!",
				text: "{{ session('success') }}",
				icon: "success",
				confirmButtonText: "OK"
			});
		@endif

		// Jika ada pesan error dari session, tampilkan SweetAlert
		@if (session('error'))
			Swal.fire({
				title: "Gagal!",
				text: "{{ session('error') }}",
				icon: "error",
				confirmButtonText: "OK"
			});
		@endif
	</script>
@endsection
