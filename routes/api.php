<?php

use App\Http\Controllers\Api\GoogleAuthenticatorController;
use Illuminate\Support\Facades\Route;

Route::prefix('google-authenticator')->group(function () {
    Route::get('generate', [GoogleAuthenticatorController::class, 'generate']);
    Route::get('verify', [GoogleAuthenticatorController::class, 'verify']);
});

