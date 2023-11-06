<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Портал за обществени консултации</title>


    <!-- Bootstrap  CSS -->
 <link href="{{ asset('/vendor/bootstrap/bootstrap.css') }}" rel="stylesheet" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <!-- Font-awesome -->
<link href="/vendor/fontawesome-free-6.4.0-web/css/all.css" rel="stylesheet">

    <!-- Custom css -->
<link href="{{ asset('css/app_vendor.css') }}" rel="stylesheet">
{{--<link href="{{ asset('css/site.css') }}" rel="stylesheet">--}}
@stack('styles')

<!-- Add favicon -->
<link rel="icon" href="/img/logo_title.jpg" sizes="16x16 32x32" type="image/png" >
<script src="{{ asset('js/app_vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</head>

<body>
<header class="fixed-top">

  <div id="topbar">
    <div class="container">
      <div class="row top">
        <div class="col-md-6">
          <div class="contact-info d-flex align-items-center">
            <a class="navbar-brand" href="#"><img src="/img/logo_title.png" alt="Logo" id="imageLogo"></a>
          </div>
        </div>

{{--        <div class="col-md-4">--}}
{{--          <div class="search">--}}
{{--            <i class="fas fa-search main-color"></i>--}}
{{--            <label for="search-field" class="visually-hidden">Търсене в сайта</label>--}}
{{--            <input type="text" class="form-control" id="search-field" placeholder="Търсене в сайта">--}}
{{--            <button class="btn btn-primary">Търсене</button>--}}
{{--          </div>--}}
{{--        </div>--}}

        <div class="col-md-6 text-end">
          <div class="auth text-right">
            @if(app('auth')->check())
                <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="profile-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        @php($user = app('auth')->user())
                        {{ $user->is_org ? $user->org_name : $user->first_name . ' ' . $user->last_name }}
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="profile-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">{{ trans_choice('custom.profiles', 1) }}</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="javascript:;"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                              {{ __('auth.logout') }}
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                              @csrf
                          </form>
                        </li>
                      </ul>
                </div>
            @else
                  <div class="registration text-right justify-content-end align-items-center d-flex">
                    <!--                    <form class="form-inline me-4">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Търсене" aria-label="Username" aria-describedby="basic-addon1" style="border-top-right-radius:0px !important;border-bottom-right-radius:0px !important;">
                        <div class="input-group-prepend rounded-0">
                          <span class="input-group-text search-btn d-block" id="basic-addon1"><i class="fa-solid fa-magnifying-glass main-color"></i>
                          </span>
                        </div>
                      </div>
                    </form> -->
                 

                    <a class="main-color me-3" href="{{ route('register') }}">{{ __('custom.register') }}</a>
                    <a class="btn btn-primary me-3" href="{{ route('login') }}"><i class="login-icon fa-solid fa-right-to-bracket main-color"></i> {{ __('custom.login') }}</a>
                    <a href="" class="cstm-btn btn btn-primary login-search "><i class="login-icon fas fa-search main-color"></i></a>
                  </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>

  <nav class="navbar navbar-expand-lg justify-content-center d-flex ">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav ">
          <li class="nav-item">
            <a class="nav-link @if(request()->route()->getName() == 'home') active @endif" aria-current="page" href="/"><i class="bi bi-house-door-fill text-light"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(str_contains(request()->url(), 'public_consultations_view')) active @endif" href="{{ url('/consultations') }}">Обществени консултации</a>
          </li>
            <li class="nav-item ">
                <a class="nav-link " aria-current="page" href="{{ route('impact_assessment.index') }}">Оценка на въздействие</a>
            </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Актове на МС</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">Стратегически документи</a>
          </li>

            <li class="nav-item">
                <a class="nav-link" href="#">Консултативни съвети</a>
            </li>

            <li class="nav-item ">
                <a class="nav-link @if(str_contains(request()->url(), '8_2_1_1_9_public_library_list')) active @endif" href="#">Библиотека</a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="#">OGP</a>
            </li>

            <li class="nav-item ">
                <a class="nav-link @if(str_contains(request()->url(), '8_2_1_1_9_public_news')) active @endif" href="#">Новини</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="#">EN</a>
            </li>
            <li class="nav-item" style="padding-right: 0px !important;">
                <a class="nav-link" href="#"><i class="fa-brands fa-facebook text-light"></i></a>
            </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
@if(request()->route()->getName() != 'home' && !request()->input('sk'))
<section class="slider" id="slider">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="slider-content">
          <div class="breadcrumbs">
            <a href="#">Начало</a> » <a href="#">@yield('pageTitle')</a>
          </div>
          <div class="page-heading">
            <h1>
              @yield('pageTitle')
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

@if(request()->route()->getName() != 'home' && !request()->input('sk'))
<section class="public-page">
  <div class="container" id="app">
@endif

      @foreach(['success', 'warning', 'danger', 'info'] as $msgType)
          @if(Session::has($msgType))
              <div class="alert alert-{{$msgType}} mt-1 alert-dismissible py-2" style="z-index: 9999;" role="alert">{!! Session::get($msgType) !!}
                  <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          @endif
      @endforeach

    @yield('content')

@if(request()->route()->getName() != 'home' && !request()->input('sk'))
  </div>
</section>
@endif

<footer>
  <div class="container">
      <div class="row">
{{--        <div class="col-6 col-md-2 mb-3">--}}
{{--          <h5 class="text-light">Полезни връзки</h5>--}}
{{--          <ul class="nav flex-column footer-nav">--}}
{{--            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Начало</a></li>--}}
{{--            <li class="nav-item mb-2"><a href="#" class= "p-0 text-light">» Новини</a></li>--}}
{{--            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Публикации</a></li>--}}
{{--            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Мнения</a></li>--}}
{{--          </ul>--}}
{{--        </div>--}}

        <div class="col-md-4 mb-3">
          <h5 class="text-light">Информация</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item pb-0"><a href="#" class=" p-0 text-light">Условия за ползване</a></li>
            <li class="nav-item pb-0"><a href="#" class=" p-0 text-light">Към стария портал</a></li>
          </ul>
        </div>

        <div class="col-md-4 mb-3">
          <h5 class="text-light">Контакти</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item pb-0 text-light"><i class="fas fa-envelope text-white me-2"></i>main_mail@test.bg</li>
            <li class="nav-item pb-0"><a href="#" class=" p-0 text-light">Контакт с администрация</a></li>
          </ul>
        </div>

{{--        <div class="col-md-5 offset-md-1 mb-3">--}}
{{--          <form>--}}
{{--            <h5 class="text-light">Абонирайте се за нашия бюлетин</h5>--}}
{{--            <p class="text-light">Получавайте актуална информация относно обществени консултации, новини и др.</p>--}}
{{--            <div class="d-flex flex-column flex-sm-row w-100 gap-2">--}}
{{--              <label for="newsletter1" class="visually-hidden" style="color: #fff !important;background: #000;">Имейл адрес</label>--}}
{{--              <input id="newsletter1" type="text" class="form-control" placeholder="Имейл адрес">--}}
{{--              <button class="btn rss-sub subscribe" type="button">Абониране</button>--}}
{{--            </div>--}}
{{--          </form>--}}
{{--        </div>--}}
{{--      </div>--}}

      <div class="d-flex flex-column flex-sm-row justify-content-between pt-4  border-top">
        <p class="m-0 text-light">© {{ date('Y') }} {{ __('custom.copyright_text') }}</p>
          <a class="m-0 text-light text-danger text-decoration-none" href="https://www.asap.bg/" target="_blank">{{ __('custom.asap_support') }}</a>
      </div>
  </div>

</footer>
</body>
</html>
