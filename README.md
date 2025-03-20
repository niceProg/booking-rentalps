# **Rental PS Booking System**

Sistem ini adalah aplikasi berbasis Laravel untuk melakukan pemesanan rental PlayStation (PS4 & PS5) dengan integrasi pembayaran menggunakan Midtrans.

## **Persyaratan**

Sebelum menjalankan proyek ini, pastikan Anda sudah menginstal:

- [x] PHP >= 8.0
- [x] Composer
- [x] MySQL/MariaDB
- [x] Node.js & NPM (opsional, untuk frontend build jika ada)
- [x] Laravel 10
- [x] Midtrans Account (untuk API Key)

## **Instalasi**

**1. Clone Repository**
```bash
git clone https://github.com/niceProg/booking-rentalps.git
cd booking-rentalps
```

**2. Install Dependensi**
```bash
composer install
npm install && npm run dev
```

**3. Konfigurasi Environment**
Duplikat file .env.example menjadi .env:
```bash
cp .env.example .env
```

Lalu sesuaikan dengan database dan Midtrans API Key Anda:
```php
DB_DATABASE=rental_ps
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_SERVER_KEY=your-server-key
```

**4. Generate Key dan Migrasi Database**
```bash
php artisan key:generate
php artisan migrate --seed
```

**5. Jalankan Server Laravel**
```bash
php artisan serve
```

Akses aplikasi di http://127.0.0.1:8000

## **Konfigurasi Midtrans**

- Masuk ke dashboard Midtrans (https://midtrans.com).

- Aktifkan mode Sandbox untuk pengujian.

- Ambil Client Key dan Server Key dari menu Settings > Access Keys.

- Masukkan ke dalam file .env seperti yang sudah dijelaskan di atas.

## **Cara Menggunakan**

### 1. Buat Booking Baru

 - Masuk ke halaman utama dan klik Buat Booking Baru.

 - Isi data pemesanan seperti nama, tanggal booking, dan jenis PS (PS4/PS5).

 - Klik Submit untuk menyimpan pemesanan.

### 2. Checkout & Pembayaran

 - Setelah booking dibuat, Anda akan diarahkan ke halaman pembayaran.

 - Klik Bayar Sekarang, sistem akan memproses transaksi dengan Midtrans.

 - Jika berhasil, status booking akan otomatis berubah menjadi Paid.

### 3. Melihat Daftar Booking

 - Masuk ke halaman Daftar Booking untuk melihat status pemesanan.

 - Status dapat berupa pending, paid, failed, dll.


> Lisensi
> Proyek ini menggunakan lisensi MIT. Anda bebas menggunakannya dengan tetap menyertakan atribusi.