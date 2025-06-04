<?php

use App\Library\DigitalSignature;
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

Route::get('/test-cert', function(Request $request){
    $certificate = '-----BEGIN%20CERTIFICATE-----%0AMIIHNjCCBR6gAwIBAgIIC87BoPsF57wwDQYJKoZIhvcNAQELBQAwgYAxJDAiBgNV%0ABAMMG1N0YW1wSVQgR2xvYmFsIFF1YWxpZmllZCBDQTEYMBYGA1UEYQwPTlRSQkct%0AODMxNjQxNzkxMSEwHwYDVQQKDBhJbmZvcm1hdGlvbiBTZXJ2aWNlcyBKU0MxDjAM%0ABgNVBAcMBVNvZmlhMQswCQYDVQQGEwJCRzAeFw0yMjA2MDkwNzUzMjFaFw0yNTA2%0AMDgwNzUzMjFaMIHdMSYwJAYJKoZIhvcNAQkBFhdpcy5pdmFub3ZAZ292ZXJubWVu%0AdC5iZzEdMBsGA1UEAwwUSXNrcmVuIFBhdmxvdiBJdmFub3YxGTAXBgNVBAUTEFBO%0AT0JHLTg0MDYyNzU2MDAxDzANBgNVBCoMBklza3JlbjEPMA0GA1UEBAwGSXZhbm92%0AMRgwFgYDVQRhDA9OVFJCRy0wMDA2OTUwMjUxHTAbBgNVBAoMFENvdW5jaWwgb2Yg%0ATWluaXN0ZXJzMREwDwYDVQQHDAhTb2ZpYSAgLTELMAkGA1UEBhMCQkcwggEiMA0G%0ACSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDRXriT%2BjeVfj%2FRIKos45%2FREYcaCh%2BS%0AWc%2FxZJW%2FD4fxBmJ76iEkMg4E4Xa%2F57pYpG99hQ%2FYTQwVMYNSHXSJnIJqpFfUnLq7%0AVj71%2BJ8AZkearxlycVjySV%2BZ%2BSaMgVX09qTMDZWSsaiRT10ojv%2FZX7sCWUDGELZW%0AJ6Xep%2BF%2BapwOhNbAHsQ8OHjfJ372p%2FaexQK6m7QumgQzT3ervkQbjxiCZNxVVuyB%0AccU6yK%2F2gl23XzWLzixmDxTKnjBjmzwLHuP0WdqGRoAo4%2BlEH8M%2B0nUL7wN1NcCF%0Aq3gzcY2jvt14Ucy42uBJ6ykYfm%2BAHVOydSdX%2FatamN7cMIJ2vllXs%2FiDAgMBAAGj%0AggJTMIICTzCBgAYIKwYBBQUHAQEEdDByMEoGCCsGAQUFBzAChj5odHRwOi8vd3d3%0ALnN0YW1waXQub3JnL3JlcG9zaXRvcnkvc3RhbXBpdF9nbG9iYWxfcXVhbGlmaWVk%0ALmNydDAkBggrBgEFBQcwAYYYaHR0cDovL29jc3Auc3RhbXBpdC5vcmcvMB0GA1Ud%0ADgQWBBT1PTsAc7oQYCvLm7AikMUFb5RlfjAMBgNVHRMBAf8EAjAAMB8GA1UdIwQY%0AMBaAFMbcbpZBEdYfMv8RvbZRKuTpEUNQMIGIBggrBgEFBQcBAwR8MHowFQYIKwYB%0ABQUHCwIwCQYHBACL7EkBATAIBgYEAI5GAQEwCAYGBACORgEEMBMGBgQAjkYBBjAJ%0ABgcEAI5GAQYBMDgGBgQAjkYBBTAuMCwWJmh0dHBzOi8vd3d3LnN0YW1waXQub3Jn%0AL3Bkcy9wZHNfZW4ucGRmEwJlbjBgBgNVHSAEWTBXMAkGBwQAi%2BxAAQIwCAYGBACL%0AMAEBMEAGCysGAQQB2BoBAgECMDEwLwYIKwYBBQUHAgEWI2h0dHBzOi8vd3d3LnN0%0AYW1waXQub3JnL3JlcG9zaXRvcnkvMEgGA1UdHwRBMD8wPaA7oDmGN2h0dHA6Ly93%0Ad3cuc3RhbXBpdC5vcmcvY3JsL3N0YW1waXRfZ2xvYmFsX3F1YWxpZmllZC5jcmww%0ADgYDVR0PAQH%2FBAQDAgXgMDUGA1UdJQQuMCwGCCsGAQUFBwMCBggrBgEFBQcDBAYK%0AKwYBBAGCNxQCAgYKKwYBBAGCNwoDDDANBgkqhkiG9w0BAQsFAAOCAgEAD5VBDEAS%0AEJ%2BF0k2Ln5VAcSr5arpuYq%2BEQ6trbLi1e22cFi27iN2lam9az53LMKihWMfmnfN6%0Av%2FKfSeoQWEULvIC8VE%2BJQmD8J708co71KMmnwHhwGNaTcWvXr3%2FKdKA4W5WAdORq%0AvuzkgS1ciWQ86juR4ylZsPOdo038kyBndXgVkLl2%2Fb6Xb5ZfFnUsptXvqqgzL02u%0A2DhY2E9I5eLJ%2FO1IYhGkeGdD5cbZG1VJglYpCksv5Gemwh9NDYXI5boKT3dTin7L%0AvJkrIiW0hh31ieM14Wkm1ak%2B7NwB15LBrU9exT0BpQumoArRKnRtiGyTI3i86XFM%0AfQIvRXExDQI4LJw0u0r89r5bnubRx4xj%2B6ZQajSdjdetvEgCZwPkssvjoD8XCoVW%0AG7kpeq%2B3%2B%2B79v9PkIEsaZ%2BcDlyPy3iWivYa5d3I9LlF3sfcS0%2F542oeMpvZepvn7%0AujOw7kCJzWEjSSBD7AM4YRuOE57%2FbPuTTUvwk2lT37zLdc6ZYd4kiIEEDhaGkKlM%0AvvsFRMZmbcEELkTSIussav1AsqlHMU5sIsvWy8I92WemFI2TbvPoQ74B1OgHjBY8%0A4N4aLg8ADyBavlsvnE1zQyzvxu2tTNI%2FJS5gpU0%2FHZAuGyOIE9xPybC3%2BJqRIYU%2B%0AAMr%2FhN8ESPIb%2BeDTBpja6M1%2BtVMgt8qxWX4%3D%0A-----END%20CERTIFICATE-----%0A';

    $certInfo = DigitalSignature::getContents($certificate);
    $details = DigitalSignature::getSubjectIdentifier($certInfo);

    dd($certInfo, $details);
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
