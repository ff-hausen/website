<?php

// Passkeys
use App\Http\Controllers\PasskeyController;

Route::middleware('auth')->group(function () {
    Route::resource('/profile/passkeys', PasskeyController::class)->only(['store', 'destroy']);

    Route::get('/passkeys/register', [PasskeyController::class, 'registerOptions'])->name('passkeys.register');
});

Route::get('/passkeys/authenticate', [PasskeyController::class, 'authenticateOptions'])->name('passkeys.authenticateOptions');
Route::post('/passkeys/authenticate', [PasskeyController::class, 'authenticate'])->name('passkeys.authenticate');
