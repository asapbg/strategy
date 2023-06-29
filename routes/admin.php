<?php

use App\Http\Controllers\Admin\Nomenclature\InstitutionLevelController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationCategoryController;
use App\Http\Controllers\Admin\Nomenclature\ActTypeController;
use App\Http\Controllers\Admin\Nomenclature\LegalActTypeController;
use App\Http\Controllers\Admin\Nomenclature\StrategicDocumentLevelController;
use App\Http\Controllers\Admin\Nomenclature\StrategicDocumentTypeController;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'administration']], function() {
    Route::controller(InstitutionLevelController::class)->group(function () {
        Route::get('/nomenclature/institution_level', 'index')->name('nomenclature.institution_level')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/institution_level/edit/{item?}', 'edit')->name('nomenclature.institution_level.edit');
        Route::match(['post', 'put'], '/nomenclature/institution_level/store/{item?}', 'store')->name('nomenclature.institution_level.store');
    });

    Route::controller(ConsultationCategoryController::class)->group(function () {
        Route::get('/nomenclature/consultation_category', 'index')->name('nomenclature.consultation_category')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/consultation_category/edit/{item?}', 'edit')->name('nomenclature.consultation_category.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_category/store/{item?}', 'store')->name('nomenclature.consultation_category.store');
    });

    Route::controller(ActTypeController::class)->group(function () {
        Route::get('/nomenclature/act_type', 'index')->name('nomenclature.act_type')->middleware('can:viewAny,App\Models\ActType');
        Route::get('/nomenclature/act_type/edit/{item?}', 'edit')->name('nomenclature.act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/act_type/store/{item?}', 'store')->name('nomenclature.act_type.store');
    });

    Route::controller(LegalActTypeController::class)->group(function () {
        Route::get('/nomenclature/legal_act_type', 'index')->name('nomenclature.legal_act_type')->middleware('can:viewAny,App\Models\LegalActType');
        Route::get('/nomenclature/legal_act_type/edit/{item?}', 'edit')->name('nomenclature.legal_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/legal_act_type/store/{item?}', 'store')->name('nomenclature.legal_act_type.store');
    });

    Route::controller(StrategicDocumentLevelController::class)->group(function () {
        Route::get('/nomenclature/strategic_document_level', 'index')->name('nomenclature.strategic_document_level')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/strategic_document_level/edit/{item?}', 'edit')->name('nomenclature.strategic_document_level.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic_document_level/store/{item?}', 'store')->name('nomenclature.strategic_document_level.store');
    });

    Route::controller(StrategicDocumentTypeController::class)->group(function () {
        Route::get('/nomenclature/strategic_document_type', 'index')->name('nomenclature.strategic_document_type')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/strategic_document_type/edit/{item?}', 'edit')->name('nomenclature.strategic_document_type.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic_document_type/store/{item?}', 'store')->name('nomenclature.strategic_document_type.store');
    });
});