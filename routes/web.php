<?php

use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/', 'AuthController@login');
Route::match(['get', 'post'], 'reset-password-request', 'AuthController@resetPasswordRequest');
Route::match(['get', 'post'], 'reset-password-action', 'AuthController@resetPasswordAction');

Route::get('stream-file', function (Request $request) {
    if ($request->type && $request->id && $request->filename) {
        return QueryAPI::getFile([
            'type' => $request->type,
            'id' => $request->id,
            'filename' => $request->filename,
        ]);
    }
});

Route::middleware('authentication')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::match(['get', 'post'], 'change-password', 'AuthController@changePassword');
        Route::match(['get', 'post'], 'profile', 'AuthController@profile');
        Route::get('logout', 'AuthController@logout');
    });

    Route::get('home', function () {
        return view('layouts.index', [
            'data' => [
                'content' => 'home'
            ]
        ]);
    });
});
