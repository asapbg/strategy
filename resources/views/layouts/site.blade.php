<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Портал за обществени консултации</title>


    <!-- Bootstrap  CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <!-- Font-awesome -->
<link href="/vendor/fontawesome-free-6.4.0-web/css/all.css" rel="stylesheet">

    <!-- Custom css -->
<link href="{{ asset('css/app_vendor.css') }}" rel="stylesheet">
<link href="{{ asset('css/site.css') }}" rel="stylesheet">
@stack('styles')

<!-- Add favicon -->
<link rel="icon" href="/img/logo_title.jpg" sizes="16x16 32x32" type="image/png" >
<script src="{{ asset('js/app_vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</head>

<body>
<header>

  <div id="topbar">
    <div class="container">
      <div class="row top">
        <div class="col-md-5"> 
          <div class="contact-info d-flex align-items-center">
            <a class="navbar-brand" href="#"><img src="/img/logo_title.png" alt="Logo" id="imageLogo"></a>
          </div>
        </div>
  
        <div class="col-md-4">    
          <div class="search">
            <i class="fas fa-search main-color"></i>
            <label for="search-field" class="visually-hidden">Търсене в сайта</label>
            <input type="text" class="form-control" id="search-field" placeholder="Търсене в сайта">
            <button class="btn btn-primary">Търсене</button>
          </div>
        </div>

        <div class="col-md-3 text-end">    
          <div class="auth">
            @if(app('auth')->check())
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="profile-menu" data-bs-toggle="dropdown" aria-expanded="false">
                @php($user = app('auth')->user())
                {{ $user->first_name . ' ' . $user->last_name }}
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
            <a class="btn btn-default" href="{{ route('login') }}">{{ __('custom.login') }}</a>
            <a class="btn btn-default" href="{{ route('register') }}">{{ __('custom.register') }}</a>
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
          <li class="nav-item mx-1">
            <a class="nav-link active " aria-current="page" href="/">Начало</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link " href="{{ url('/consultations') }}">Обществени консултации</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link" href="#">Актове на МС</a>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link" href="#">Стратегически документи</a>
          </li>


          <li class="nav-item mx-1">
            <a class="nav-link" href="#">Публикации</a>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link" href="#">OGP</a>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link" href="#">Новини</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>


<section class="slider">
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


<section class="public-page">
  <div class="container" id="app">
    @yield('content')
  </div>
</section>

<footer>
  <div class="container">
      <div class="row">
        <div class="col-6 col-md-2 mb-3">
          <h5 class="text-light">Полезни връзки</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Начало</a></li>
            <li class="nav-item mb-2"><a href="#" class= "p-0 text-light">» Новини</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Публикации</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Мнения</a></li>
          </ul>
        </div>
  
        <div class="col-6 col-md-2 mb-3">
          <h5 class="text-light">Информация</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» За нас</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Карта на сайта</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Условия за ползване</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Често задавани въпроси</a></li>
          </ul>
        </div>
  
        <div class="col-6 col-md-2 mb-3">
          <h5 class="text-light">Контакти</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Адрес</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Телефон</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Имейл</a></li>
            <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Фейсбук</a></li>
          </ul>
        </div>
  
        <div class="col-md-5 offset-md-1 mb-3">
          <form>
            <h5 class="text-light">Абонирайте се за нашия бюлетин</h5>
            <p class="text-light">Получавайте актуална информация относно обществени консултации, новини и др.</p>
            <div class="d-flex flex-column flex-sm-row w-100 gap-2">
              <label for="newsletter1" class="visually-hidden" style="color: #fff !important;background: #000;">Имейл адрес</label>
              <input id="newsletter1" type="text" class="form-control" placeholder="Имейл адрес">
              <button class="btn rss-sub subscribe" type="button">Абониране</button>
            </div>
          </form>
        </div>
      </div>
  
      <div class="d-flex flex-column flex-sm-row justify-content-between pt-4  border-top">
        <p class="m-0 text-light">© 2023 Портал за обществени консултации. Всички права запазени.</p>
      </div>
  </div>
 
</footer>
</body>
</html>
