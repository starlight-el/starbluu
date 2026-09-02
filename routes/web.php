<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\TicketTierController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tour', [TourController::class, 'index'])->name('tour.index');
Route::get('/world-tour', [TourController::class, 'worldTour'])->name('tour.world');
Route::get('/artist/{id}', [ArtistController::class, 'show'])->name('artist.show');
Route::get('/jadwal/{jadwalId}/tiket', [TicketTierController::class, 'show'])->name('tickettier.show');

Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/{checkoutGroupId}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{checkoutGroupId}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::get('/checkout/{checkoutGroupId}/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/checkout/{checkoutGroupId}/payment', [PaymentController::class, 'process'])->name('payment.process');

Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
