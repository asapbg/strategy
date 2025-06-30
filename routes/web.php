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

Route::get('/test-123123', function () {
    $eAuth = new EAuthentication();
    $saml = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHNhbWwycDpSZXNwb25zZSBEZXN0aW5hdGlvbj0iaHR0cHM6Ly9zdHJhdGVneS5hc2FwYmcuY29tL2VhdXRoL2xvZ2luLWNhbGxiYWNrIiBJRD0iX2U1ZGFjMzMxLTg1YzUtNDhiMy04ZTIzLWI5YjQ5YWVhZjczNyIgSW5SZXNwb25zZVRvPSJBUlExYTFkZDZhLTM1OTItNDdhYi1hZTI1LTVjMzJkZmQ5MTcyMCIgSXNzdWVJbnN0YW50PSIyMDI1LTA2LTMwVDEyOjExOjExLjc1NloiIFZlcnNpb249IjIuMCIgeG1sbnM6c2FtbDJwPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6cHJvdG9jb2wiPjxzYW1sMjpJc3N1ZXIgRm9ybWF0PSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6bmFtZWlkLWZvcm1hdDplbnRpdHkiIHhtbG5zOnNhbWwyPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YXNzZXJ0aW9uIj5odHRwczovL2VhdXRoLmVnb3YuYmc8L3NhbWwyOklzc3Vlcj48c2FtbDJwOlN0YXR1cz48c2FtbDJwOlN0YXR1c0NvZGUgVmFsdWU9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDpzdGF0dXM6U3VjY2VzcyIvPjwvc2FtbDJwOlN0YXR1cz48c2FtbDI6RW5jcnlwdGVkQXNzZXJ0aW9uIHhtbG5zOnNhbWwyPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YXNzZXJ0aW9uIj48eGVuYzpFbmNyeXB0ZWREYXRhIElkPSJfMjBjZDkwMWFjZTE1OTc5MTJlMTNmYjcwMjQ2YmIzYjAiIFR5cGU9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvMDQveG1sZW5jI0VsZW1lbnQiIHhtbG5zOnhlbmM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvMDQveG1sZW5jIyI';

    $userInfo = $eAuth->userData($saml);

    $certInfo = DigitalSignature::getContents($userInfo['certificate']);
    dd($userInfo, $certInfo);
});

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
