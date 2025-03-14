<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/create-process', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/{id}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
    // Route::post('/{id}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    // Route::post('/{id}/payment-success', [BookingController::class, 'paymentSuccess'])
    //     ->name('bookings.paymentSuccess');

    Route::post('/midtrans/callback', [BookingController::class, 'midtransCallback'])->name('midtrans.callback');
});