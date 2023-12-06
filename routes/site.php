<?php

use App\Http\Controllers\ExecutorController;
use App\Http\Controllers\ImpactAssessmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('site.home');
})->name('home');

Route::controller(\App\Http\Controllers\AdvisoryBoardController::class)->prefix('advisory-boards')->group(function() {
    Route::get('', 'index')->name('advisory-boards.index');
    Route::get('{item}/view', 'show')->name('advisory-boards.view');
});

Route::controller(\App\Http\Controllers\ArchiveController::class)->group(function () {
    Route::get('/archive', 'index')->name('archive.index');
});

Route::controller(\App\Http\Controllers\AnalyzeMethodsController::class)->group(function () {
    Route::get('/impact-analyze-methods', 'index')->name('impact-analyze-methods.index');
});

Route::controller(\App\Http\Controllers\PollController::class)->group(function () {
    Route::get('polls', 'index')->name('poll.index');
    Route::get('poll/{id}/show', 'show')->name('poll.show');
    Route::post('poll', 'store')->name('poll.store');
});

Route::controller(\App\Http\Controllers\PartnershipController::class)->group(function () {
    Route::get('partnerships', 'index')->name('partnerships.index');
    Route::get('partnership/view', 'show')->name('partnership.view');
});

Route::controller(\App\Http\Controllers\ReportController::class)->group(function () {
    Route::get('reports', 'index')->name('reports.index');
    Route::get('report/view', 'show')->name('report.view');
});

Route::get('/consultations', function () {
    return view('site.consultations');
});

Route::controller(\App\Http\Controllers\PublicConsultationController::class)->group(function () {
    Route::get('/public-consultations', 'index')->name('public_consultation.index');
    Route::get('/public-consultations/{id}', 'show')->name('public_consultation.view');
    Route::post('/public-consultations/add-comment', 'addComment')->name('public_consultation.comment.add');
});

Route::controller(\App\Http\Controllers\PrisController::class)->group(function () {
    Route::get('/pris', 'index')->name('pris.index');
    Route::get('/pris/legal-information/{category}/{id}', 'show')->where('id', '[0-9]+')->name('pris.view');
    Route::get('/pris/archive', 'archive')->name('pris.archive');
    Route::get('/pris/{category}', 'index')->name('pris.category');
    Route::get('/pris/legal-information/{category}', 'index')->name('pris.category_slug');
});

Route::controller(\App\Http\Controllers\OperationalProgramController::class)->group(function () {
    Route::get('/operational-programs', 'index')->name('op.index');
    Route::get('/operational-programs/{id}', 'show')->name('op.view');
});

Route::controller(\App\Http\Controllers\LegislativeProgramController::class)->group(function () {
    Route::get('/legislative-programs', 'index')->name('lp.index');
    Route::get('/legislative-programs/{id}', 'show')->name('lp.view');
});

Route::controller(\App\Http\Controllers\StrategicDocumentsController::class)->group(function() {
    Route::get('/strategy-documents/{search?}', 'index')->name('strategy-documents.index');
    Route::get('/strategy-document/list/{search?}', 'listStrategicDocuments')->name('strategy-document.list');

    Route::get('/strategy-document/{id}', 'show')->name('strategy-document.view');
    Route::get('/strategy-document/download-file/{id}', 'downloadDocFile')->name('strategy-document.download-file');
    Route::get('/strategy-document/file-preview-modal/{id}', 'previewModalFile')->name('strategy-document.preview.file_modal');
});

Route::controller(ImpactAssessmentController::class)->prefix('/impact_assessments')->as('impact_assessment.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('/executors', 'executors')->name('executors');
    Route::get('/forms', 'forms')->name('forms');
    Route::get('/{form}', 'form')->name('form');
    Route::post('/{form}', 'store')->name('store');
    Route::get('/{form}/pdf/{inputId}', 'pdf')->name('pdf');
    Route::get('/{form}/show/{inputId}', 'show')->name('show');
});

Route::controller(ProfileController::class)->middleware('auth')->group(function () {
    Route::get('/profile/{tab?}', 'index')->name('profile');
    Route::post('/profile/{tab?}', 'store')->name('profile.store');
});

Route::controller(\App\Http\Controllers\LegislativeInitiativeController::class)->group(function() {
    Route::get('/legislative-initiatives', 'index')->name('legislative_initiatives.index');
    Route::get('/legislative-initiatives/create', 'create')->name('legislative_initiatives.create');
    Route::post('/legislative-initiatives/store', 'store')->name('legislative_initiatives.store');
    Route::get('/legislative-initiatives/{item}/view', 'show')->name('legislative_initiatives.view');
    Route::get('/legislative-initiatives/{item}/edit', 'edit')->name('legislative_initiatives.edit');
    Route::post('/legislative-initiatives/{item}/update', 'update')->name('legislative_initiatives.update');
    Route::post('/legislative-initiatives/{item}/delete', 'destroy')->name('legislative_initiatives.delete');
});

Route::controller(\App\Http\Controllers\LegislativeInitiativeVotesController::class)->prefix('/legislative-initiatives/{item}/vote/')->group(function () {
    Route::get('store/{is_like}', 'store')->name('legislative_initiatives.vote.store');
    Route::get('revert', 'revert')->name('legislative_initiatives.vote.revert');
});

Route::controller(\App\Http\Controllers\LegislativeInitiativeCommentController::class)->prefix('/legislative-initiatives/comments/')->group(function () {
    Route::post('store', 'store')->name('legislative_initiatives.comments.store');
    Route::post('{comment}/delete', 'destroy')->name('legislative_initiatives.comments.delete');
});

Route::controller(\App\Http\Controllers\LegislativeInitiativeCommentStatController::class)->prefix('/legislative-initiatives/comments/{comment}/stats')->group(function () {
    Route::get('store/{is_like}', 'store')->name('legislative_initiatives.comments.stats.store');
    Route::get('revert', 'revert')->name('legislative_initiatives.comments.stats.revert');
});
