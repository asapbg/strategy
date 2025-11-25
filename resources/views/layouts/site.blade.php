<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{ seo()->render() }}
    @if(isset($facebookAppId) && !empty($facebookAppId))
        <meta property="fb:app_id" content="{{ $facebookAppId }}"/>
    @endif
{{--    <title>{{ __('site.seo_title') }}</title>--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap  CSS -->
    <link href="{{ asset('/vendor/bootstrap/bootstrap.css') }}" rel="stylesheet" crossorigin="anonymous">

    <!-- Font-awesome -->
    <link href="{{ asset('/vendor/fontawesome-free-6.4.0-web/css/all.css') }}" rel="stylesheet">

    <!-- Custom css -->
{{--    <link href="{{ asset('css/app_vendor.css?v='.date('d')) }}" rel="stylesheet">--}}
    <link href="{{ asset('css/app_vendor.css') }}" rel="stylesheet">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @stack('styles')

    <!-- Add favicon -->
    <link rel="icon" href="{{ asset('/img/strategy-logo.svg') }}" sizes="16x16 32x32" type="image/png">
    <script type="text/javascript">
        var GlobalLang = "{{ str_replace('_', '-', app()->getLocale()) }}";
        var MaxUploadFileSize = parseInt("{{ config('filesystems.max_upload_file_size') * 1024 }}");
        var centralLevel = "{{ \App\Enums\InstitutionCategoryLevelEnum::CENTRAL->value }}";
        var centralOtherLevel = "{{ \App\Enums\InstitutionCategoryLevelEnum::CENTRAL_OTHER->value }}";
        var areaLevel = "{{ \App\Enums\InstitutionCategoryLevelEnum::AREA->value }}";
        var municipalityLevel = "{{ \App\Enums\InstitutionCategoryLevelEnum::MUNICIPAL->value }}";
    </script>

    @if(env('APP_ENV') == 'production')
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y) {
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "ub34b42l7i");
        </script>
    @endif
</head>

<body class="@if($vo_high_contrast) high-contrast @endif">
<header class="fixed-top" id="header-wrapper">
    @include('partials.top_bar_front')
    @include('partials.nav_bar_front')
</header>
@if(isset($pageTitle) && request()->route()->getName() != 'home' && !request()->input('sk'))

    @php
    $current_url = url()->current();
    $chunks = explode('/', $current_url);
    $class = '';
    if(isset($chunks[3]) && $chunks[3] === 'advisory-boards') {
        $class = 'advisory-boards-page';
    }
    @endphp

    @if(isset($slider) && isset($slider['img']))
        <section id="sliderr" class="{{ $class }}">
            <div id="carouselExampleSlidesOnly" class="carousel slide  bgr-main " data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="margin-top:115px;">
                        <img class="d-block w-100 adv-board-slide" src="@if(isset($slider) && isset($slider['img'])){{ $slider['img'] }}@endif" alt="First slide">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="centered-heading w-100 text-center px-3">
                                    <h1 class="text-light adv-board-heading" style="background: unset !important;" >
                                        {!! $pageTitle !!}
                                    </h1>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="slider {{ $class }}" id="slider">
            <div class="@if(isset($fullwidth) && $fullwidth) container-fluid @else container @endif">
                <div class="row">
                    <div class="col-md-12">
                        <div class="slider-content">
                            <div class="page-heading">
                                <h1 class="mb-0">
                                    {!! $pageTitle !!}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endif
@include('partials.breadcrumbs_front')
@if(request()->route()->getName() != 'site.home' && !request()->input('sk'))
    <section class="public-page">
        <div class="@if(isset($fullwidth) && $fullwidth) container-fluid @else container @endif" id="app">
            @endif
            @foreach(['success', 'warning', 'danger', 'info'] as $msgType)
                @if(Session::has($msgType))
                    <div class="alert alert-{{$msgType}} mt-1 alert-dismissible py-2" style="z-index: 9999;"
                         role="alert">{!! Session::get($msgType) !!}
                        <button type="button" class="btn-close py-2" data-dismiss="alert"
                                aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            {{--    @include('partials.breadcrumbs_front')--}}
            @yield('content')

            @if(request()->route()->getName() != 'home' && !request()->input('sk'))
        </div>
    </section>
@endif

@include('partials.footer_front')
<script integrity="sha256-LHqnhy8caV1Cl66WO+IL8lzq1/u0wekg4zodTnm8NWo=">
    var vo_font_percent = parseInt('<?php echo($vo_font_percent)?>');
    var vo_high_contrast = parseInt('<?php echo($vo_high_contrast)?>');
    var vo_ajax = false;
    var GlobalLang = "{{ str_replace('_', '-', app()->getLocale()) }}";
</script>

<div id="ajax_loader_backgr"></div>
<div id="ajax_loader">
    <h2>Моля изчакайте</h2>
    <div class="sk-cube-grid">
        <div class="sk-cube sk-cube1"></div>
        <div class="sk-cube sk-cube2"></div>
        <div class="sk-cube sk-cube3"></div>
        <div class="sk-cube sk-cube4"></div>
        <div class="sk-cube sk-cube5"></div>
        <div class="sk-cube sk-cube6"></div>
        <div class="sk-cube sk-cube7"></div>
        <div class="sk-cube sk-cube8"></div>
        <div class="sk-cube sk-cube9"></div>
    </div>
</div>
@include('cookie-consent::index')
<!-- SCRIPTS -->
{{--<script src="{{ asset('js/app_vendor.js?v='.date('d')) }}"></script>--}}
<script src="{{ asset('js/app_vendor.js') }}"></script>
@stack('scripts')
@if($vo_font_percent)
    <script type="text/javascript"  nonce="2726c7f26c">
        $(document).ready(function (){
            setDomElFontSize(vo_font_percent, true);
        });
    </script>
@endif

</body>
</html>
