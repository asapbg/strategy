<?php

use App\Http\Controllers\Admin\Consultations\PublicConsultationController;
use App\Http\Controllers\Admin\StrategicDocumentsController;
use App\Http\Controllers\Admin\Nomenclature\LinkCategoryController;
use App\Http\Controllers\Admin\Nomenclature\ProgramProjectController;
use App\Http\Controllers\Admin\NomenclatureController;
use App\Http\Controllers\Admin\Nomenclature\InstitutionLevelController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationLevelController;
use App\Http\Controllers\Admin\Nomenclature\ActTypeController;
use App\Http\Controllers\Admin\Nomenclature\LegalActTypeController;
use App\Http\Controllers\Admin\Nomenclature\StrategicDocumentLevelController;
use App\Http\Controllers\Admin\Nomenclature\StrategicDocumentTypeController;
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

        Route::controller(StrategicDocumentsController::class)->group(function () {
            Route::get('/strategic_documents', 'index')->name('strategic_documents.index')->middleware('can:viewAny,App\Models\StrategicDocument');
            Route::get('/strategic_documents/edit/{item?}', 'edit')->name('strategic_documents.edit');
            Route::match(['post', 'put'], '/strategic_documents/store/{item?}', 'store')->name('strategic_documents.store');
        });

        /*Route::view('/strategic_documents', 'admin.strategic_documents.index')
            ->name('strategic_documents.index');
        Route::view('/strategic_documents/edit/{item?}', 'admin.strategic_documents.edit')
            ->name('strategic_documents.edit');*/

        Route::view('/strategic_documents/institutions', 'admin.strategic_documents.institutions.index')
            ->name('strategic_documents.institutions.index');
        Route::view('/strategic_documents/institutions/edit/{item?}', 'admin.strategic_documents.institutions.edit')
            ->name('strategic_documents.institutions.edit');
        
        Route::view('/news', 'admin.news.index')
            ->name('news.index');
        Route::view('/news/edit/{item?}', 'admin.news.edit')
            ->name('news.edit');
        Route::view('/news/categories', 'admin.news.categories.index')
            ->name('news.categories.index');
        Route::view('/news/categories/edit/{item?}', 'admin.news.categories.edit')
            ->name('news.categories.edit');
        
        Route::view('/polls', 'admin.polls.index')
            ->name('polls.index');
        Route::view('/polls/edit/{item?}', 'admin.polls.edit')
            ->name('polls.edit');
            
        Route::view('/publications', 'admin.publications.index')
            ->name('publications.index');
        Route::view('/publications/edit/{item?}', 'admin.publications.edit')
            ->name('publications.edit');
        Route::view('/publications/categories', 'admin.publications.categories.index')
            ->name('publications.categories.index');
        Route::view('/publications/categories/edit/{item?}', 'admin.publications.categories.edit')
            ->name('publications.categories.edit');
            
        Route::view('/ogp/plan_elements', 'admin.ogp.plan_elements.index')
            ->name('ogp.plan_elements.index');
        Route::view('/ogp/plan_elements/edit/{item?}', 'admin.ogp.plan_elements.edit')
            ->name('ogp.plan_elements.edit');
        Route::view('/ogp/estimations', 'admin.ogp.estimations.index')
            ->name('ogp.estimations.index');
        Route::view('/ogp/estimations/edit/{item?}', 'admin.ogp.estimations.edit')
            ->name('ogp.estimations.edit');
        Route::view('/ogp/articles', 'admin.ogp.articles.index')
            ->name('ogp.articles.index');
        Route::view('/ogp/articles/edit/{item?}', 'admin.ogp.articles.edit')
            ->name('ogp.articles.edit');
            
        Route::view('/pc_subjects', 'admin.pc_subjects.index')
            ->name('pc_subjects.index');
        Route::view('/pc_subjects/edit/{item?}', 'admin.pc_subjects.edit')
            ->name('pc_subjects.edit');
            
        Route::view('/legislative_initiatives', 'admin.legislative_initiatives.index')
            ->name('legislative_initiatives.index');
        Route::view('/legislative_initiatives/edit/{item?}', 'admin.legislative_initiatives.edit')
            ->name('legislative_initiatives.edit');
        
        Route::view('/links', 'admin.links.index')
            ->name('links.index');
        Route::view('/links/edit/{item?}', 'admin.links.edit')
            ->name('links.edit');
        
        Route::view('/pages', 'admin.pages.index')
            ->name('pages.index');
        Route::view('/pages/edit/{item?}', 'admin.pages.edit')
            ->name('pages.edit');
        
        Route::view('/pages', 'admin.pages.index')
            ->name('pages.index');
        Route::view('/pages/edit/{item?}', 'admin.pages.edit')
            ->name('pages.edit');
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
        Route::get('/nomenclature/strategic_document_type', 'index')->name('nomenclature.strategic_document_type')->middleware('can:viewAny,App\Models\StrategicDocumentType');
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
        Route::get('/nomenclature/consultation_category', 'index')->name('nomenclature.consultation_category')->middleware('can:viewAny,App\Models\ConsultationCategory');
        Route::get('/nomenclature/consultation_category/edit/{item?}', 'edit')->name('nomenclature.consultation_category.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_category/store/{item?}', 'store')->name('nomenclature.consultation_category.store');
    });

    Route::controller(ConsultationTypeController::class)->group(function () {
        Route::get('/nomenclature/consultation_type', 'index')->name('nomenclature.consultation_type')->middleware('can:viewAny,App\Models\ConsultationType');
        Route::get('/nomenclature/consultation_type/edit/{item?}', 'edit')->name('nomenclature.consultation_type.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation_type/store/{item?}', 'store')->name('nomenclature.consultation_type.store');
    });

    Route::controller(ProgramProjectController::class)->group(function () {
        Route::get('/nomenclature/program_project', 'index')->name('nomenclature.program_project')->middleware('can:viewAny,App\Models\ProgramProject');
        Route::get('/nomenclature/program_project/edit/{item?}', 'edit')->name('nomenclature.program_project.edit');
        Route::match(['post', 'put'], '/nomenclature/program_project/store/{item?}', 'store')->name('nomenclature.program_project.store');
    });

    Route::controller(LinkCategoryController::class)->group(function () {
        Route::get('/nomenclature/link_category', 'index')->name('nomenclature.link_category')->middleware('can:viewAny,App\Models\LinkCategory');
        Route::get('/nomenclature/link_category/edit/{item?}', 'edit')->name('nomenclature.link_category.edit');
        Route::match(['post', 'put'], '/nomenclature/link_category/store/{item?}', 'store')->name('nomenclature.link_category.store');
    });
});