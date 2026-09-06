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
Route::get('/receipt-admin/{filename}', 'ReceiptController@openFromAdmin')
    ->name('receipt.admin');
    
Route::middleware('authentication')->group(function () {
    Route::prefix('download')->group(function () {
        Route::get('from-public', 'DownloadController@fromPublic');
    });

    Route::prefix('auth')->group(function () {
        Route::match(['get', 'post'], 'not-verified', 'AuthController@notVerified');
        Route::match(['get', 'post'], 'change-password', 'AuthController@changePassword');
        Route::match(['get', 'post'], 'profile', 'AuthController@profile');
        Route::post('check-ajax-password', 'AuthController@checkAjaxPassword');
        Route::post('send-otp', 'AuthController@sendOTP');
        Route::post('verify-otp', 'AuthController@verifyOTP');
        Route::get('logout', 'AuthController@logout');
    });

    Route::prefix('datatable-serverside')->group(function () {
        Route::post('catalog', 'DataTableServersideController@catalog');
        Route::post('catalog-parent', 'DataTableServersideController@catalogParent');
        Route::post('catalog-history', 'DataTableServersideController@catalogHistory');
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
        Route::post('datatable', 'RequestFileController@datatable');
        Route::post('datatable-collection', 'RequestFileController@datatableCollection');
        Route::post('create-data', 'RequestFileController@createData');
    });

    Route::prefix('digital-storage-handover')->namespace('DigitalStorageHandover')->group(function () {
        Route::prefix('draft')->group(function () {
            Route::get('/', 'DraftController@index');
            Route::post('datatable', 'DraftController@datatable');
            Route::match(['get', 'post'], 'detail/{id}', 'DraftController@detail');
            Route::delete('destroy-data', 'DraftController@destroyData');
        });

        Route::prefix('reject')->group(function () {
            Route::get('/', 'RejectController@index');
            Route::post('datatable', 'RejectController@datatable');
            Route::get('detail/{id}', 'RejectController@detail');
        });

        Route::prefix('problem')->group(function () {
            Route::get('/', 'ProblemController@index');
            Route::post('datatable', 'ProblemController@datatable');
            Route::match(['get', 'post'], 'detail/{id}', 'ProblemController@detail');
        });

        Route::prefix('review')->group(function () {
            Route::get('/', 'ReviewController@index');
            Route::post('datatable', 'ReviewController@datatable');
            Route::get('detail/{id}', 'ReviewController@detail');
        });

        Route::prefix('accept')->group(function () {
            Route::get('/', 'AcceptController@index');
            Route::post('datatable', 'AcceptController@datatable');
            Route::match(['get', 'post'], 'detail/{id}', 'AcceptController@detail');
            Route::get('receipt/{id}', 'AcceptController@receipt');
        });

        Route::prefix('single-upload-isbn')->group(function () {
            Route::get('/', 'SingleUploadISBNController@index');
            Route::post('datatable', 'SingleUploadISBNController@datatable');
            Route::post('submission', 'SingleUploadISBNController@submission');
            Route::post('uploaded', 'SingleUploadISBNController@uploaded');
            Route::match(['get', 'post'], 'update-data/{id}', 'SingleUploadISBNController@updateData');
            Route::delete('destroy-data', 'SingleUploadISBNController@destroyData');
        });

        Route::prefix('single-upload-non-isbn')->group(function () {
            Route::get('/', 'SingleUploadNonISBNController@index');
            Route::get('catalog-parent', 'SingleUploadNonISBNController@catalogParent');
            Route::post('submitted', 'SingleUploadNonISBNController@submitted');
        });

        Route::prefix('bulk-upload')->group(function () {
            Route::get('/', 'BulkUploadController@index');
            Route::post('datatable-bulk', 'BulkUploadController@datatableBulk');
            Route::get('detail-bulk', 'BulkUploadController@detailBulk');
            Route::post('submitted', 'BulkUploadController@submitted');
        });
    });

    Route::prefix('physical-handover')->namespace('PhysicalHandover')->group(function () {
        Route::prefix('add-delivery-form')->group(function () {
            Route::get('/', 'AddDeliveryFormController@index');
            Route::get('search-isbn', 'AddDeliveryFormController@searchISBN');
            Route::get('select-catalog', 'AddDeliveryFormController@selectCatalog');
            Route::get('calculate-cost', 'AddDeliveryFormController@calculateCost');
            Route::post('submitted', 'AddDeliveryFormController@submitted');
        });

        Route::prefix('add-delivery-form-v2')->group(function () {
            Route::get('/', 'AddDeliveryFormV2Controller@index');
            Route::get('search-isbn', 'AddDeliveryFormV2Controller@searchISBN');
            Route::get('calculate-cost', 'AddDeliveryFormV2Controller@calculateCost');
            Route::post('submitted', 'AddDeliveryFormV2Controller@submitted');
        });

        Route::prefix('delivery-monitoring')->group(function () {
            Route::get('/', 'DeliveryMonitoringController@index');
            Route::post('datatable', 'DeliveryMonitoringController@datatable');
            Route::get('show-data', 'DeliveryMonitoringController@showData');
            Route::post('update-data', 'DeliveryMonitoringController@updateData');
            Route::get('detail/{id}', 'DeliveryMonitoringController@detail');
            Route::get('print-label/{id}', 'DeliveryMonitoringController@printLabel');
            Route::delete('destroy-data', 'DeliveryMonitoringController@destroyData');
        });

        Route::prefix('delivery-accept')->group(function () {
            Route::get('/', 'DeliveryAcceptController@index');
            Route::post('datatable', 'DeliveryAcceptController@datatable');
            Route::get('detail/{id}', 'DeliveryAcceptController@detail');
            Route::get('print/{id}', 'DeliveryAcceptController@print');
        });

        Route::prefix('accept')->group(function () {
            Route::get('/', 'AcceptController@index');
            Route::post('datatable', 'AcceptController@datatable');
        });

        Route::prefix('in-delivery')->group(function () {
            Route::get('/', 'InDeliveryController@index');
            Route::post('datatable', 'InDeliveryController@datatable');
        });

        Route::prefix('reject')->group(function () {
            Route::get('/', 'RejectController@index');
            Route::post('datatable', 'RejectController@datatable');
            Route::post('grant', 'RejectController@grant');
            Route::post('retur', 'RejectController@retur');
        });

        Route::prefix('grant')->group(function () {
            Route::get('/', 'GrantController@index');
            Route::post('datatable', 'GrantController@datatable');
        });

        Route::prefix('retur')->group(function () {
            Route::get('/', 'ReturController@index');
            Route::post('datatable', 'ReturController@datatable');
            Route::post('grant', 'ReturController@grant');
        });
    });

    Route::prefix('bill-isbn')->group(function () {
        Route::get('/', 'BillISBNController@index');
        Route::post('datatable', 'BillISBNController@datatable');
        Route::get('load-summary', 'BillISBNController@loadSummary');
    });

    Route::prefix('documentation')->namespace('Documentation')->group(function () {
        Route::prefix('access-api')->group(function () {
            Route::get('/', 'AccessAPIController@index');
            Route::post('request-access', 'AccessAPIController@requestAPIAccess');
            Route::post('generate-new-token', 'AccessAPIController@generateNewToken');
        });
    });
});
