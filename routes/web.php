<?php

use App\Http\Controllers\{Auth\LoginController, CommonController};
// Admin
use App\Http\Controllers\Admin\{HomeController as AdminHomeController,
    ActivityLogController,
    PermissionsController,
    UsersController,
    RolesController};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

include 'eauth.php';

require_once('site.php');

Route::controller(\App\Http\Controllers\Templates::class)->group(function () {
    Route::get('/templates',                'index')->name('templates');
    Route::get('/templates/{slug}',                'show')->name('templates.view');
});

Route::controller(\App\Http\Controllers\Auth\ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password',                'showLinkRequestForm')->name('forgot_pass');
    Route::post('/forgot-password/send',                'sendResetLinkEmail')->name('forgot_pass.password.send');
    Route::post('/forgot-password/update',                'confirmPassword')->name('forgot_pass.password.update');
});

// Common routes
Route::group(['middleware' => ['auth']], function() {
    Route::match(['get', 'post'],'/logout', [LoginController::class, 'logout'])->name('front.logout');

    Route::get('/locale', function (Request $request) {
        if ($request->has('locale')) {
            session(['locale' => $request->offsetGet('locale')]);
            app()->setLocale($request->offsetGet('locale'));
        }
        return back();
    })->name('change-locale');

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
