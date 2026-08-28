<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\TicketTierController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tour', [TourController::class, 'index'])->name('tour.index');
Route::get('/artist/{id}', [ArtistController::class, 'show'])->name('artist.show');
Route::get('/jadwal/{jadwalId}/tiket', [TicketTierController::class, 'show'])->name('tickettier.show');