<?php

use App\Http\Controllers\ImpactAssessmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('site.home');
})->name('home');

Route::get('/consultations', function () {
    return view('site.consultations');
});

Route::controller(ImpactAssessmentController::class)->group(function () {
    Route::get('/impact_assessment', 'index')->name('impact_assessment.index');
    Route::get('/impact_assessment/{form}', 'form')->name('impact_assessment.form');
    Route::post('/impact_assessment/{form}', 'store')->name('impact_assessment.store');
    Route::get('/impact_assessment/{form}/pdf/{inputId}', 'pdf')->name('impact_assessment.pdf');
    Route::get('/impact_assessment/{form}/show/{inputId}', 'show')->name('impact_assessment.show');
});


Route::controller(ProfileController::class)->middleware('auth')->group(function () {
    Route::get('/profile/{tab?}', 'index')->name('profile');
    Route::post('/profile/{tab?}', 'store')->name('profile.store');
});
