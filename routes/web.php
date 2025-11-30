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

    Route::prefix('datatable-serverside')->group(function () {
        Route::get('catalog', 'DataTableServersideController@catalog');
        Route::get('catalog-parent', 'DataTableServersideController@catalogParent');
        Route::get('catalog-history', 'DataTableServersideController@catalogHistory');
    });

    Route::prefix('select2-serverside')->group(function () {
        Route::get('branch', 'Select2ServersideController@branch');
        Route::get('executor', 'Select2ServersideController@executor');
        Route::get('location', 'Select2ServersideController@location');
        Route::get('collection-parent', 'Select2ServersideController@collectionParent');
        Route::get('problem', 'Select2ServersideController@problem');
        Route::get('catalog', 'Select2ServersideController@catalog');
        Route::get('currency', 'Select2ServersideController@currency');
        Route::get('promotion', 'Select2ServersideController@promotion');
        Route::get('news-category', 'Select2ServersideController@newsCategory');
        Route::get('news', 'Select2ServersideController@news');
    });

    Route::get('home', function () {
        return view('layouts.index', [
            'data' => [
                'content' => 'home'
            ]
        ]);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', 'DashboardController@index');
        Route::get('data-media-type', 'DashboardController@dataMediaType');
        Route::get('data-worksheet', 'DashboardController@dataWorksheet');
        Route::get('data-collection-status', 'DashboardController@dataCollectionStatus');
        Route::get('data-total-works', 'DashboardController@dataTotalWorks');
        Route::get('data-activity', 'DashboardController@dataActivity');
    });

    Route::prefix('request-file')->group(function () {
        Route::get('/', 'RequestFileController@index');
        Route::get('datatable', 'RequestFileController@datatable');
        Route::post('create-data', 'RequestFileController@createData');
    });

    Route::prefix('digital-storage-handover')->namespace('DigitalStorageHandover')->group(function () {
        Route::prefix('draft')->group(function () {
            Route::get('/', 'DraftController@index');
            Route::get('datatable', 'DraftController@datatable');
            Route::match(['get', 'post'], 'detail/{id}', 'DraftController@detail');
        });

        Route::prefix('single-upload')->group(function () {
            Route::get('/', 'SingleUploadController@index');
            Route::get('check-isbn-code', 'SingleUploadController@checkISBNCode');
            Route::get('catalog-parent', 'SingleUploadController@catalogParent');
            Route::post('submitted', 'SingleUploadController@submitted');
        });

        Route::prefix('bulk-upload')->group(function () {
            Route::get('/', 'BulkUploadController@index');
            Route::get('datatable-bulk', 'BulkUploadController@datatableBulk');
            Route::get('detail-bulk', 'BulkUploadController@detailBulk');
            Route::post('submitted', 'BulkUploadController@submitted');
        });
    });
});
