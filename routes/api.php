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
Route::group(['middleware' => 'api'], function ($router){

    Route::group(['middleware' => 'auth:api'], function (){
        Route::post('/logout',       [\App\Http\Controllers\ApiStrategy\AuthController::class, 'logout']);
        Route::controller(\App\Http\Controllers\ApiStrategy\UsersController::class)->group(function () {
            Route::get('/users',       'users');
            Route::get('/users/{id}',       'viewUser')->where('id', '[0-9]+');
            Route::get('/users/roles',       'roles');
        });

        Route::controller(\App\Http\Controllers\ApiStrategy\LogController::class)->group(function () {
            Route::get('/log',       'list');
            Route::get('/log/subject-types',       'subjects');
            Route::get('/log/causer-types',       'causers');
        });
    });
});

    Route::post('/login',       [\App\Http\Controllers\ApiStrategy\AuthController::class, 'login']);

    Route::controller(\App\Http\Controllers\ApiStrategy\NomenclatureController::class)->prefix('nomenclature')->group(function () {
        //institutions
        Route::get('/institutions',       'institutions');
        Route::get('/laws',       'laws');
        Route::get('/act-types',       'actTypes');
        Route::get('/legal-act-types',       'legalActTypes');
        Route::get('/policy-areas',       'policyAreas');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\PublicConsultationsController::class)->group(function () {
        //Public consultation
        Route::get('/consultations',       'list');
        Route::get('/consultations/{id}',       'show')->where('id', '[0-9]+');
        Route::get('/consultations/{id}/comments',       'comments')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\LegislativeProgramController::class)->group(function () {
        //Legislative programs
        Route::get('/legislative-programs',       'list');
        Route::get('/legislative-programs/{id}',       'show')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\OperationalProgramController::class)->group(function () {
        //Operational programs
        Route::get('/operational-programs',       'list');
        Route::get('/operational-programs/{id}',       'show');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\LegislativeInitiativeController::class)->group(function () {
        //Legislative iniciative
        Route::get('/legislative-initiatives',       'list');
        Route::get('/legislative-initiatives/{id}',       'show')->where('id', '[0-9]+');
        Route::get('/legislative-initiatives/{id}/comments',       'comments')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\AdvisoryBoardController::class)->group(function () {
        //Legislative programs
        Route::get('/advisory-boards',       'list');
        Route::get('/advisory-boards/{id}',       'show')->where('id', '[0-9]+');
        Route::get('/advisory-boards/{id}/meetings',       'meetings')->where('id', '[0-9]+');
        Route::get('/advisory-boards/{id}/news',       'news')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\PollsController::class)->group(function () {
        //Polls
        Route::get('/polls',       'list');
        Route::get('/polls/{id}',       'show')->where('id', '[0-9]+');
        Route::get('/polls/{id}/questions',       'questions')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\StrategicDocumentsController::class)->group(function () {
        //Strategic document
        Route::get('/strategic-documents',       'list');
        Route::get('/strategic-documents/{id}',       'show')->where('id', '[0-9]+');
        Route::get('/strategic-documents/{id}/subdocuments',       'subdocuments')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\OgpController::class)->group(function () {
        //Ogp
        Route::get('/ogp/plans',       'list');
        Route::get('/ogp/plans/news',       'news');
        Route::get('/ogp/plans/{id}',       'show')->where('id', '[0-9]+');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\LibraryController::class)->group(function () {
        //Library
        Route::get('/library',       'list');
    });

    Route::controller(\App\Http\Controllers\ApiStrategy\ImpactAssessmentsController::class)->group(function () {
        //ImpactAssessments
        Route::get('/impact-assessments',       'list');
        Route::get('/executors',       'executors');
        Route::get('/executors/{eik}',       'showExecutor');
    });

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
        //Advisory Boards
        Route::get('/reports/adv-boards/{type}/view',       'apiReportAdvBoards')->name('api.report.adv_boards');
        //Strategic Document
        Route::get('/reports/strategic-documents/{type}/view',       'apiReportSd')->name('api.report.sd');
        //Strategic Document
        Route::get('/reports/ogp/{type}/view',       'apiOgp')->name('api.report.ogp');
    });
