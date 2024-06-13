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
        //Impact assessments
        Route::get('/reports/impact_assessments/{type}/view',       'apiReportImpactAssessments')->name('api.report.ia');
        //Library
        Route::get('/reports/library/{type}/view',       'apiReportLibrary')->name('api.report.library');
        //Library
        Route::get('/reports/polls/{type}/view',       'apiReportPolls')->name('api.report.polls');
        //Pris
        Route::get('/reports/pris/{type}/view',       'apiReportPris')->name('api.report.pris');
        //Legislative initiatives
        Route::get('/reports/legislative-initiatives/{type}/view',       'apiReportLegislativeInitiative')->name('api.report.li');
        //Public consultations
        Route::get('/reports/public-consultations/{type}/view',       'apiReportPc')->name('api.report.pc');
        //Legislative Program
        Route::get('/reports/legislative-program/{type}/view',       'apiReportLp')->name('api.report.lp');
        //Operational Program
        Route::get('/reports/operational-program/{type}/view',       'apiReportOp')->name('api.report.op');


        //Strategic Document
        Route::get('/reports/strategic-documents/{type}/view',       'apiReportSd')->name('api.report.sd');

    });
});
