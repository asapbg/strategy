<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name') }} :: {{ isset($title_plural) ? $title_plural : "Начало" }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var GlobalLang = "{{ str_replace('_', '-', app()->getLocale()) }}";
    </script>
    <script src="{{ asset('vendor/ckeditor/ckeditor.min.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/translations/bg.min.js') }}"></script>
    @stack('styles')
</head>
@php
    if (isset($_COOKIE['nav'])) {
        $sidebarCollapse = false;
    }
@endphp
<body class="hold-transition sidebar-mini {{isset($sidebarCollapse) ? 'sidebar-collapse' : null}}">
<!-- Site wrapper -->
<div class="wrapper">

@include('partials.header')

@include('partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header p-0">
            <div class="container-fluid">
                @foreach(['success', 'warning', 'danger', 'info'] as $msgType)
                    @if(Session::has($msgType))
                        <div class="alert alert-{{$msgType}} mt-1" role="alert">{!! Session::get($msgType) !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @php Session::forget($msgType); @endphp
                    @endif
                @endforeach

                @if($errors->any())
                    @if(env('APP_DEBUG'))
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @foreach($errors->all() as $message)
                                <div>{{ $message }}</div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-danger mt-1" role="alert">Моля проверете за грешки
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        @includeIf('partials.breadcrumbs')

        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    @includeIf('partials.footer')
    @includeIf('modals.alert')
    @includeIf('modals.confirm')

</div>

<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')

</body>
</html>
