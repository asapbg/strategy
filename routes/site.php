<?php

use App\Http\Controllers\ImpactAssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('site.home');
});

Route::get('/consultations', function () {
    return view('site.consultations');
});

Route::controller(ImpactAssessmentController::class)->group(function () {
    Route::get('/impact_assessment', 'index')->name('impact_assessment.index');
    Route::get('/impact_assessment/{form}', 'form')->name('impact_assessment.form');
    Route::post('/impact_assessment/{form}', 'store')->name('impact_assessment.store');
});