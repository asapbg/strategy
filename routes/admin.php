<?php

use App\Http\Controllers\Admin\Consultations\LegislativeProgramController;
use App\Http\Controllers\Admin\Consultations\OperationalProgramController;
use App\Http\Controllers\Admin\Consultations\PublicConsultationController;
use App\Http\Controllers\Admin\ImpactPageController;
use App\Http\Controllers\Admin\LegislativeInitiativeController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\Nomenclature\NewsCategoryController;
use App\Http\Controllers\Admin\Nomenclature\RegulatoryActController;
use App\Http\Controllers\Admin\StrategicDocuments\InstitutionController;
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
use App\Http\Controllers\Admin\Nomenclature\ConsultationTypeController;
use App\Http\Controllers\Admin\Nomenclature\ConsultationDocumentTypeController;
use App\Http\Controllers\Admin\Nomenclature\PolicyAreaController;
use App\Http\Controllers\Admin\Nomenclature\PublicationCategoryController;
use App\Http\Controllers\Admin\Nomenclature\RegulatoryActTypeController;
use App\Http\Controllers\Admin\OGP\NewsController as OGPNewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PCSubjectController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'administration']], function() {
    // Settings
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings.index')->middleware('can:viewAny,App\Models\Setting');
        Route::match(['post', 'put'], '/settings/store/{item?}', 'store')->name('settings.store');
    });
    
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

    // Content
    Route::controller(PageController::class)->group(function () {
        Route::get('/pages', 'index')->name('pages.index')->middleware('can:viewAny,App\Models\Page');
        Route::get('/pages/edit/{item?}', 'edit')->name('pages.edit');
        Route::match(['post', 'put'], '/pages/store/{item?}', 'store')->name('pages.store');
    });

    Route::controller(StaticPageController::class)->group(function () {
        Route::get('/static_pages', 'index')->name('static_pages.index')->middleware('can:viewAny,App\Models\Page');
        Route::get('/static_pages/edit/{item?}', 'edit')->name('static_pages.edit');
        Route::match(['post', 'put'], '/static_pages/store/{item?}', 'store')->name('static_pages.store');
    });

    Route::controller(ImpactPageController::class)->group(function () {
        Route::get('/impact_pages', 'index')->name('impact_pages.index')->middleware('can:viewAny,App\Models\Page');
        Route::get('/impact_pages/edit/{item?}', 'edit')->name('impact_pages.edit');
        Route::match(['post', 'put'], '/impact_pages/store/{item?}', 'store')->name('impact_pages.store');
    });

    // Polls
    Route::controller(PollController::class)->group(function () {
        Route::get('/polls', 'index')->name('polls.index')->middleware('can:viewAny,App\Models\Poll');
        Route::get('/polls/edit/{item?}', 'edit')->name('polls.edit');
        Route::match(['post', 'put'], '/polls/store/{item?}', 'store')->name('polls.store');
    });

    // News
    Route::controller(NewsController::class)->group(function () {
        Route::get('/news', 'index')->name('news.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/news/edit/{item?}', 'edit')->name('news.edit');
        Route::match(['post', 'put'], '/news/store/{item?}', 'store')->name('news.store');
    });

    // Library
    Route::controller(PublicationController::class)->group(function () {
        Route::get('/publications', 'index')->name('publications.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/publications/edit/{item?}', 'edit')->name('publications.edit');
        Route::match(['post', 'put'], '/publications/store/{item?}', 'store')->name('publications.store');
    });

    // Consultations
    Route::controller(PublicConsultationController::class)->group(function () {
        Route::get('/consultations/public_consultations', 'index')->name('consultations.public_consultations.index');
        Route::get('/consultations/public_consultations/edit/{item?}', 'edit')->name('consultations.public_consultations.edit');
        Route::match(['post', 'put'], '/consultations/public_consultations/store/{item?}', 'store')->name('consultations.public_consultations.store');
    });
    Route::controller(LegislativeProgramController::class)->group(function () {
        Route::get('/consultations/legislative_programs', 'index')->name('consultations.legislative_programs.index');
        Route::get('/consultations/legislative_programs/edit/{item?}', 'edit')->name('consultations.legislative_programs.edit');
        Route::match(['post', 'put'], '/consultations/legislative_programs/store/{item?}', 'store')->name('consultations.legislative_programs.store');
    });
    Route::controller(OperationalProgramController::class)->group(function () {
        Route::get('/consultations/operational_programs', 'index')->name('consultations.operational_programs.index');
        Route::get('/consultations/operational_programs/edit/{item?}', 'edit')->name('consultations.operational_programs.edit');
        Route::match(['post', 'put'], '/consultations/operational_programs/store/{item?}', 'store')->name('consultations.operational_programs.store');
    });

    // Strategic Documents
    Route::controller(StrategicDocumentsController::class)->group(function () {
        Route::get('/strategic_documents', 'index')->name('strategic_documents.index')->middleware('can:viewAny,App\Models\StrategicDocument');
        Route::get('/strategic_documents/edit/{item?}', 'edit')->name('strategic_documents.edit');
        Route::match(['post', 'put'], '/strategic_documents/store/{item?}', 'store')->name('strategic_documents.store');
    });
    Route::controller(InstitutionController::class)->group(function () {
        Route::get('/strategic_documents/institutions', 'index')->name('strategic_documents.institutions.index')->middleware('can:viewAny,App\Models\Institution');
        Route::get('/strategic_documents/institutions/edit/{item?}', 'edit')->name('strategic_documents.institutions.edit');
        Route::match(['post', 'put'], '/strategic_documents/institutions/store/{item?}', 'store')->name('strategic_documents.institutions.store');
    });

    // Open Govenrnance Partnership
    Route::controller(OGPNewsController::class)->group(function () {
        Route::get('/ogp/articles', 'index')->name('ogp.articles.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/ogp/articles/edit/{item?}', 'edit')->name('ogp.articles.edit');
        Route::match(['post', 'put'], '/ogp/articles/store/{item?}', 'store')->name('ogp.articles.store');
    });

    // Links
    Route::controller(LinkController::class)->group(function () {
        Route::get('/links', 'index')->name('links.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/links/edit/{item?}', 'edit')->name('links.edit');
        Route::match(['post', 'put'], '/links/store/{item?}', 'store')->name('links.store');
    });

    // PC Subjects
    Route::controller(PCSubjectController::class)->group(function () {
        Route::get('/pc_subjects', 'index')->name('pc_subjects.index')->middleware('can:viewAny,App\Models\PCSubject');
        Route::get('/pc_subjects/edit/{item?}', 'edit')->name('pc_subjects.edit');
        Route::match(['post', 'put'], '/pc_subjects/store/{item?}', 'store')->name('pc_subjects.store');
    });

    // Legislative Initiatives
    Route::controller(LegislativeInitiativeController::class)->group(function () {
        Route::get('/legislative_initiatives', 'index')->name('legislative_initiatives.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/legislative_initiatives/edit/{item?}', 'edit')->name('legislative_initiatives.edit');
        Route::match(['post', 'put'], '/legislative_initiatives/store/{item?}', 'store')->name('legislative_initiatives.store');
    });

    // Mock controllers
    Route::group([], function () {
        Route::view('/consultations/comments', 'admin.consultations.comments.index')
            ->name('consultations.comments.index');

        Route::view('/ogp/plan_elements', 'admin.ogp.plan_elements.index')
            ->name('ogp.plan_elements.index');
        Route::view('/ogp/plan_elements/edit/{item?}', 'admin.ogp.plan_elements.edit')
            ->name('ogp.plan_elements.edit');
        Route::view('/ogp/estimations', 'admin.ogp.estimations.index')
            ->name('ogp.estimations.index');
        Route::view('/ogp/estimations/edit/{item?}', 'admin.ogp.estimations.edit')
            ->name('ogp.estimations.edit');
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

    Route::controller(PolicyAreaController::class)->group(function () {
        Route::get('/nomenclature/policy_area', 'index')->name('nomenclature.policy_area')->middleware('can:viewAny,App\Models\LinkCategory');
        Route::get('/nomenclature/policy_area/edit/{item?}', 'edit')->name('nomenclature.policy_area.edit');
        Route::match(['post', 'put'], '/nomenclature/policy_area/store/{item?}', 'store')->name('nomenclature.policy_area.store');
    });

    Route::controller(PublicationCategoryController::class)->group(function () {
        Route::get('/nomenclature/publication_category', 'index')->name('nomenclature.publication_category')->middleware('can:viewAny,App\Models\PublicationCategory');
        Route::get('/nomenclature/publication_category/edit/{item?}', 'edit')->name('nomenclature.publication_category.edit');
        Route::match(['post', 'put'], '/nomenclature/publication_category/store/{item?}', 'store')->name('nomenclature.publication_category.store');
    });

    Route::controller(NewsCategoryController::class)->group(function () {
        Route::get('/nomenclature/news_category', 'index')->name('nomenclature.news_category')->middleware('can:viewAny,App\Models\NewsCategory');
        Route::get('/nomenclature/news_category/edit/{item?}', 'edit')->name('nomenclature.news_category.edit');
        Route::match(['post', 'put'], '/nomenclature/news_category/store/{item?}', 'store')->name('nomenclature.news_category.store');
    });

    Route::controller(RegulatoryActTypeController::class)->group(function () {
        Route::get('/nomenclature/regulatory_act_type', 'index')->name('nomenclature.regulatory_act_type')->middleware('can:viewAny,App\Models\RegulatoryActType');
        Route::get('/nomenclature/regulatory_act_type/edit/{item?}', 'edit')->name('nomenclature.regulatory_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/regulatory_act_type/store/{item?}', 'store')->name('nomenclature.regulatory_act_type.store');
    });

    Route::controller(RegulatoryActController::class)->group(function () {
        Route::get('/nomenclature/regulatory_act', 'index')->name('nomenclature.regulatory_act')->middleware('can:viewAny,App\Models\RegulatoryAct');
        Route::get('/nomenclature/regulatory_act/edit/{item?}', 'edit')->name('nomenclature.regulatory_act.edit');
        Route::match(['post', 'put'], '/nomenclature/regulatory_act/store/{item?}', 'store')->name('nomenclature.regulatory_act.store');
    });
});
