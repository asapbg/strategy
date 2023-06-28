<?php

use App\Http\Controllers\Admin\Nomenclature\InstitutionLevelController;

use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'administration']], function() {
    Route::controller(InstitutionLevelController::class)->group(function () {
        Route::get('/nomenclature/institution_level', 'index')->name('nomenclature.institution_level')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/institution_level/edit/{item?}', 'edit')->name('nomenclature.institution_level.edit');
        Route::match(['post', 'put'], '/nomenclature/institution_level/store/{item?}', 'store')->name('nomenclature.institution_level.store');
    });
});