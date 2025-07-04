<?php

use App\Library\DigitalSignature;
use App\Library\EAuthentication;
use App\Http\Controllers\{Auth\LoginController, CommonController};
// Admin
use App\Http\Controllers\Admin\{HomeController as AdminHomeController,
    ActivityLogController,
    PermissionsController,
    UsersController,
    RolesController};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//To redirect if no language in url
Route::get('/', function(){
    return Redirect::to(app()->getLocale());
});

Route::feeds();

Route::get('/locale', \App\Http\Controllers\LocaleController::class)->name('change-locale');

Route::get('/download/{file}', [\App\Http\Controllers\CommonController::class, 'downloadFile'])->whereNumber('file')->name('download.file');
Route::get('/strategy-document/download-file/{id}', [\App\Http\Controllers\StrategicDocumentsController::class, 'downloadDocFile'])->name('strategy-document.download-file');

Route::prefix(app()->getLocale())->group(function (){
    Auth::routes(['verify' => true]);
    require_once('site.php');

    Route::controller(\App\Http\Controllers\Auth\ForgotPasswordController::class)->group(function () {
        Route::get('/forgot-password',                'showLinkRequestForm')->name('forgot_pass');
        Route::post('/forgot-password/send',                'sendResetLinkEmail')->name('forgot_pass.password.send');
        Route::post('/forgot-password/update',                'confirmPassword')->name('forgot_pass.password.update');
    });
});

include 'eauth.php';
include 'api.php';

Route::get('/sitemap.xml', [\App\Http\Controllers\HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/sitemap/base', [\App\Http\Controllers\HomeController::class, 'sitemapBase'])->name('sitemap.base');
Route::get('/sitemap/sub/{page}', [\App\Http\Controllers\HomeController::class, 'sitemapSub'])->name('sitemap.sub');

Route::get('/admin/login', function (){
    return redirect(route('login'));
});

Route::get('/get-institutions', [CommonController::class, 'modalInstitutions'])->name('modal.institutions');
Route::get('/file-preview-modal/{id}', [CommonController::class, 'previewModalFile'])->name('modal.file_preview');
Route::get('/file-preview-modal-static-page', [CommonController::class, 'previewModalFileStaticPage'])->name('modal.file_preview_static_page');
Route::get('/select2-ajax/{type}', [CommonController::class, 'getSelect2Ajax'])->name('select2.ajax');

Route::controller(\App\Http\Controllers\Templates::class)->group(function () {
    Route::get('/templates',                'index')->name('templates');
    Route::get('/templates/{slug}',                'show')->name('templates.view');
});


// Common routes
Route::group(['middleware' => ['auth']], function() {
    Route::match(['get', 'post'],'/logout', [LoginController::class, 'logout'])->name('front.logout');

    Route::controller(UsersController::class)->group(function () {
        Route::get('/subscribe-form', 'subscribeForm')->name('subscribe.form');
        Route::get('/subscribe', 'subscribe')->name('subscribe');
    });

    Route::controller(CommonController::class)->group(function () {
        Route::get('/toggle-boolean', 'toggleBoolean')->name('toggle-boolean');
        Route::get('/toggle-permissions', 'togglePermissions')->name('toggle-permissions');
        //download public page file
        Route::get('/download/page/{file}', 'downloadPageFile')->name('download.page.file');
    });

    Route::fallback(function(){
        \Log::channel('info')->info('Path not found; User ip: '.request()->ip().'; Url: '.request()->getPathInfo());
        return response()->view('errors.404', [], 404);
    });
});


Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

require_once('admin.php');
