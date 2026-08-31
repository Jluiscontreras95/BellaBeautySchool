<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/reservas', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/reservas/disponibilidad', [AppointmentController::class, 'availability'])->name('appointments.availability');
Route::get('/reservas/confirmacion/{code}', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');

Route::post('/chat', [ChatController::class, 'stream'])->name('chat.stream');
