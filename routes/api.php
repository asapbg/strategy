<?php

use App\Http\Controllers\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['guest']], function() {
    Route::controller(ReportsController::class)->group(function () {
        Route::get('/reports/public-consultations/{type}/view',       'apiReportPc')->name('api.report.pc');
        Route::get('/report/strategic-documents/{type}/view',       'apiReportSd')->name('api.report.sd');
//        Route::get('/report/advisory-boards/{type}/view',       'apiReportAdvBoard')->name('api.report.adv_board');
    });
});
