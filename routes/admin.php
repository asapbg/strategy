<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\Consultations\LegislativeProgramController;
use App\Http\Controllers\Admin\Consultations\OperationalProgramController;
use App\Http\Controllers\Admin\Consultations\PublicConsultationController;
use App\Http\Controllers\Admin\ImpactAssessmentController;
use App\Http\Controllers\Admin\ImpactPageController;
use App\Http\Controllers\Admin\LegislativeInitiativeController;
use App\Http\Controllers\Admin\LinkController;
//use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\Nomenclature\NewsCategoryController;
use App\Http\Controllers\Admin\Nomenclature\RegulatoryActController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
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
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PCSubjectController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'administration']], function() {
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

    Route::controller(\App\Http\Controllers\CommonController::class)->group(function () {
        Route::get('/download/{file}', 'downloadFile')->name('download.file');
        Route::get('/file-preview-modal/{id}', 'previewModalFile')->name('preview.file.modal');
        Route::get('/delete/{file}/{disk?}', 'deleteFile')->name('delete.file');
        Route::post('/upload-file/{object_id}/{object_type}','uploadFile')->name('upload.file');
        Route::post('/upload-file-languages/{object_id}/{object_type}','uploadFileLanguages')->name('upload.file.languages');
        Route::get('/select2-ajax/{type}','getSelect2Ajax')->name('select2.ajax');
    });

    // Publications
    Route::controller(PublicationController::class)->group(function () {
        Route::get('/publications', 'index')->name('publications.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/publications/edit/{item?}', 'edit')->name('publications.edit');
        Route::match(['post', 'put'], '/publications/store/{item?}', 'store')->name('publications.store');
    });

    // Consultations
    Route::controller(LegislativeProgramController::class)->group(function () {
        Route::get('/consultations/legislative-programs', 'index')->name('consultations.legislative_programs.index')->middleware('can:viewAny,App\Models\Consultations\LegislativeProgram');
        Route::get('/consultations/legislative-programs/edit/{item?}', 'edit')->name('consultations.legislative_programs.edit');
        Route::get('/consultations/legislative-programs/view/{item}', 'show')->name('consultations.legislative_programs.view');
        Route::get('/consultations/legislative-programs/remove-row/{item}/{row}', 'removeRow')->name('consultations.legislative_programs.remove_row');
        Route::match(['post', 'put'], '/consultations/legislative-programs/store', 'store')->name('consultations.legislative_programs.store');
        Route::get('/consultations/legislative-programs/publish/{item}', 'publish')->name('consultations.legislative_programs.publish');
        Route::get('/consultations/legislative-programs/unpublish/{item}', 'unPublish')->name('consultations.legislative_programs.unpublish');
        Route::get('/consultations/legislative-programs/{program}/remove-file/{file}', 'deleteFile')->name('consultations.legislative_programs.delete.file');
    });
    Route::controller(OperationalProgramController::class)->group(function () {
        Route::get('/consultations/operational-programs', 'index')->name('consultations.operational_programs.index')->middleware('can:viewAny,App\Models\Consultations\OperationalProgram');
        Route::get('/consultations/operational-programs/edit/{item?}', 'edit')->name('consultations.operational_programs.edit');
        Route::get('/consultations/operational-programs/view/{item}', 'show')->name('consultations.operational_programs.view');
        Route::get('/consultations/operational-programs/remove-row/{item}/{row}', 'removeRow')->name('consultations.operational_programs.remove_row');
        Route::match(['post', 'put'], '/consultations/operational-programs/store', 'store')->name('consultations.operational_programs.store');
        Route::get('/consultations/operational-programs/publish/{item}', 'publish')->name('consultations.operational_programs.publish');
        Route::get('/consultations/operational-programs/unpublish/{item}', 'unPublish')->name('consultations.operational_programs.unpublish');
        Route::get('/consultations/operational-programs/{program}/remove-file/{file}', 'deleteFile')->name('consultations.operational_programs.delete.file');
    });
    Route::controller(PublicConsultationController::class)->group(function () {
        Route::get('/consultations/public-consultations', 'index')->name('consultations.public_consultations.index')->middleware('can:viewAny,App\Models\Consultations\PublicConsultation');
        Route::get('/consultations/public-consultations/edit/{item?}', 'edit')->name('consultations.public_consultations.edit');
        Route::match(['post', 'put'], '/consultations/public-consultations/store/{item?}', 'store')->name('consultations.public_consultations.store');
        Route::post('/consultations/public-consultations/store-kd', 'storeKd')->name('consultations.public_consultations.store.kd');
        Route::post('/consultations/public-consultations/store-doc', 'storeDocs')->name('consultations.public_consultations.store.documents');
        Route::post('/consultations/public-consultations/add-contact', 'addContact')->name('consultations.public_consultations.add.contact');
        Route::post('/consultations/public-consultations/remove-contact', 'removeContact')->name('consultations.public_consultations.remove.contact');
        Route::post('/consultations/public-consultations/update-contact', 'updateContacts')->name('consultations.public_consultations.update.contacts');
        Route::post('/consultations/public-consultations/add-poll', 'attachPoll')->name('consultations.public_consultations.poll.attach');
    });

    // Strategic Documents
    Route::controller(StrategicDocumentsController::class)->group(function () {
        Route::get('/strategic-documents', 'index')->name('strategic_documents.index')->middleware('can:viewAny,App\Models\StrategicDocument');
        Route::get('/strategic-documents/edit/{id?}', 'edit')->name('strategic_documents.edit');
        Route::match(['post', 'put'], '/strategic-documents/store', 'store')->name('strategic_documents.store');
        Route::post( '/strategic-documents/upload-file', 'uploadDcoFile')->name('strategic_documents.file.upload');
        Route::put( '/strategic-documents/update-file/{id}', 'updateDcoFile')->name('strategic_documents.file.update');
        Route::get( '/strategic-documents/download-file/{file}', 'downloadDocFile')->name('strategic_documents.file.download');
        Route::post( '/strategic-documents/delete-file/{file}', 'deleteDocFile')->name('strategic_documents.file.delete');
        Route::get('/strategic-documents/delete/{id}', 'delete')->name('strategic_documents.delete');
        Route::post('strategic-documents/save-tree', 'saveFileTree')->name('strategic_documents.save.file.tree');
    });

    // Static pages
    Route::controller(PageController::class)->group(function () {
        Route::get('/page', 'index')->name('page')->middleware('can:viewAny,App\Models\Page');
        Route::get('/page/edit/{item?}', 'edit')->name('page.edit');
        Route::match(['post', 'put'], '/page/store', 'store')->name('page.store');
    });

    // Settings
    Route::controller(\App\Http\Controllers\Admin\SettingsController::class)->group(function () {
        Route::get('/settings/{section?}',                'index')->name('settings')->middleware('can:viewAny,App\Models\Settings');
        Route::match(['put'], '/settings',         'store')->name('settings.store');
    });

    //PRIS
    Route::controller(\App\Http\Controllers\Admin\PrisController::class)->group(function () {
        Route::get('/pris',                'index')->name('pris')->middleware('can:viewAny,App\Models\Pris');
        Route::get( '/pris/edit/{item}',         'edit')->name('pris.edit');
        Route::post( '/pris/connect-documents',         'connectDocuments')->name('pris.connect');
        Route::post( '/pris/disconnect-documents',         'disconnectDocuments')->name('pris.disconnect');
        Route::match(['put', 'post'],'/pris/edit',         'store')->name('pris.store');
    });

    //Dynamic Structures
    Route::controller(\App\Http\Controllers\Admin\DynamicStructureController::class)->group(function () {
        Route::get('/dynamic-structures',                'index')->name('dynamic_structures')->middleware('can:viewAny,App\Models\DynamicStructure');
        Route::get( '/dynamic-structures/edit/{item}',         'edit')->name('dynamic_structures.edit');
        Route::post( '/dynamic-structures/add-column',         'addColumn')->name('dynamic_structures.add_column');
    });

    //Impact assessments
    Route::controller(\App\Http\Controllers\Admin\ImpactAssessmentController::class)->group(function () {
        Route::get('/impact-assessments', 'index')->name('impact_assessment.index');
    });

    //Profile
    Route::controller(UsersController::class)->group(function () {
        Route::name('users.profile.edit')->get('/users/profile/{user}/edit', 'editProfile');
        Route::name('users.profile.update')->post('/users/profile/{user}/update', 'updateProfile');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get('/users',                'index')->name('users')->middleware('can:viewAny,App\Models\User');
        Route::get('/users/create',         'create')->name('users.create');
        Route::post('/users/store',         'store')->name('users.store');
        Route::get('/users/{user}/edit',    'edit')->name('users.edit');
        Route::post('/users/{user}/update',  'update')->name('users.update');
        Route::get('/users/{user}/delete',  'destroy')->name('users.delete');
        Route::get('/users/export',         'export')->name('users.export');
    });

    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles',                'index')->name('roles')->middleware('can:viewAny,App\Models\CustomRole');
        Route::get('/roles/create',         'create')->name('roles.create');
        Route::post('/roles/store',         'store')->name('roles.store');
        Route::get('/roles/{role}/edit',    'edit')->name('roles.edit');
        Route::get('/roles/{role}/update',  'update')->name('roles.update');
        Route::get('/roles/{role}/delete',  'destroy')->name('roles.delete');
    });

    Route::controller(PermissionsController::class)->group(function () {
        Route::get('/permissions',                      'index')->name('permissions')->middleware('can:viewAny,App\Models\CustomRole');
        Route::get('/permissions/create',               'create')->name('permissions.create');
        Route::post('/permissions/store',               'store')->name('permissions.store');
        Route::get('/permissions/{permission}/edit',    'edit')->name('permissions.edit');
        Route::get('/permissions/{permission}/update',  'update')->name('permissions.update');
        Route::get('/permissions/{permission}/delete',  'destroy')->name('permissions.delete');
        Route::post('/permissions/roles',               'rolesPermissions')->name('permissions.roles');
    });

    Route::controller(ActivityLogController::class)->group(function () {
        Route::get('/activity-logs',                 'index')->name('activity-logs')->middleware('can:viewAny,App\Models\CustomActivity');;
        Route::get('/activity-logs/{activity}/show', 'show')->name('activity-logs.show');
    });

//    Route::controller(StaticPageController::class)->group(function () {
//        Route::get('/static-pages', 'index')->name('static_pages.index')->middleware('can:viewAny,App\Models\Page');
//        Route::get('/static-pages/edit/{item?}', 'edit')->name('static_pages.edit');
//        Route::match(['post', 'put'], '/static-pages/store/{item?}', 'store')->name('static_pages.store');
//    });
//
//    Route::controller(ImpactPageController::class)->group(function () {
//        Route::get('/impact-pages', 'index')->name('impact_pages.index')->middleware('can:viewAny,App\Models\Page');
//        Route::get('/impact-pages/edit/{item?}', 'edit')->name('impact_pages.edit');
//        Route::match(['post', 'put'], '/impact-pages/store/{item?}', 'store')->name('impact_pages.store');
//    });

    // Polls
    Route::controller(PollController::class)->group(function () {
        Route::get('/polls', 'index')->name('polls.index')->middleware('can:viewAny,App\Models\Poll');
        Route::get('/polls/edit/{id}', 'edit')->name('polls.edit');
        Route::get('/polls/result/{item}', 'preview')->name('polls.preview');
        Route::match(['post', 'put'], '/polls/store', 'store')->name('polls.store');

        Route::post('/poll/question', 'createQuestion')->name('polls.question.create');
        Route::post('/poll/question/edit', 'editQuestion')->name('polls.question.edit');
        Route::get('/poll/question/delete/{id}', 'questionDelete')->where('id', '([1-9]+[0-9]*)')->name('polls.question.delete');
        Route::post('/poll/question/delete', 'questionConfirmDelete')->name('polls.question.delete.confirm');
    });

//    // News
//    Route::controller(NewsController::class)->group(function () {
//        Route::get('/news', 'index')->name('news.index')->middleware('can:viewAny,App\Models\Publication');
//        Route::get('/news/edit/{item?}', 'edit')->name('news.edit');
//        Route::match(['post', 'put'], '/news/store/{item?}', 'store')->name('news.store');
//    });



    Route::controller(InstitutionController::class)->group(function () {
        Route::get('/nomenclature/institutions', 'index')->name('strategic_documents.institutions.index')->middleware('can:viewAny,App\Models\Institution');
        Route::get('/nomenclature/institutions/edit/{item?}', 'edit')->name('strategic_documents.institutions.edit');
        Route::match(['post', 'put'], '/nomenclature/institutions/store/{item?}', 'store')->name('strategic_documents.institutions.store');
    });

    // Links
    Route::controller(LinkController::class)->group(function () {
        Route::get('/links', 'index')->name('links.index')->middleware('can:viewAny,App\Models\Publication');
        Route::get('/links/edit/{item?}', 'edit')->name('links.edit');
        Route::match(['post', 'put'], '/links/store/{item?}', 'store')->name('links.store');
    });

    // PC Subjects
    Route::controller(PCSubjectController::class)->group(function () {
        Route::get('/pc-subjects', 'index')->name('pc_subjects.index');
        Route::get('/pc_-subjects/edit/{item?}', 'edit')->name('pc_subjects.edit');
        Route::match(['post', 'put'], '/pc-subjects/store/{item?}', 'store')->name('pc_subjects.store');
    });

    // Legislative Initiatives
    Route::controller(LegislativeInitiativeController::class)->group(function () {
        Route::get('/legislative-initiatives', 'index')->name('legislative_initiatives.index')
            ->middleware('can:viewAny, App\Models\LegislativeInitiative');
        Route::get('/legislative-initiatives/create', 'create')->name('legislative_initiatives.create');
        Route::get('/legislative-initiatives/edit/{item?}', 'edit')->name('legislative_initiatives.edit');
        Route::bind('item', function ($id) {
            return \App\Models\LegislativeInitiative::withTrashed()->find($id);
        });
        Route::post('/legislative-initiatives/{item}/update', 'update')->name('legislative_initiatives.update');
        Route::delete('/legislative-initiatives/{item}/delete', 'destroy')->name('legislative_initiatives.delete');
        Route::put('/legislative-initiatives/{item}/restore', 'restore')->name('legislative_initiatives.restore');
    });

    // Comments
    Route::controller(\App\Http\Controllers\Admin\CommentsController::class)->group(function () {
        Route::get('/consultations/comment', 'index')->name('consultations.comments.index')
            ->middleware('can:viewAny, App\Models\Comments');
    });



    // Mock controllers
    Route::group([], function () {
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
        Route::get('/nomenclature/institution-level', 'index')->name('nomenclature.institution_level')->middleware('can:viewAny,App\Models\InstitutionLevel');
        Route::get('/nomenclature/institution-level/edit/{item?}', 'edit')->name('nomenclature.institution_level.edit');
        Route::match(['post', 'put'], '/nomenclature/institution-level/store/{item?}', 'store')->name('nomenclature.institution_level.store');
    });

    Route::controller(ConsultationLevelController::class)->group(function () {
        Route::get('/nomenclature/consultation-level', 'index')->name('nomenclature.consultation_level')->middleware('can:viewAny,App\Models\ConsultationLevel');
        Route::get('/nomenclature/consultation-level/edit/{item?}', 'edit')->name('nomenclature.consultation_level.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation-level/store/{item?}', 'store')->name('nomenclature.consultation_level.store');
    });

    Route::controller(ActTypeController::class)->group(function () {
        Route::get('/nomenclature/act-type', 'index')->name('nomenclature.act_type')->middleware('can:viewAny,App\Models\ActType');
        Route::get('/nomenclature/act-type/edit/{item?}', 'edit')->name('nomenclature.act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/act-type/store/{item?}', 'store')->name('nomenclature.act_type.store');
    });

    Route::controller(LegalActTypeController::class)->group(function () {
        Route::get('/nomenclature/legal-act-type', 'index')->name('nomenclature.legal_act_type')->middleware('can:viewAny,App\Models\LegalActType');
        Route::get('/nomenclature/legal-act-type/edit/{item?}', 'edit')->name('nomenclature.legal_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/legal-act-type/store/{item?}', 'store')->name('nomenclature.legal_act_type.store');
    });

    Route::controller(StrategicDocumentLevelController::class)->group(function () {
        Route::get('/nomenclature/strategic-document-level', 'index')->name('nomenclature.strategic_document_level')->middleware('can:viewAny,App\Models\StrategicDocumentLevel');
        Route::get('/nomenclature/strategic-document-level/edit/{item?}', 'edit')->name('nomenclature.strategic_document_level.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic-document-level/store/{item?}', 'store')->name('nomenclature.strategic_document_level.store');
    });

    Route::controller(StrategicDocumentTypeController::class)->group(function () {
        Route::get('/nomenclature/strategic-document-type', 'index')->name('nomenclature.strategic_document_type')->middleware('can:viewAny,App\Models\StrategicDocumentType');
        Route::get('/nomenclature/strategic-document-type/edit/{item?}', 'edit')->name('nomenclature.strategic_document_type.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic-document-type/store/{item?}', 'store')->name('nomenclature.strategic_document_type.store');
    });

    Route::controller(AuthorityAcceptingStrategicController::class)->group(function () {
        Route::get('/nomenclature/authority-accepting-strategic', 'index')->name('nomenclature.authority_accepting_strategic')->middleware('can:viewAny,App\Models\AuthorityAcceptingStrategic');
        Route::get('/nomenclature/authority-accepting-strategic/edit/{item?}', 'edit')->name('nomenclature.authority_accepting_strategic.edit');
        Route::match(['post', 'put'], '/nomenclature/authority-accepting-strategic/store/{item?}', 'store')->name('nomenclature.authority_accepting_strategic.store');
    });

    Route::controller(AuthorityAdvisoryBoardController::class)->group(function () {
        Route::get('/nomenclature/authority-advisory-board', 'index')->name('nomenclature.authority_advisory_board')->middleware('can:viewAny,App\Models\AuthorityAdvisoryBoard');
        Route::get('/nomenclature/authority-advisory-board/edit/{item?}', 'edit')->name('nomenclature.authority_advisory_board.edit');
        Route::match(['post', 'put'], '/nomenclature/authority-advisory-board/store/{item?}', 'store')->name('nomenclature.authority_advisory_board.store');
    });

    Route::controller(AdvisoryActTypeController::class)->group(function () {
        Route::get('/nomenclature/advisory-act-type', 'index')->name('nomenclature.advisory_act_type')->middleware('can:viewAny,App\Models\AdvisoryActType');
        Route::get('/nomenclature/advisory-act-type/edit/{item?}', 'edit')->name('nomenclature.advisory_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/advisory-act-type/store/{item?}', 'store')->name('nomenclature.advisory_act_type.store');
    });

    Route::controller(StrategicActTypeController::class)->group(function () {
        Route::get('/nomenclature/strategic-act-type', 'index')->name('nomenclature.strategic_act_type')->middleware('can:viewAny,App\Models\StrategicActType');
        Route::get('/nomenclature/strategic-act-type/edit/{item?}', 'edit')->name('nomenclature.strategic_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/strategic-act-type/store/{item?}', 'store')->name('nomenclature.strategic_act_type.store');
    });

    Route::controller(AdvisoryChairmanTypeController::class)->group(function () {
        Route::get('/nomenclature/advisory-chairman-type', 'index')->name('nomenclature.advisory_chairman_type')->middleware('can:viewAny,App\Models\AdvisoryChairmanType');
        Route::get('/nomenclature/advisory-chairman-type/edit/{item?}', 'edit')->name('nomenclature.advisory_chairman_type.edit');
        Route::match(['post', 'put'], '/nomenclature/advisory-chairman-type/store/{item?}', 'store')->name('nomenclature.advisory_chairman_type.store');
    });

    Route::controller(ConsultationDocumentTypeController::class)->group(function () {
        Route::get('/nomenclature/consultation-document-type', 'index')->name('nomenclature.consultation_document_type')->middleware('can:viewAny,App\Models\ConsultationDocumentType');
        Route::get('/nomenclature/consultation-document-type/edit/{item?}', 'edit')->name('nomenclature.consultation_document_type.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation-document-type/store/{item?}', 'store')->name('nomenclature.consultation_document_type.store');
    });

    Route::controller(ConsultationTypeController::class)->group(function () {
        Route::get('/nomenclature/consultation-type', 'index')->name('nomenclature.consultation_type')->middleware('can:viewAny,App\Models\ConsultationType');
        Route::get('/nomenclature/consultation-type/edit/{item?}', 'edit')->name('nomenclature.consultation_type.edit');
        Route::match(['post', 'put'], '/nomenclature/consultation-type/store/{item?}', 'store')->name('nomenclature.consultation_type.store');
    });

    Route::controller(ProgramProjectController::class)->group(function () {
        Route::get('/nomenclature/program-project', 'index')->name('nomenclature.program_project')->middleware('can:viewAny,App\Models\ProgramProject');
        Route::get('/nomenclature/program-project/edit/{item?}', 'edit')->name('nomenclature.program_project.edit');
        Route::match(['post', 'put'], '/nomenclature/program-project/store/{item?}', 'store')->name('nomenclature.program_project.store');
    });

    Route::controller(LinkCategoryController::class)->group(function () {
        Route::get('/nomenclature/link-category', 'index')->name('nomenclature.link_category')->middleware('can:viewAny,App\Models\LinkCategory');
        Route::get('/nomenclature/link-category/edit/{item?}', 'edit')->name('nomenclature.link_category.edit');
        Route::match(['post', 'put'], '/nomenclature/link-category/store/{item?}', 'store')->name('nomenclature.link_category.store');
    });

    Route::controller(PolicyAreaController::class)->group(function () {
        Route::get('/nomenclature/policy-area', 'index')->name('nomenclature.policy_area')->middleware('can:viewAny,App\Models\LinkCategory');
        Route::get('/nomenclature/policy-area/edit/{item?}', 'edit')->name('nomenclature.policy_area.edit');
        Route::match(['post', 'put'], '/nomenclature/policy-area/store/{item?}', 'store')->name('nomenclature.policy_area.store');
    });

    Route::controller(PublicationCategoryController::class)->group(function () {
        Route::get('/nomenclature/publication-category', 'index')->name('nomenclature.publication_category')->middleware('can:viewAny,App\Models\PublicationCategory');
        Route::get('/nomenclature/publication-category/edit/{item?}', 'edit')->name('nomenclature.publication_category.edit');
        Route::match(['post', 'put'], '/nomenclature/publication-category/store/{item?}', 'store')->name('nomenclature.publication_category.store');
    });

//    Route::controller(NewsCategoryController::class)->group(function () {
//        Route::get('/nomenclature/news_category', 'index')->name('nomenclature.news_category')->middleware('can:viewAny,App\Models\NewsCategory');
//        Route::get('/nomenclature/news_category/edit/{item?}', 'edit')->name('nomenclature.news_category.edit');
//        Route::match(['post', 'put'], '/nomenclature/news_category/store/{item?}', 'store')->name('nomenclature.news_category.store');
//    });

    Route::controller(RegulatoryActTypeController::class)->group(function () {
        Route::get('/nomenclature/regulatory-act-type', 'index')->name('nomenclature.regulatory_act_type')->middleware('can:viewAny,App\Models\RegulatoryActType');
        Route::get('/nomenclature/regulatory-act-type/edit/{item?}', 'edit')->name('nomenclature.regulatory_act_type.edit');
        Route::match(['post', 'put'], '/nomenclature/regulatory-act-type/store/{item?}', 'store')->name('nomenclature.regulatory_act_type.store');
    });

    Route::controller(RegulatoryActController::class)->group(function () {
        Route::get('/nomenclature/regulatory-act', 'index')->name('nomenclature.regulatory_act')->middleware('can:viewAny,App\Models\RegulatoryAct');
        Route::get('/nomenclature/regulatory-act/edit/{item?}', 'edit')->name('nomenclature.regulatory_act.edit');
        Route::match(['post', 'put'], '/nomenclature/regulatory-act/store/{item?}', 'store')->name('nomenclature.regulatory_act.store');
    });

    Route::controller(\App\Http\Controllers\Admin\Nomenclature\TagController::class)->group(function () {
        Route::get('/nomenclature/tag', 'index')->name('nomenclature.tag')->middleware('can:viewAny,App\Models\Tag');
        Route::get('/nomenclature/tag/edit/{item?}', 'edit')->name('nomenclature.tag.edit');
        Route::match(['post', 'put'], '/nomenclature/tag/store/{item?}', 'store')->name('nomenclature.tag.store');
    });

    Route::controller(\App\Http\Controllers\Admin\Nomenclature\FieldOfActionController::class)->group(function () {
        Route::get('/nomenclature/field-of-actions',                    'index')->name('nomenclature.field_of_actions.index');
        Route::get('/nomenclature/field-of-actions/create',             'create')->name('nomenclature.field-of-actions.create');
        Route::post('/nomenclature/field-of-actions/store',             'store')->name('nomenclature.field_of_actions.store');
        Route::get('/nomenclature/field-of-actions/{action}/edit',      'edit')->name('nomenclature.field_of_actions.edit');
        Route::post('/nomenclatures/field-of-actions/{action}/update',  'update')->name('nomenclatures.field_of_actions.update');
        Route::post('/nomenclatures/field-of-actions/{action}/delete',  'destroy')->name('nomenclatures.field_of_actions.delete');
    });

    Route::controller(\App\Http\Controllers\Admin\ReportController::class)->group(function() {
        Route::get('/reports', 'index')->name('reports.index');
        Route::get('/reports/create', 'create')->name('reports.create');
        Route::post('/reports/store', 'store')->name('reports.store');
    });
});
