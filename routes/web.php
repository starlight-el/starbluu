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
use App\Http\Controllers\EticketController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;

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
Route::get('/tickets/{orderId}/eticket', [EticketController::class, 'show'])->name('eticket.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('artists', AdminArtistController::class)->except(['show']);
    });
});