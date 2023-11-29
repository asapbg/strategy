<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Портал за обществени консултации</title>

    <!-- Bootstrap  CSS -->
    <link href="{{ asset('/vendor/bootstrap/bootstrap.css') }}" rel="stylesheet" crossorigin="anonymous">

    <!-- Font-awesome -->
    <link href="/vendor/fontawesome-free-6.4.0-web/css/all.css" rel="stylesheet">

    <!-- Custom css -->
    <link href="{{ asset('css/app_vendor.css') }}" rel="stylesheet">
    @stack('styles')

    <!-- Add favicon -->
    <link rel="icon" href="/img/strategy-logo.svg" sizes="16x16 32x32" type="image/png">
    <script type="text/javascript">
        var GlobalLang = "{{ str_replace('_', '-', app()->getLocale()) }}";
    </script>
</head>

<body>
<header class="fixed-top" id="header-wrapper">
    @include('partials.top_bar_front')
    @include('partials.nav_bar_front')
</header>
@if(isset($pageTitle) && request()->route()->getName() != 'home' && !request()->input('sk'))
    <section class="slider" id="slider">
        <div class="@if(isset($fullwidth) && $fullwidth) container-fluid @else container @endif">
            <div class="row">
                <div class="col-md-12">
                    <div class="slider-content">
                        <div class="page-heading">
                            <h1 class="mb-0">
                                {{ $pageTitle }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@include('partials.breadcrumbs_front')
@if(request()->route()->getName() != 'home' && !request()->input('sk'))
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

<!-- SCRIPTS -->
<script src="{{ asset('js/app_vendor.js') }}"></script>
@stack('scripts')
</body>
</html>
