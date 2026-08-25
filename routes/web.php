<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/reservas', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/reservas/disponibilidad', [AppointmentController::class, 'availability'])->name('appointments.availability');
Route::get('/reservas/confirmacion/{code}', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');
