<?php

use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\PasskeyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::post('/contact-form/send', [ContactFormController::class, 'store'])
    ->middleware(HandlePrecognitiveRequests::class)
    ->name('contact-form.send');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Passkeys
    Route::resource('/profile/passkeys', PasskeyController::class)->only(['store', 'destroy']);
    Route::get('/profile/passkeys/register', [PasskeyController::class, 'register'])->name('passkeys.register');
});

Route::inertia('/impressum', 'Imprint')->name('imprint');
Route::inertia('/privacy', 'Privacy')->name('privacy');

require __DIR__.'/auth.php';

Route::get('/.well-known/change-password', fn () => to_route('profile.edit'));
