<?php

use App\Http\Controllers\Admin\Consultations\PublicConsultationController;
use App\Http\Controllers\Admin\Nomenclature\LinkCategoryController;
use App\Http\Controllers\Admin\Nomenclature\ProgramProjectController;
use App\Http\Controllers\Admin\NomenclatureController;
use App\Http\Controllers\Admin\Nomenclature\InstitutionLevelController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationLevelController;
use App\Http\Controllers\Admin\Nomenclature\ActTypeController;
use App\Http\Controllers\Admin\Nomenclature\LegalActTypeController;
use App\Http\Controllers\Admin\Nomenclature\StrategicDocumentLevelController;
use App\Http\Controllers\Admin\Nomenclature\StrategicConsultationDocumentTypeController;
use App\Http\Controllers\Admin\Nomenclature\AuthorityAcceptingStrategicController;
use App\Http\Controllers\Admin\Nomenclature\AuthorityAdvisoryBoardController;
use App\Http\Controllers\Admin\Nomenclature\AdvisoryActTypeController;
use App\Http\Controllers\Admin\Nomenclature\StrategicActTypeController;
use App\Http\Controllers\Admin\Nomenclature\AdvisoryChairmanTypeController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationCategoryController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationTypeController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationDocumentTypeController;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'administration']], function() {
    // Manager controllers
    Route::controller(PublicConsultationController::class)->group(function () {
        Route::get('/consultations/public_consultations', 'index')->name('consultations.public_consultations.index');
        Route::get('/consultations/public_consultations/edit/{item?}', 'edit')->name('consultations.public_consultations.edit');
        Route::match(['post', 'put'], '/consultations/public_consultations/store/{item?}', 'store')->name('consultations.public_consultations.store');
    });

    // Mock controllers
    Route::group([], function () {
        Route::view('/consultations/legislative_programs', 'admin.consultations.legislative_programs.index')
            ->name('consultations.legislative_programs.index');
        Route::view('/consultations/legislative_programs/edit/{item?}', 'admin.consultations.legislative_programs.edit')
            ->name('consultations.legislative_programs.edit');
        
        Route::view('/consultations/operational_programs', 'admin.consultations.operational_programs.index')
            ->name('consultations.operational_programs.index');
        Route::view('/consultations/operational_programs/edit/{item?}', 'admin.consultations.operational_programs.edit')
            ->name('consultations.operational_programs.edit');
            
        Route::view('/consultations/comments', 'admin.consultations.comments.index')
            ->name('consultations.comments.index');

        Route::view('/strategic_documents', 'admin.strategic_documents.index')
            ->name('strategic_documents.index');
        Route::view('/strategic_documents/edit/{item?}', 'admin.strategic_documents.edit')
            ->name('strategic_documents.edit');

        Route::view('/strategic_documents/institutions', 'admin.strategic_documents.institutions.index')
            ->name('strategic_documents.institutions.index');
        Route::view('/strategic_documents/institutions/edit/{item?}', 'admin.strategic_documents.institutions.edit')
            ->name('strategic_documents.institutions.edit');
    });

    // Nomenclatures
    Route::controller(NomenclatureController::class)->group(function () {
        Route::get('/nomenclature', 'index')->name('nomenclature');
    });

    Route::controller(InstitutionLevelController::class)->group(function () {
        Route::get('/nomenclature/institution_level', 'index')->name('nomenclature.institution_level')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/institution_level/edit/{item?}', 'edit')->name('nomenclature.institution_level.edit');
        Route::match(['post', 'put'], '/nomenclature/institution_level/store/{item?}', 'store')->name('nomenclature.institution_level.store');
    });

    Route::controller(ConsultationLevelController::class)->group(function () {
        Route::get('/nomenclature/consultation_level', 'index')->name('nomenclature.consultation_level')->middleware('can:viewAny,App\Models\ConsultationLevel');
        Route::get('/nomenclature/consultation_level/edit/{item?}', 'edit')->name('nomenclature.consultation_level.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_level/store/{item?}', 'store')->name('nomenclature.consultation_level.store');
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
        Route::get('/nomenclature/strategic_document_level', 'index')->name('nomenclature.strategic_document_level')->middleware('can:viewAny,App\Models\StrategicDocumentLevel');
        Route::get('/nomenclature/strategic_document_level/edit/{item?}', 'edit')->name('nomenclature.strategic_document_level.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic_document_level/store/{item?}', 'store')->name('nomenclature.strategic_document_level.store');
    });

    Route::controller(StrategicDocumentTypeController::class)->group(function () {
        Route::get('/nomenclature/strategic_document_type', 'index')->name('nomenclature.strategic_document_type')->middleware('can:viewAny,App\Models\StrategicConsultationDocumentType');
        Route::get('/nomenclature/strategic_document_type/edit/{item?}', 'edit')->name('nomenclature.strategic_document_type.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic_document_type/store/{item?}', 'store')->name('nomenclature.strategic_document_type.store');
    });

    Route::controller(AuthorityAcceptingStrategicController::class)->group(function () {
        Route::get('/nomenclature/authority_accepting_strategic', 'index')->name('nomenclature.authority_accepting_strategic')->middleware('can:viewAny,App\Models\AuthorityAcceptingStrategic');
        Route::get('/nomenclature/authority_accepting_strategic/edit/{item?}', 'edit')->name('nomenclature.authority_accepting_strategic.edit');
        Route::match(['post', 'put'], '/nomenclature/authority_accepting_strategic/store/{item?}', 'store')->name('nomenclature.authority_accepting_strategic.store');
    });

    Route::controller(AuthorityAdvisoryBoardController::class)->group(function () {
        Route::get('/nomenclature/authority_advisory_board', 'index')->name('nomenclature.authority_advisory_board')->middleware('can:viewAny,App\Models\AuthorityAdvisoryBoard');
        Route::get('/nomenclature/authority_advisory_board/edit/{item?}', 'edit')->name('nomenclature.authority_advisory_board.edit');
        Route::match(['post', 'put'], '/nomenclature/authority_advisory_board/store/{item?}', 'store')->name('nomenclature.authority_advisory_board.store');
    });

    Route::controller(AdvisoryActTypeController::class)->group(function () {
        Route::get('/nomenclature/advisory_act_type', 'index')->name('nomenclature.advisory_act_type')->middleware('can:viewAny,App\Models\AdvisoryActType');
        Route::get('/nomenclature/advisory_act_type/edit/{item?}', 'edit')->name('nomenclature.advisory_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/advisory_act_type/store/{item?}', 'store')->name('nomenclature.advisory_act_type.store');
    });

    Route::controller(StrategicActTypeController::class)->group(function () {
        Route::get('/nomenclature/strategic_act_type', 'index')->name('nomenclature.strategic_act_type')->middleware('can:viewAny,App\Models\StrategicActType');
        Route::get('/nomenclature/strategic_act_type/edit/{item?}', 'edit')->name('nomenclature.strategic_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic_act_type/store/{item?}', 'store')->name('nomenclature.strategic_act_type.store');
    });

    Route::controller(AdvisoryChairmanTypeController::class)->group(function () {
        Route::get('/nomenclature/advisory_chairman_type', 'index')->name('nomenclature.advisory_chairman_type')->middleware('can:viewAny,App\Models\AdvisoryChairmanType');
        Route::get('/nomenclature/advisory_chairman_type/edit/{item?}', 'edit')->name('nomenclature.advisory_chairman_type.edit');
        Route::match(['post', 'put'], '/nomenclature/advisory_chairman_type/store/{item?}', 'store')->name('nomenclature.advisory_chairman_type.store');
    });

    Route::controller(ConsultationDocumentTypeController::class)->group(function () {
        Route::get('/nomenclature/consultation_document_type', 'index')->name('nomenclature.consultation_document_type')->middleware('can:viewAny,App\Models\ConsultationDocumentType');
        Route::get('/nomenclature/consultation_document_type/edit/{item?}', 'edit')->name('nomenclature.consultation_document_type.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_document_type/store/{item?}', 'store')->name('nomenclature.consultation_document_type.store');
    });

    Route::controller(ConsultationCategoryController::class)->group(function () {
        Route::get('/nomenclature/consultation_category', 'index')->name('nomenclature.consultation_category')->middleware('can:viewAny,App\Models\ConsultationLevel');
        Route::get('/nomenclature/consultation_category/edit/{item?}', 'edit')->name('nomenclature.consultation_category.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_category/store/{item?}', 'store')->name('nomenclature.consultation_category.store');
    });

    Route::controller(ConsultationTypeController::class)->group(function () {
        Route::get('/nomenclature/consultation_type', 'index')->name('nomenclature.consultation_type')->middleware('can:viewAny,App\Models\ConsultationLevel');
        Route::get('/nomenclature/consultation_type/edit/{item?}', 'edit')->name('nomenclature.consultation_type.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_type/store/{item?}', 'store')->name('nomenclature.consultation_type.store');
    });

    Route::controller(ProgramProjectController::class)->group(function () {
        Route::get('/nomenclature/program_project', 'index')->name('nomenclature.program_project')->middleware('can:viewAny,App\Models\ConsultationLevel');
        Route::get('/nomenclature/program_project/edit/{item?}', 'edit')->name('nomenclature.program_project.edit');
        Route::match(['post', 'put'], '/nomenclature/program_project/store/{item?}', 'store')->name('nomenclature.program_project.store');
    });

    Route::controller(LinkCategoryController::class)->group(function () {
        Route::get('/nomenclature/link_category', 'index')->name('nomenclature.link_category')->middleware('can:viewAny,App\Models\ConsultationLevel');
        Route::get('/nomenclature/link_category/edit/{item?}', 'edit')->name('nomenclature.link_category.edit');
        Route::match(['post', 'put'], '/nomenclature/link_category/store/{item?}', 'store')->name('nomenclature.link_category.store');
    });
});