<?php

use App\Http\Controllers\AusflugAnmeldungController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::post('/contact-form/send', [ContactFormController::class, 'store'])
    ->middleware(HandlePrecognitiveRequests::class)
    ->name('contact-form.send');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::inertia('/impressum', 'Imprint')->name('imprint');
Route::inertia('/datenschutz', 'Privacy')->name('privacy');

Route::prefix('/ausflug')->group(function () {
    Route::get('/', function () {
        return to_route('ausflug.anmeldung');
    });

    Route::get('/anmeldung', [AusflugAnmeldungController::class, 'index'])->name('ausflug.anmeldung');
    Route::post('/anmeldung', [AusflugAnmeldungController::class, 'store']);

    Route::get('/verification/{submissionId}', [AusflugAnmeldungController::class, 'verification'])->name('ausflug.verification');
});

require __DIR__.'/passkeys.php';

require __DIR__.'/auth.php';

Route::get('/.well-known/change-password', fn () => to_route('profile.edit'));
