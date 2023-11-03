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


    <style>
      .table-light {
        --bs-table-bg: #d5e7f6;
      }

      .pris-row {
        border-bottom: 1px solid  #c0dbf2;
      }

      .col-md-3.pris-left-column {
       font-weight: 500;
       color: #20659e !important;
       font-size: 18px;
        }

        .col-md-3.pris-left-column i {
    font-size: 20px;
    width: 30px;
}

.pris-tag {
  background: #20659e;
    color: #fff !important;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 15px;
    display: inline-flex;
    margin-right: 5px;
    margin-top: 3px;
    margin-bottom: 3px;
}

.public-page {
  padding: 60px 0px;
}
    </style>


</head>

<header>

    <div id="topbar">
        <div class="container">
            <div class="row top">

                <div class="col-md-6">
                    <div class="contact-info d-flex align-items-center">
                        <a class="navbar-brand" href="#"><img src="../images/logo_title.png" alt="Logo" id="imageLogo"></a>
                    </div>
                </div>



                <div class="col-md-6 text-end">
                    <div class="registration text-right">
                        <a href="#" class="main-color">Регистрация</a>
                        <button class=" btn rss-sub main-color"><i class="login-icon fa-solid fa-right-to-bracket main-color"></i>Вход</button>
                        <button class=" btn rss-sub main-color"><i class="login-icon fas fa-search main-color"></i></button>
                    </div>
                </div>

            </div>




        </div>
    </div>


  <nav class="navbar navbar-expand-lg justify-content-center d-flex ">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
        aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav ">
          <li class="nav-item ">
            <a class="nav-link  " aria-current="page" href="#"><i class="bi bi-house-door-fill text-light"></i></a>
          </li>
          <li class="nav-item ">
            <a class="nav-link " aria-current="page" href="#">Обществени консултации</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link " aria-current="page" href="#">Оценка на въздействие</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="#">Актове на МС</a>
          </li>

          <li class="nav-item ">
            <a class="nav-link" href="#">Стратегически документи</a>
          </li>


          <li class="nav-item">
            <a class="nav-link" href="#">Консултативни съвети</a>
          </li>

          <li class="nav-item ">
            <a class="nav-link" href="#">Библиотека</a>
          </li>

          <li class="nav-item ">
            <a class="nav-link" href="#">OGP</a>
          </li>

          <li class="nav-item ">
            <a class="nav-link" href="#">Новини</a>
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

<body>


  <section class="slider">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="slider-content">
            <div class="breadcrumbs">
              <a href="#">Начало</a> » <a href="#">Консултативни съвети</a>
            </div>
            <div class="page-heading">
              <h1>
                Висш съвет по фармация
              </h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section class="public-page">
    <div class="container">

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Председател/и</h2>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Проф. Христо Хинков, министър на здравеопазването
            </li>
          </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Заместник-председател/и
          </h2>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Проф. Илко Гетов, заместник-министър
            </li>

          </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Членове</h2>

          <ul class="list-group list-group-flush">
            <li class="list-group-item">д-р Александър Златанов, МЗ</li>
            <li class="list-group-item">Маг. фарм. Венда Зидарова, МЗ            </li>
            <li class="list-group-item">Христина Гетова, МЗ</li>
            <li class="list-group-item">Маг. фарм. Орлин Недев, МЗ
            </li>
            <li class="list-group-item">Маг.фарм. Розалина Кулаксъзова, ИАЛ
            </li>
            <li class="list-group-item">маг.-фар. Любима Бургазлиева, Български фармацевтичен съюз
            </li>
            <li class="list-group-item">Проф. Асена Сербезова, Български фармацевтичен съюз
            </li>
            <li class="list-group-item">Маг.фарм. Велина Григорова, Български фармацевтичен съюз
            </li>
            <li class="list-group-item">Маг .фарм. Венелин Сапунаров, Български фармацевтичен съюз
            </li>
            <li class="list-group-item">Маг.фарм. Анжела Мизова, Български фармацевтичен съюз
            </li>
          </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Правилник за вътрешната организация на дейността</h2>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Правилник за организацията и дейността на Висшия съвет по фармация_обн., ДВ, бр. 71 от 31.08.2007 г., изм. и доп., бр. 40 от 29.05.2012 г.</a>
                </li>
                <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class=" fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Правилник за организацията и дейността на Висшия съвет по фармация</a>
                <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Правилник за организацията и дейността на съвета</a>
                </li>
            </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Работна програма
          </h2>
        </h2>
        <p>Проведено заседание на ВСФ на 15.05.2018г. при следния дневен ред:</p>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">1. Избиране на заместник -председател на ВСФ чрез тайно гласуване.</li>
          <li class="list-group-item">2. Обсъждане на текущи въпроси по функционирането и приоритетите на Министерството на здравеопазването във фармацевтичния сектор.
          </li>
          <li class="list-group-item">3. Приемане на годишна работна програма на Висшия съвет по фармация.
          </li>
          <li class="list-group-item">4. Обсъждане на нормативни промени във връзка с оптимизиране на дейността на лечебните заведения. </li>
          <li class="list-group-item">5. Разни</li>
          </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Секретариат</h2>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Секретар: </strong>маг. фарм. Таня Гергинова, държавен експерт в дирекция "Лекарствена политика", отдел "Лекарствени продукти", Министерство на здравеопазването.
            </li>
            <li class="list-group-item"><strong>Лице за контакт: </strong>: маг. фарм. Таня Гергинова, главен експерт в дирекция "Лекарствена политика", отдел "Лекарствени продукти", Министерство на здравеопазването.</li>
            <li class="list-group-item"><strong>Телефон: </strong> <a href="tel:+3599301131">02/93 01 131</a>
            </li>
            <li class="list-group-item"><strong>E-mail: </strong><a href="#">tgerginova@mh.government.bg</a>
            </li>
        </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Заседания</h2>
          <p>
            Проведено бе заседание на ВСФ на 10.03.2017г . на което, чрез тайно гласуване бе избран заместник-председател на ВСФ. Обсъдено бе текущото състояние на системата на лекарствоснабдяването и актуалните проблеми през 2017г.
          </p>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Дневен ред на заседание 2023 г.</a></li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file main-color me-2 fs-5"></i>Заседание на ВСФ на 15.05.2018г. от 10:00ч</a>
            </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file main-color me-2 fs-5"></i>Дневен ред на заседание на ВСФ 25.09.2019</a>
            </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-lines main-color me-2 fs-5"></i>Дневен ред на заседание на ВСФ_19.11.2019г.</a> </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Дневен ред за заседание на ВСФ на 19.11.2020 г.</a> </li>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Функции</h2>
          <p>Висшият съвет по фармация обсъжда и дава становища по:</p>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">1. основните насоки и приоритети в областта на фармацията;</li>
            <li class="list-group-item">2. етични проблеми на фармацията;
            </li>
            <li class="list-group-item">3. проекти на нормативни актове свързани с фармацията;
            </li>
            <li class="list-group-item">4. научни приоритети в областта на фармацията; </li>
            <li class="list-group-item">5. програми за организиране на обществени образователни кампании в областта на лекарствените продукти.</li>
            </ul>
        </div>
      </div>

      <div class="row mb-5 ks-row">
        <div class="col-md-12">
          <h2 class="mb-2">Заповеди</h2>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Заповед ВСФ РД-02-76_04.06.2021г.</a>
            </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class=" fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Заповед РД-02-27_01.03.2022 г.</a>   </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Заповед РД-02-2022_08.09.2022 г.</a>
            </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class=" fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Заповед РД-02-173_17.07.2023 г.</a> </li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class=" fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Заповед РД-02-177_18.07.2023 г.</a></li>
            <li class="list-group-item"><a href="#" class="main-color text-decoration-none"><i class=" fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Заповед № РД-02-142/30.10.2019 г.</a></li>
        </ul>
        </div>
      </div>


    </div>
  </section>

</body>

<footer>
  <div class="container">
    <div class="row">
      <div class="col-6 col-md-2 mb-3">
        <h5 class="text-light">Полезни връзки</h5>
        <ul class="nav flex-column footer-nav">
          <li class="nav-item mb-2"><a href="#" class=" p-0 text-light">» Начало</a></li>
          <li class="nav-item mb-2"><a href="#" class="p-0 text-light">» Новини</a></li>
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
            <label for="newsletter1" class="visually-hidden">Имейл адрес</label>
            <input id="newsletter1" type="text" class="form-control" placeholder="Имейл адрес">
            <button class="btn rss-sub" type="button">Абониране</button>
          </div>
        </form>
      </div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-between pt-4  border-top">
      <p class="m-0 text-light">© 2023 Портал за обществени консултации. Всички права запазени.</p>
    </div>
  </div>

</footer>



</div>

</html>
