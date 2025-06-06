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

Route::get('/user-data-test', function () {
    $saml = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHNhbWwycDpSZXNwb25zZSBEZXN0aW5hdGlvbj0iaHR0cHM6Ly9zdHJhdGVneS5hc2FwYmcuY29tL2VhdXRoL2xvZ2luLWNhbGxiYWNrIiBJRD0iX2M5NWE4ZGM0LWJhZjYtNDQ2Zi1iMjIzLWFiMGViMjNkODBjZSIgSW5SZXNwb25zZVRvPSJBUlExYTFkZDZhLTM1OTItNDdhYi1hZTI1LTVjMzJkZmQ5MTcyMCIgSXNzdWVJbnN0YW50PSIyMDI1LTA2LTA2VDEyOjU1OjE5LjI2N1oiIFZlcnNpb249IjIuMCIgeG1sbnM6c2FtbDJwPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6cHJvdG9jb2wiPjxzYW1sMjpJc3N1ZXIgRm9ybWF0PSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6bmFtZWlkLWZvcm1hdDplbnRpdHkiIHhtbG5zOnNhbWwyPSJ1cm46b2FzaXM6bmFtZXM6dGM6U0FNTDoyLjA6YXNzZXJ0aW9uIj5odHRwczovL2VhdXRoLmVnb3YuYmc8L3NhbWwyOklzc3Vlcj48c2FtbDJwOlN0YXR1cz48c2FtbDJwOlN0YXR1c0NvZGUgVmFsdWU9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDpzdGF0dXM6UmVzcG9uZGVyIj48c2FtbDJwOlN0YXR1c0NvZGUgVmFsdWU9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDpzdGF0dXM6QXV0aG5GYWlsZWQiLz48L3NhbWwycDpTdGF0dXNDb2RlPjxzYW1sMnA6U3RhdHVzTWVzc2FnZT7Qk9GA0LXRiNC90Lgg0LTQsNC90L3QuCDQv9GA0Lgg0LDQstGC0LXQvdGC0LjQutCw0YbQuNGPITwvc2FtbDJwOlN0YXR1c01lc3NhZ2U+PC9zYW1sMnA6U3RhdHVzPjxzYW1sMjpFbmNyeXB0ZWRBc3NlcnRpb24geG1sbnM6c2FtbDI9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjIuMDphc3NlcnRpb24iPjx4ZW5jOkVuY3J5cHRlZERhdGEgSWQ9Il85YTI2NTU5ZGNjZTBkZDI0YWVlNzI0NTkwMDRhNDJjZCIgVHlwZT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjRWxlbWVudCIgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIj48eGVuYzpFbmNyeXB0aW9uTWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjYWVzMTI4LWNiYyIgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIi8+PGRzOktleUluZm8geG1sbnM6ZHM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyMiPjx4ZW5jOkVuY3J5cHRlZEtleSBJZD0iX2JjZDRkMGQ5OGMwMDU1MzUyODZiMzU1NGJhOTA3NjE3IiB4bWxuczp4ZW5jPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGVuYyMiPjx4ZW5jOkVuY3J5cHRpb25NZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGVuYyNyc2Etb2FlcC1tZ2YxcCIgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIj48ZHM6RGlnZXN0TWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnI3NoYTEiIHhtbG5zOmRzPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjIi8+PC94ZW5jOkVuY3J5cHRpb25NZXRob2Q+PHhlbmM6Q2lwaGVyRGF0YSB4bWxuczp4ZW5jPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzA0L3htbGVuYyMiPjx4ZW5jOkNpcGhlclZhbHVlPjFESGMxWGN4QmgyUk1rU3FLTDNCMWpVcFVSMGo4T2tjVWk0VWZKci9Nb3FLVy9aeFVSQjlJNVFhdW8wZTRJU0ozSW1pblloY01iaGQmI3hkOwpHSG91QnJhRDliUU1CMzViSnJlTTE2OXYxbVN3bGs2c3lIdE43K1VuYmdFTktUc2NIaGxpcE0vTXNEbkpFbUVZTVRvaW5TWkRPQjhKJiN4ZDsKM3FUOGswSm12WTJ1cDk1WUVxbjNDd1pVSWhYUUZRbTMvYnRsSHhDeVdvSTNibGIwMHpLZENPRitNR0ZudnZzMnNYL3k2MXVxaE5yTSYjeGQ7CjI4N0Jnb3hRUk5hSXVpWUdFOVhQdmlMRmVveFRxdUV0MTZGaFU1SjhvQld6MjJZenJZVnI0SHpSd2tGSWpLblk5bU5HYkgzMXRGL1QmI3hkOwo3QkV6d0lkbU5kci91dkt4Q1hOV3dzYnNuaGRKQ0NOdFpPS3M2UT09PC94ZW5jOkNpcGhlclZhbHVlPjwveGVuYzpDaXBoZXJEYXRhPjwveGVuYzpFbmNyeXB0ZWRLZXk+PC9kczpLZXlJbmZvPjx4ZW5jOkNpcGhlckRhdGEgeG1sbnM6eGVuYz0iaHR0cDovL3d3dy53My5vcmcvMjAwMS8wNC94bWxlbmMjIj48eGVuYzpDaXBoZXJWYWx1ZT4zcFhDQndBR0dta3pwQWpVTzdSM1V2am5QL011RU1PS2pUWmhNcitrWHVENGlTYk9KcGZmK2xCMFlmUEwzRXFlUzdaVVFtMlBuZUFNJiN4ZDsKT3hDQnl2aE1pdklqWXlWL1pUWXAvNnFlR1hEc3pnS1V5YytKTGEwVmx0enIxcTZEcTRIMmFrcjZMSHA2UTdsTm9seTNURlRlMjdBdSYjeGQ7CjJEZ3lyT2NNQlg5bldleTExdFYvUTJxc3MxS3c1RFJKR2ZOMnB0UFB1MURFV2haV1E1SnVIS3kwMlkzLy95UDA1Wjc3OVBFVzFUMmYmI3hkOwo3NzBVbWsrZWxCZDVhanltQ0doNHB0ZFQ3Vmw2ZWIrYVUvNlVRUUw3RGhCSHR3YnVjZS96VWtyWFJwYnFWK05UUEtVOVJQVzFZVjR0JiN4ZDsKeVZFRG9rdmM4RFdnSFBUd2VvYndJQXRNZ2ttQmgzOEd0U3p2cTc1aXFuZXBmWkZlVTNZOGY5RGt0QVdiSkd6T3FhYzlnRjBjYlhxbCYjeGQ7Cm9XMzRPOWpYQmtxdHRpcGRxem8yek9rZlgwTGZHY3ZLSllYcjVqcXNGN2xZa2xuZ2RNR2dlNTd6c3JvZStIMEZVR3ZmUHdNSzg0dlAmI3hkOwpqQkx0cFRkc2Vhd3JyRk1VUnBiL1ZMVHhtSjgwVm1wOE5GZTVYL3pxMm0zT3lIUDZteXdnY2l3d291Q1RyNkVnMFYzT3ZKQXlJTno5JiN4ZDsKS2NOWjdzaUFJWFVOOXhhdndMbGFBenB4eW9FVDVsRTQxMEhxNGNyV240ZGQ0YmNWOW0yamxaaWdiSG9lUlNJcGlWUTMvNjdkYXdCSyYjeGQ7CkNtbURCUHRzdVVsdUpKWURuci8wODdwenFWUHhoSEg3MkE1MWpzSDVxVFZnMGh4UHp3T25zL29FU0FRNmRlUnlob2NIbFpkb2dpTGsmI3hkOwo3Qk9TSHgvU08yWnpLWHRaVXk5eENCUUxDUGdoWTR1aDV2NkFLbjdFalcrWkpzZzgyalNFaVBZdU1XZVFGazg3Tm4rcldVL09DUE1VJiN4ZDsKY3JuQWJLUW45c1NVTlBLTXpuaVM1ellSTEltejlseWdFRVRZVmJZd2gvNHcrclExKzVtWERUR0xmL0E0eVd6U1FMQW1HQUpwRmNzNyYjeGQ7CnZmU3FKc3R4RXRsN0lWcTRKeEpVaTBjdXc1R281THFXOUNjcG5hdDlnMGdHdFJUMGJVcHpLOWJNZVlGZGpTVkFQOFpmc25uOElmWEsmI3hkOwp5TnpQZ1o3aWpaM0xyS3Bnc1pSc2k5dDBPdGtNSmltd1FxMHFHVTdUS2dTb084UE5CVnQycXB0MW9ScUo5cFNhNWh4NDZWcGFHazZuJiN4ZDsKRW9XM00yUVVSL1Z1azE1NldvOXAzKzg3Vy9sMzFGd2FCZVF1ZDRtZUFmRW5aYWNyL3hLM0VRY3owbFBEY2xNbndIWGNXOHZZQktNUiYjeGQ7CmpPNzVFcC9qZHJLV0lRd3RrSjFQbVBla2s4eDl2NW1oVTI4MkJpYmxzOGNhZ1ArQ1JKZDNYM2Q4eHZsYVpGOGRjWDJTY3NNaFJsY08mI3hkOwpidm55U3FiNkJuMDJhcjBNQ05qekFuRE15d0pHWjErZkNrU0VJanJ4RHRaZjc3bExhdFNjQ2k0anhubjFrZVlXa3RJL2t6VjhGby85JiN4ZDsKT3N5bzVVc0NNY2VmSzlTV2ZsUFA2OWdnYnVlajl4WjJkRy9ieGhGbFN3TFo5WW1tY3licitLbE0wYXYyNHh2TTFMRVA5VWNhQ3psdSYjeGQ7CnA4c2hTMHFhQ2FlWXFGL3RhZC82SHJSSmcwcXIyQ2RyT3hTTmFZSkRjREJDQmhtcExHWTlTaHQweGQzR3N3SHRuMFZPQmpyQldQMDQmI3hkOwp4bmorcmh6bXloRzQ0eWNZYURQN3FWNExiUk1pNlJlT3YwY0IyZ3JkMDZSSW55N2N4SzIySmFraktHcWpaQXhVTStXeWw4NUFEMFFCJiN4ZDsKVUdpTWVpa1NQczJka2doWTNpSmdaWWhZeG5SVHhsV21lTXU5OVVNN1FlRzdIampSby9Cam5jY0VGejdCTG04VzM0YTZTQm5LWm9DbSYjeGQ7CmtlYUViVjdxM2VoQVFDbE5LYjZCdnJqVkJYK05WclQ1cmxZeFlCNUFWMzVWMDRzcWh5eFB2TENqdThRWUN4RytmR2dOR04zS0M3Z2wmI3hkOwplbEZ6MVd0VEtJKzdLdzlLNis1YXJnV3Ria09pTGJ3RlRvbU8zTWRHSzZOby9CWTJiVXI0OEwzOE8zdkxSSXlxZ08wM3VULytKd1cwJiN4ZDsKdnBubHdaRFNuUmorYk1xRHlaSEU0TGc1VXdiTkFKU0lwT3dSUlJHMUxocHhmcWlaNzR1RHNkN3huaUFtYnhCbXI0aGlHdzdhazlISyYjeGQ7Cm5tN05GSjZ4L052eUh5enpRRXZGUjUzWXVTYTdFWDlHeWZlbEVqOCtLazI3ZjFvdXlkR0NwcHZJK2t2QVZrbk05V0xIZmFpZmkwSjMmI3hkOwpMWENWWEdHdDhTWFh0RTFycDYwMUlRemRLcVk1T2xFNjNVWG1sMmhVNEIwUmVidEgzSGk0SnZMKzRWNldWbEY0SUVVWVAwVUo3ZjdVJiN4ZDsKYk1jOXNEcjBpaDNDd0ZxTXdLVnJ6Qnc2L3IraGJtSjVNeDFiRHNmVkNWNTZwZkF3cEFUN1FrbjJraXdxVFB0K1ZEdUltamZzNklHSiYjeGQ7Ci80V1FoM002WUZGcm8wUlNQWFNQbkc2MjZFVkxweWVJL0VqZHl3dXNITSs0MTl6OVhyaFZCaWNBbEFoS0lOVzBKbFNOVGY3TEZlZEUmI3hkOwpJcmpRZUxyVTFCS0UwQzFzeDhGcmFGWExmQ3pScXNtZjVuRHR2RFNCOFNIY0xNaG5la01xc1ZJazNkVkRhUE1NcWM2UFJLTk5FYVFiJiN4ZDsKUkpFUVRjWE01NmhQZWdYbmpWR2JkRjdKaHZPdHR4NC9tM2d4dzhnRFlVdE9QTVFVS2NQQUQ4d1F1N0lsN05FWkpySnZiZnJKQjJtMyYjeGQ7CmtycjVXZTNCK0dUT1YzcFFOMEdqVW5BUm1odmlMU1BUZVFLSzBrMFZmMStjaCtaaG15aVBZYTVYODBSMHRxZko2a0RxUXNiSlcrelUmI3hkOwp3dFdOSTI3T3hLUXFNTlZsNDlpdHlISkxBTW5UTVZqMk5FdDF4Umg5U0VpQ0xCSDVHa3J6MldBYU43MDZFdTBkVGJ3aCtkR3VnTjBrJiN4ZDsKVXNYcU9JKzl2cUcvc2Z2YzFKNkpaQnpxd0FPSGxiQmxDQU81UzdBOG9BMndZS3dtZWhDWmY2VGZ1R2Vud3J4K1NxTFNpSGpaMkdsbCYjeGQ7Cko5MncrNnFtRTlDQVZqUlNuTUk2T1Z2ekd5TUs4R1p3dE5XRzhSTWlnWk9ienVhd1Z2ci9YOWdPSWFHSmJxd09sK01LdjVIamU1SDkmI3hkOwplQ1FJdGVSSFl0TDRubk9xOTVqR09zTXUrak1sQVZZUTU3cCsvVXBGYk9FazJuNHNMMGQ4SWdjMlUvUnVMbnhLbWlNL2xPVHJBODNEJiN4ZDsKVWd4WGVHb0V1OXYvQUNudFNlL3FqRjUybXVvL2t4SGJ0c1duVWRRSlNIY1RGR29lNzVxbzhyYlZmaWcxazFHTlo0S1Q0dkxGL3JDTyYjeGQ7CkhIRHMzSitLNTRLUnUvVjZiT0g5UjlSWVhwM0YrYTI0SGZGcnd2am1KcURERnRUaXNJUGk0VjQ2cDRqL0dFak9yNjN6Q0VKeGFHYTAmI3hkOwpMQTNzREZDMGFqQU8rWGljQXRJUDZ1aUxta2RhUFdsY0ZqNEtObUtHekh0OTE1a0k2eWlaWDNna1B6eHM1WUcyTElrVjgyNjBySG9jJiN4ZDsKdklSeUZyWmwyaUVGOFFmaGFIOXZXYnhsb09TRkg2WDh6bEpUUG4wdnpFQmU5Ny93bHdBaFBjWHhselcvemlFRCtDaGtkbzVIWFh4VCYjeGQ7CnEzcjJyRUEwcFF4VkhlVm5kMk5neDZGWXZ2QisvaHNGb3BUVWU2RXMveDQyWEZaTXY4YVJWdHZSTWxKWjFSRTBKNlN0STFoNVNXRG0mI3hkOwpCczJhMDVLaHRoZnhOT0hGK0ZRYTJ4d3RicDZza3poRDNMelBZNzBaMk4wWlhPblluM0M0Q2F5S0pPaU5zQ3NnQVFKWHVzVFZpa0JxJiN4ZDsKY2UzbDE2Ni9YN1hYQTdNRS9XZXA1ai9IWlEzL3RnQVM1a1F3d1lrbC83dUZGSFg0VXlqNUs0aW1oaEZqVmxNaUxTeXg2ZWN4YkNhZiYjeGQ7CkZGaXZzRXhXNUhrc2lqWkFMWGhUTjZaWGNocGdFVEEwVGdEdjBOZjFwZkl4dGdFbEJuOUNrUG9rR3pNMlJtMFdqRUxHTXNYdThxREYmI3hkOwphYldLSnZxUWpTVDQvM2JabjV2ZExwZjZjSG52YVRlV2tvN1U4QWd0MFByMzZaTThqWmV6WFNhKzBmTEJCdDFDaWozbGZaSHZKZUJvJiN4ZDsKNWdqUmRIb2dLLzRRZzdJdk1ScEsxWTExbE5qUGdsRE1jUU5pbVBhdXRJbkQ5YlR3M1phVWRER1Y1VjZxaUtZa3Jrd2JNeWY5L2JLciYjeGQ7CktlbTdiN3o4QTRpcGtnQzZBNzFnR2p2MnV3bTZ5ZHJPbElqNlg1MHJtT0hxT0ZDQWF3amdReE5zd0JJSUNrS3FkYm9UaUE9PTwveGVuYzpDaXBoZXJWYWx1ZT48L3hlbmM6Q2lwaGVyRGF0YT48L3hlbmM6RW5jcnlwdGVkRGF0YT48L3NhbWwyOkVuY3J5cHRlZEFzc2VydGlvbj48L3NhbWwycDpSZXNwb25zZT4=';

    $eauth = new \App\Library\EAuthentication();
    dd($eauth->userData($saml));
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
