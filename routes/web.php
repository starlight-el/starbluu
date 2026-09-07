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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\TicketTierController as AdminTicketTierController;
use App\Http\Controllers\Admin\CheckInController as AdminCheckInController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::resource('artists', AdminArtistController::class)->except(['show']);
        Route::resource('tours', AdminTourController::class)->except(['show']);
        Route::get('/ticket-tiers', [AdminTicketTierController::class, 'index'])->name('tickettiers.index');
        Route::get('/ticket-tiers/create', [AdminTicketTierController::class, 'create'])->name('tickettiers.create');
        Route::post('/ticket-tiers', [AdminTicketTierController::class, 'store'])->name('tickettiers.store');
        Route::get('/ticket-tiers/{jadwal}/edit', [AdminTicketTierController::class, 'edit'])->name('tickettiers.edit');
        Route::put('/ticket-tiers/{jadwal}', [AdminTicketTierController::class, 'update'])->name('tickettiers.update');
        Route::delete('/ticket-tiers/{jadwal}', [AdminTicketTierController::class, 'destroy'])->name('tickettiers.destroy');
        Route::get('/checkin', [AdminCheckInController::class, 'index'])->name('checkin.index');
        Route::post('/checkin', [AdminCheckInController::class, 'store'])->name('checkin.store');
    });
});