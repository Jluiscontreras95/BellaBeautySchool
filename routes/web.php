<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sitemap.xml', function () {
    $url = e(route('home'));
    $lastmod = now()->toDateString();

    return response(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{$url}</loc>
        <lastmod>{$lastmod}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
</urlset>
XML, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');

Route::post('/reservas', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/reservas/disponibilidad', [AppointmentController::class, 'availability'])->name('appointments.availability');
Route::get('/reservas/confirmacion/{code}', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');

Route::post('/chat', [ChatController::class, 'stream'])->name('chat.stream');
