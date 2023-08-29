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
    Route::post('/templates/{slug}',                'show')->name('templates.view');
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
    });

    Route::fallback(function(){
        \Log::channel('info')->info('Path not found; User ip: '.request()->ip().'; Url: '.request()->getPathInfo());
        return response()->view('errors.404', [], 404);
    });
});

// Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['roles:all','auth']], function() {

    Route::controller(UsersController::class)->group(function () {
        Route::name('users.profile.edit')->get('/users/profile/{user}/edit', 'editProfile');
        Route::name('users.profile.update')->post('/users/profile/{user}/update', 'updateProfile');
    });

    Route::middleware(['roles:admin'])->group(function () {

        Route::controller(UsersController::class)->group(function () {
            Route::get('/users',                'index')->name('users');
            Route::get('/users/create',         'create')->name('users.create');
            Route::post('/users/store',         'store')->name('users.store');
            Route::get('/users/{user}/edit',    'edit')->name('users.edit');
            Route::post('/users/{user}/update',  'update')->name('users.update');
            Route::get('/users/{user}/delete',  'destroy')->name('users.delete');
            Route::get('/users/export',         'export')->name('users.export');
        });

        Route::controller(RolesController::class)->group(function () {
            Route::get('/roles',                'index')->name('roles');
            Route::get('/roles/create',         'create')->name('roles.create');
            Route::post('/roles/store',         'store')->name('roles.store');
            Route::get('/roles/{role}/edit',    'edit')->name('roles.edit');
            Route::get('/roles/{role}/update',  'update')->name('roles.update');
            Route::get('/roles/{role}/delete',  'destroy')->name('roles.delete');
        });

        Route::controller(PermissionsController::class)->group(function () {
            Route::get('/permissions',                      'index')->name('permissions');
            Route::get('/permissions/create',               'create')->name('permissions.create');
            Route::post('/permissions/store',               'store')->name('permissions.store');
            Route::get('/permissions/{permission}/edit',    'edit')->name('permissions.edit');
            Route::get('/permissions/{permission}/update',  'update')->name('permissions.update');
            Route::get('/permissions/{permission}/delete',  'destroy')->name('permissions.delete');
            Route::post('/permissions/roles',               'rolesPermissions')->name('permissions.roles');
        });

        Route::controller(ActivityLogController::class)->group(function () {
            Route::get('/activity-logs',                 'index')->name('activity-logs');
            Route::get('/activity-logs/{activity}/show', 'show')->name('activity-logs.show');
        });

    });

});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

require_once('admin.php');
