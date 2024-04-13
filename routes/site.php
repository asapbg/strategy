<?php

use App\Http\Controllers\DevelopNewActionPlan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpactAssessmentController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\OpenGovernmentPartnership;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('site.home');
        Route::get('/get-consultations', 'getConsultations')->name('get-consultations');
        Route::get('/get-initiatives', 'getInitiatives')->name('get-initiatives');
        Route::get('/search', 'search')->name('search');
        Route::get('/search-section', 'searchSection')->name('search.section');
        Route::get('/contacts/{section?}', 'contacts')->name('contacts');
        Route::post('/contacts', 'sendMessage')->name('contacts.message');
        Route::get('/other', 'otherLinks')->name('other_links');
    });

    Route::controller(\App\Http\Controllers\PublicProfilesController::class)->group(function () {
        Route::get('/user-profile/public-consultations/{item}', 'userPublicConsultation')->name('user.profile.pc');
        Route::get('/user-profile/legislative-initiatives/{item}', 'userLegislativeInitiatives')->name('user.profile.li');
        Route::get('/institution-profile/information/{item}', 'institutionProfile')->name('institution.profile');
        Route::get('/institution-profile/public-consultations/{item}', 'institutionPublicConsultations')->name('institution.profile.pc');
//    Route::get('/institution-profile/strategic-documents/{item}', 'institutionStrategicDocuments')->name('institution.profile.sd');
        Route::get('/institution-profile/legislative-initiatives/{item}', 'institutionLegislativeInitiatives')->name('institution.profile.li');
        Route::get('/institution-profile/pris/{item}', 'institutionPris')->name('institution.profile.pris');
        Route::get('/institution-profile/moderators/{item}', 'institutionModerators')->name('institution.profile.moderators');
    });

    Route::controller(\App\Http\Controllers\PageController::class)->group(function () {
        Route::get('/page/{slug}', 'show')->name('page.view');
    });

    Route::controller(\App\Http\Controllers\AdvisoryBoardController::class)->prefix('advisory-boards')->group(function() {
        Route::get('', 'index')->name('advisory-boards.index');
        Route::get('/news', 'news')->name('advisory-boards.news');
        Route::get('/news/{item}/details', 'newsDetails')->name('advisory-boards.news.details');
        Route::get('/information', 'info')->name('advisory-boards.info');
        Route::get('/documents', 'documents')->name('advisory-boards.documents');
        Route::get('/contacts/{item?}', 'contacts')->name('advisory-boards.contacts');
        Route::get('{item}/view', 'show')->name('advisory-boards.view');
        Route::get('{item}/view/news', 'itemNews')->name('advisory-boards.view.news');
        Route::get('{item}/view/news/{news}/details', 'itemNewsDetails')->name('advisory-boards.view.news.details');
        Route::get('{item}/view/{section}/section/', 'showSection')->name('advisory-boards.view.section');
        Route::get('{item}/view/archive/meetings/', 'archiveMeetings')->name('advisory-boards.view.archive.meetings');
        Route::get('{item}/view/archive/work_programs/', 'archiveWorkPrograms')->name('advisory-boards.view.archive.work_programs');
        Route::get('reports', 'reports')->name('advisory-boards.reports');

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
        Route::get('poll/{id}/statistic', 'statistic')->name('poll.statistic');
        Route::post('poll', 'store')->name('poll.store');
        Route::get('polls/export/{id}/{format?}', 'export')->name('polls.export');
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

    Route::controller(LibraryController::class)->group(function () {
        Route::get('/library', function () {
            return redirect(route('library.publications'));
        });
        Route::get('/library/publications/{type?}', 'publications')->name('library.publications');
        Route::get('/library/news', 'news')->name('library.news');
        Route::get('/library/{type}/{id}/details', 'details')->name('library.details');
    });

    Route::controller(\App\Http\Controllers\PublicConsultationController::class)->group(function () {
        Route::get('/public-consultations', 'index')->name('public_consultation.index');
        Route::get('/public-consultations/{id}', 'show')->name('public_consultation.view');
        Route::post('/public-consultations/add-comment', 'addComment')->name('public_consultation.comment.add');
        Route::get('/public-consultations/reports/simple', 'simpleReport')->name('public_consultation.report.simple');
        Route::get('/public-consultations/reports/field-of-actions', 'fieldОfАctionsReport')->name('public_consultation.report.field_of_actions');
        Route::get('/public-consultations/reports/field-of-actions-institution', 'fieldОfАctionsInstitutionReport')->name('public_consultation.report.field_of_actions.institution');
        Route::get('/public-consultations/reports/institutions', 'institutionsReport')->name('public_consultation.report.institutions');
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
        Route::get('/strategy-documents/{id}', 'show')->name('strategy-document.view')->where('id', '([1-9]+[0-9]*)');
        Route::get('/strategy-documents', 'index')->name('strategy-documents.index');
        Route::get('/strategy-documents/tree', 'tree')->name('strategy-documents.tree');
        Route::get('/strategy-documents/reports', 'reports')->name('strategy-documents.reports');
//    Route::get('/strategy-document/list/{search?}', 'listStrategicDocuments')->name('strategy-document.list');

        Route::get('/strategy-document/download-file/{id}', 'downloadDocFile')->name('strategy-document.download-file');
        Route::get('/strategy-document/file-preview-modal/{id}', 'previewModalFile')->name('strategy-document.preview.file_modal');
//    Route::get('/strategy-document-institution/{documentLevelIds?}', 'getInstitutions')->name('strategy-document.institutions');
//    Route::get('/strategy-document/load-pris-acts', 'loadPrisOptions')->name('strategy-document.load-pris-acts');
        Route::get('/strategy-documents/information', 'info')->name('strategy-document.info');
        Route::get('/strategy-documents/documents', 'documents')->name('strategy-document.documents');
        Route::get('/strategy-documents/contacts/{item?}', 'contacts')->name('strategy-document.contacts');
    });

    Route::controller(\App\Http\Controllers\ImpactAssessmentCalculatorsController::class)->group(function () {
        Route::get('/impact_assessments/tools', 'tools')->name('impact_assessment.tools');
        Route::match(['get', 'post'],'/impact_assessments/tools/{calc}', 'calc')->name('impact_assessment.tools.calc');
        Route::get('/impact_assessments/tools/calc/get-blade/{type}', 'templates')->name('impact_assessment.tools.templates');

    });

    Route::controller(ImpactAssessmentController::class)->group(function () {
        Route::get('/impact_assessments', 'info')->name('impact_assessment.index');
//    Route::get('/impact_assessments/library', 'library')->name('impact_assessment.library');
        Route::get('/impact_assessments/library/{slug}', 'libraryView')->name('impact_assessment.library.view');
        Route::get('/impact_assessments/executors', 'executors')->name('impact_assessment.executors');
        Route::get('/impact_assessments/forms', 'forms')->name('impact_assessment.forms');
        Route::get('/impact_assessments/{form}', 'form')->name('impact_assessment.form');
        Route::post('/impact_assessments/{form}', 'store')->name('impact_assessment.store');
        Route::get('/impact_assessments/{form}/pdf/{inputId}', 'pdf')->name('impact_assessment.pdf');
        Route::get('/impact_assessments/{form}/show/{inputId}', 'show')->name('impact_assessment.show');
    });

    Route::controller(\App\Http\Controllers\CommonController::class)->group(function () {
        Route::get('/download/{file}', 'downloadFile')->name('download.file');
        Route::get('/select2-ajax/{type}', 'getSelect2Ajax')->name('select2.ajax');
    });

    Route::controller(ProfileController::class)->middleware('auth')->group(function () {
        Route::get('/profile/{tab?}', 'index')->name('profile');
        Route::post('/profile/change-request', 'store')->name('profile.store');
        Route::get('/profile/change-request/withdrew', 'withdrew')->name('profile.withdrew');
        Route::post('/profile/change-password', 'changePassword')->name('profile.store.password');
        Route::get('/profile/{id}/{status}', 'subscriptionState')->name('profile.subscribe.set');
    });

    Route::controller(\App\Http\Controllers\LegislativeInitiativeController::class)->group(function() {
        Route::get('/legislative-initiatives', 'index')->name('legislative_initiatives.index');
        Route::get('/legislative-initiatives/create', 'create')->name('legislative_initiatives.create');
        Route::post('/legislative-initiatives/store', 'store')->name('legislative_initiatives.store');
        Route::get('/legislative-initiatives/{item}/view', 'show')->name('legislative_initiatives.view');
        Route::get('/legislative-initiatives/{item}/edit', 'edit')->name('legislative_initiatives.edit');
        Route::post('/legislative-initiatives/{item}/update', 'update')->name('legislative_initiatives.update');
        Route::post('/legislative-initiatives/{item}/delete', 'destroy')->name('legislative_initiatives.delete');
        Route::post('/legislative-initiatives/{item}/close', 'close')->name('legislative_initiatives.close');
        Route::get('/legislative-initiatives/information', 'info')->name('legislative_initiatives.info');
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

//OGP
    Route::controller(OpenGovernmentPartnership::class)->group(function () {
        Route::get('ogp', 'index')->name('ogp.list');
        Route::get('ogp/information', 'info')->name('ogp.info');
        Route::get('ogp/contacts', 'contacts')->name('ogp.contacts');
        Route::get('ogp/library/{slug}', 'libraryView')->name('ogp.library.view');
        Route::get('ogp/news', 'news')->name('ogp.news');
        Route::get('ogp/events', 'events')->name('ogp.events');
        Route::get('ogp/news/{item}/details', 'newsDetails')->name('ogp.news.details');
        Route::get('ogp/forum', 'forum')->name('ogp.forum');
    });

    Route::controller(DevelopNewActionPlan::class)->group(function () {
        Route::get('ogp/develop-a-new-action-plans', 'index')->name('ogp.develop_new_action_plans');
//    Route::get('ogp/develop-a-new-action-plans/{id}', 'show')->name('ogp.develop_new_action_plans.show')->whereNumber('id');
        Route::get('ogp/develop-a-new-action-plan/{plan}/view/{planArea}', 'area')->name('ogp.develop_new_action_plans.area');
        Route::get('ogp/develop-a-new-action-plan/{plan}/view/{planArea}/{offer}', 'offer')->name('ogp.develop_new_action_plans.area.offer');
        Route::post('ogp/develop-a-new-action-plans/store-offer/{id}', 'store')->name('ogp.develop_new_action_plans.store_offer')->whereNumber('otg_area_id');
//    Route::get('ogp/develop-a-new-action-plans/offer/edit/{offer}', 'editOffer')->name('ogp.develop_new_action_plans.edit_offer');
        Route::post('ogp/develop-a-new-action-plans/add-comment/{offer}', 'storeComment')->name('ogp.develop_new_action_plans.add_comment');
//    Route::post('ogp/develop-a-new-action-plans/delete-comment/{comment}', 'deleteComment')->name('ogp.develop_new_action_plans.delete_comment');
        Route::post('ogp/develop-a-new-action-plans/offer-vote/{id}/{like}', 'voteOffer')->name('ogp.develop_new_action_plans.vote');
    });

    Route::controller(\App\Http\Controllers\NationalActionPlans::class)->group(function () {
        Route::get('ogp/national-action-plans', 'index')->name('ogp.national_action_plans');
        Route::get('ogp/national-action-plans/{id}', 'show')->name('ogp.national_action_plans.show')->whereNumber('id');
        Route::get('ogp/national-action-plans/old/{id}', 'showOld')->name('ogp.national_action_plans.show.old')->whereNumber('id');
        Route::get('ogp/national-action-plans/old/file/download', function (){
            return Storage::disk('public_uploads')->download(request()->get('file'));
        })->name('ogp.national_action_plans.old.file');
        Route::get('ogp/national-action-plans/{id}/export', 'export')->name('ogp.national_action_plans.export')->whereNumber('id');
        Route::get('ogp/national-action-plans/{id}/develop-plan', 'developPlan')->name('ogp.national_action_plans.develop_plan');
        Route::get('ogp/national-action-plans/{id}/develop-plan/area/view/{planArea}', 'areaDevelopPlan')->name('ogp.national_action_plans.develop_plan.area');
        //    Route::get('ogp/national-action-plans/{id}/export-old', 'exportOld')->name('ogp.national_action_plans.export.old')->whereNumber('id');
//    Route::get('ogp/national-action-plans/{plan}/view/{planArea}', 'area')->name('ogp.national_action_plans.area');
    });

