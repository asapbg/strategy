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
    {{-- <link href="{{ asset('css/site.css') }}" rel="stylesheet">--}} @stack('styles')
    <!-- Add favicon -->
    <link rel="icon" href="/img/logo_title.jpg" sizes="16x16 32x32" type="image/png">
    <script src="{{ asset('js/app_vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
      .table-light {
        --bs-table-bg: #d5e7f6;
      }

      .pris-row {
        border-bottom: 1px solid #c0dbf2;
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

      .active-ks {
        color: #fff !important;
        background: rgba(var(--bs-success-rgb));
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 13px;
        display: inline-flex;
      }

      .inactive-ks {
        background: #ccc;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 13px;
        display: inline-flex;
      }

      .subscribe-row {
        border-bottom: 1px solid #c0dbf2;
      }

      .btn-primary {
        --bs-btn-bg: #20659e;
      }

      .btn-check:checked+.btn,
      :not(.btn-check)+.btn:active,
      .btn:first-child:active,
      .btn.active,
      .btn.show {
        color: #fff;
        background-color: #20659e;
        border-color: #20659e;
      }

      .btn.btn-labeled.bgr-main.rounded {
        background-color: #20659e;
      }

      .custom-card {
        box-shadow: rgba(99, 99, 99, 0.2) 0px 0px 8px 0px;
        border-radius: 6px;
        border-top: 3px solid #20659e;
      }

      .custom-hr {
        border-color: #c0dbf2 !important;
        width: 99% !important;
        margin: 0 auto;
        opacity: 1 !important;
      }

      .fs-18 {
        font-size: 18px !important;
      }
    </style>
  </head>
  <header>
    <div id="topbar">
      <div class="container">
        <div class="row top">
          <div class="col-md-6">
            <div class="contact-info d-flex align-items-center">
              <a class="navbar-brand" href="#">
                <img src="../images/logo_title.png" alt="Logo" id="imageLogo">
              </a>
            </div>
          </div>
          <div class="col-md-6 text-end">
            <div class="registration text-right">
              <a href="#" class="main-color">Регистрация</a>
              <button class=" btn rss-sub main-color">
                <i class="login-icon fa-solid fa-right-to-bracket main-color"></i>Вход </button>
              <button class=" btn rss-sub main-color">
                <i class="login-icon fas fa-search main-color me-0"></i>
              </button>
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
            <li class="nav-item ">
              <a class="nav-link  " aria-current="page" href="#">
                <i class="bi bi-house-door-fill text-light"></i>
              </a>
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
              <a class="nav-link" href="#">
                <i class="fa-brands fa-facebook text-light"></i>
              </a>
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
              <div class="page-heading">
                <h1> Проект на Наредба за изменение и допълнение на Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни товари </h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section>
        <div class="container-fluid">
            <div class="row edit-consultation m-0">
                <div class="col-md-12 text-end">
                    <button class="btn rss-sub main-color mt-2">
                        <i class="fas fa-pen me-2 main-color"></i>Редактиране на консултация</button>
                </div>
            </div>
        </div>
    </section>

    <section class="public-page">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-4 ">
            <h3 class="mb-3 fs-5">Откриване / Приключване <a href="#short-term" class="text-decoration-none">
                <i class="fa-solid fa-triangle-exclamation text-danger fs-5" title="По-кратък срок"></i>
            </h3>
            </a>
            <span class="obj-icon-info fs-18">
              <i class="far fa-calendar me-2 main-color" title="Дата на откриване"></i>28.8.2023 г. </span>
            <span class="mx-2 fs-18"> — </span>
            <span class="obj-icon-info fs-18">
              <i class="far fa-calendar-check me-2 main-color" title="Дата на приключване"></i>10.09.2023 г. </span>
          </div>
          <div class="col-md-4 ">
            <h3 class="mb-3 fs-5">Номер на консултация</h3>
            <a href="#" class="main-color text-decoration-none fs-18">
              <span class="obj-icon-info me-2">
                <i class="fas fa-hashtag me-2 main-color" title="Номер на консултация "></i>1261313 </span>
            </a>
          </div>
          <div class="col-md-4 ">
            <h3 class="mb-3 fs-5">Сфера на действие</h3>
            <a href="#" class="main-color text-decoration-none fs-18">
              <span class="obj-icon-info me-2">
                <i class="fas fa-car me-2 main-color" title="Сфера на действие"></i>Транспорт </span>
            </a>
          </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-4 ">
            <h3 class="mb-3 fs-5">Тип консултация</h3>
            <a href="#" class="main-color text-decoration-none fs-18">
              <span class="obj-icon-info me-2">
                <i class="fas fa-file-lines me-2 main-color" title="Тип консултация"></i>Закон </span>
            </a>
          </div>
          <div class="col-md-4 ">
            <h3 class="mb-3 fs-5">Тип вносител</h3>
            <a href="#" class="main-color text-decoration-none fs-18">
              <span class="obj-icon-info me-2">
                <i class="fa-solid fa-arrow-right-from-bracket me-2 main-color" title="Вносител"></i>Централно ниво </span>
            </a>
          </div>
          <div class="col-md-4 ">
            <h3 class="mb-3 fs-5">Предишна версия</h3>
            <a href="#" class="main-color text-decoration-none fs-18">
              <span class="obj-icon-info me-2">
                <i class="fa-solid fa-code-compare me-2 main-color" title="История"></i>Версия 1.1 </span>
            </a>
          </div>
        </div>

        <hr class="custom-hr">

        <div class="row mb-1 mt-5">
          <div class="col-md-8">
            <h2 class="mb-3">Информация</h2>
          </div>
          <div class="col-md-4 text-end">
            <button class="btn btn-primary  main-color">
              <i class="fa-solid fa-download main-color me-2"></i>Експорт </button>
            <button class="btn rss-sub main-color">
              <i class="fas fa-square-rss text-warning me-2"></i>RSS </button>
            <button class="btn rss-sub main-color">
              <i class="fas fa-envelope me-2 main-color"></i>Абониране </button>
          </div>
        </div>

        <div class="row mt-3 mb-4">
          <div class="col-md-12">
            <p> С проекта на наредба се предлагат: I. Промени, свързани с въвеждането в националното законодателство на Делегирана директива (ЕС) 2022/2407 на Комисията от 20 септември 2022 година за изменение на приложенията към Директива 2008/68/ЕО на Европейския парламент и на Съвета с оглед на адаптирането към научно-техническия прогрес (ОВ L 317, 09/12/2022) (Делегирана директива (ЕС) 2022/2407). Директива 2008/68/ЕО относно вътрешния превоз на опасни товари (2008/68/ЕО) е въведена в националното законодателство с Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни товари. Съгласно чл. 8, параграф 1 от Директива 2008/68/ЕО, Европейската комисия приема делегирани актове за изменение на приложенията на директивата с цел отчитане на измененията на ADR, RID и ADN, по-специално свързаните с научния и техническия прогрес, включително използването на технологии за локализиране и проследяване. Приетите въз основа на цитирания текст актове, въвеждащи изменения на приложенията към Директива 2008/68/ЕО, са въведени в Наредба № 46, с изключение на Делегирана директива (ЕС) 2022/2407. Текстът, който следва да се транспонира от посочената делегирана директива се съдържа в чл. 1, т. 2 от нея и гласи: „в приложение II раздел II.1 се заменя със следното: „II.1. RID </p>
            <p>
              <strong>Приложението към RID, приложимо от 1 януари 2023 г., като се разбира, че където е уместно „RID договаряща държава" се заменя с „държава членка“.</strong>
            </p>
            <p> Подобен текст вече е транспониран и се съдържа в чл. 2, ал. 1 от Наредба № 46. За да се транспонира Делегирана директива (ЕС) 2022/2407 е необходимо да се добави заглавието й в края на § 2 от Заключителните разпоредби на действащата Наредба № 46, което се предлага в параграф единствен от проекта на наредба. Предложеният проект на Наредба за изменение и допълнение на Наредба № 46 не оказва пряко/или косвено въздействие върху държавния бюджет. Не са необходими финансови и други средства за прилагането на новата уредба. На основание чл. 26, ал. 2-4 от Закона за нормативните актове проектът на наредба, заедно с доклада към него, е публикуван за обществено обсъждане на страницата на Министерството на транспорта, информационните технологии и съобщенията и на Портала за обществени консултации на Министерски съвет. На заинтересованите лица е предоставена възможност да се запознаят с проекта на Наредба за изменение и допълнение на Наредба № 46 и да представят писмени предложения или становища в 14-дневен срок от публикуването им. </p>
            <p>
              <strong> Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект на наредба включва разпоредби, които имат технически характер и чрез тях се въвежда изискването на чл. 1, параграф 2 от Делегирана директива (ЕС) 2022/2407, който регламентира актуалната редакция на правилата към RID – приложима от 1 януари 2023 г., която вече е в сила за Република България. </strong>
            </p>
            <p> В изпълнение на изискванията на чл. 3, ал. 4, т. 1 от Постановление № 85 на Министерския съвет от 2007 г. за координация по въпросите на Европейския съюз (обн., ДВ, бр. 35 от 2007 г., изм., бр. 53 и 64 от 2008 г., бр. 34, 71, 78 и 83 от 2009 г., бр. 4, 5, 19 и 65 от 2010 г., попр., бр. 66 от 2010 г., изм., бр. 2 и 105 от 2011 г., доп., бр. 68 от 2012 г., изм., бр. 62, 65 и 80 от 2013 г., изм. и доп., бр. 53 от 2014 г., изм., бр. 76 от 2014 г., изм. и доп., бр. 94 от 2014 г., изм., бр. 101 от 2014 г., изм. и доп., бр. 6 от 2015 г., изм., бр. 36 от 2016 г., изм. и доп., бр. 79 от 2016 г., изм., бр. 7 и 12 от 2017 г., изм. и доп., бр. 39 от 2017 г., бр. 3 от 2019 г., изм., бр. 41 от 2021 г.) е изготвена таблица за съответствие с Делегирана директива (ЕС) 2022/2407. Проектът на наредба е съгласуван в рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на работната група. </p>
          </div>
        </div>

        <div class="row mb-4 mt-4">
          <h3 class="mb-3">Лице за контакт</h3>
          <p> Мария Стефанова <br> Главен секретар <br> ИА „Железопътна администрация" <br> Тел.: <a href="tel:359884 101 581" class="main-color">02/9409 378</a>, Мобилен: <a href="tel:359884 101 581" class="main-color">+359 884 101 581</a>
            <br> Е-mail: <a href="mailto:mstefanova@iaja.bg " class="main-color">mstefanova@iaja.bg </a>
            <br>
          </p>
        </div>

        <div class="row mb-4 mt-4" id="short-term">
          <h3 class="mb-3">Мотиви за кратък срок</h3>
          <p> Предоставяне на мотиви за кратък срок (по-малко от 30 дни). </p>
        </div>

        <div class="row mb-4 mt-4">
          <h3 class="mb-3">Полезни линкове</h3>
          <div class="col-md-12">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <a href="#" class="main-color text-decoration-none">
                  <i class="fas fa-regular fa-link  main-color me-2 fs-5"></i>
                  <a href="#" class="main-color text-decoration-none">Полезен линк 1</a>
                </a>
              </li>
              <li class="list-group-item">
                <a href="#" class="main-color text-decoration-none">
                  <i class=" fas fa-regular fa-link main-color me-2 fs-5"></i>
                  <a href="#" class="main-color text-decoration-none">Полезен линк 2</a>
                </a>
              </li>
              <li class="list-group-item">
                <a href="#" class="main-color text-decoration-none">
                  <i class="fas fa-regular fa-link main-color me-2 fs-5"></i>
                  <a href="#" class="main-color text-decoration-none">Полезен линк 3</a>
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="row mb-5 mt-5">
          <div class="col-md-12">
            <div class="custom-card py-4 px-3">
              <h3 class="mb-3">Коментари</h3>
              <div class="obj-comment comment-background p-2 rounded mb-3">
                <div class="info">
                  <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                    <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Ivanov </span>
                  <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                </div>
                <div class="comment rounded py-2">
                  <p class="mb-0">Проектът на наредба е съгласуван в рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на работната група.</p>
                </div>
              </div>
              <div class="obj-comment comment-background p-2 rounded mb-3">
                <div class="info">
                  <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                    <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Иванов </span>
                  <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                </div>
                <div class="comment rounded py-2">
                  <p class="mb-0">Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект на наредба включва разпоредби</p>
                </div>
              </div>
              <div class="col-md-12 mt-4">
                <div>
                  <textarea class="form-control mb-3 rounded" id="exampleFormControlTextarea1" rows="2" placeholder="Въведете коментар"></textarea>
                  <button class=" cstm-btn btn btn-primary login m-0">Добавяне на коментар</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr class="custom-hr">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="hori-timeline" dir="ltr">
              <h3 class="mb-3">Досие на актовете на Министерски съвет</h3>
              <div class="timeline">
                <ul class="timeline events">
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold">Включване на проекта на акт в законодателната или оперативната програма на Министерския съвет</h5>
                    <p class="mb-2 fw-bold">12.05.2023</p>
                    <p> Tова събитие описва запис на акт в ЗП или ОП (вж. предишните секции) с линк към тях. Ако актът е закон, той е в ЗП, ако е подзаконов акт на МС, е в ОП. Ако е друг, това събитие е неприложимо. </p>
                  </li>
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold">Начало на обществената консултация</h5>
                    <p class="mb-2 fw-bold">20.05.2023</p>
                    <p> Визуализира се „Начало на консултацията“. Това събитие е задължително при създаването на всяка консултация (вж. 8.2.1.1.3.3. за обществените консултации). То е еднократно и в Timeline-а се показва дата и час на неговото създаване. </p>
                  </li>
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold">Промяна на файл (само на файл) от обществената консултация</h5>
                    <p class="mb-2 fw-bold">25.05.2023</p>
                    <p> Визуализира се „Промяна на файл от консултацията“ с дата, час и списък от променените или добавени файлове. Това събитие не е задължително, тъй като промени в записите от консултацията по правило не е редно да се случват. </p>
                  </li>
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold text-muted">Приключване на консултацията</h5>
                    <p class="text-muted mb-2 fw-bold ">01.06.2023</p>
                    <p class="text-muted"> Визуализира се „Край на консултацията“. Това е еднократно и необратимо събитие. Срокът е съобразно зададения при създаването на консултацията срок (вж. 8.2.1.1.3.3). или неговото удължаване. </p>
                  </li>
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold text-muted">Публикуване на справка за получените предложения или на съобщение за неполучени предложения</h5>
                    <p class="text-muted mb-2 fw-bold">15.06.2023</p>
                    <p class="text-muted"> Според това дали има постъпили или не становища в рамките на консултацията, се публикува справка или съобщение. Коя от двете опции се визуализира зависи от записа, който е избрал модераторът в административната част. </p>
                  </li>
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold text-muted">Приемане на акта от Министерския съвет</h5>
                    <p class="text-muted mb-2 fw-bold text-muted">18.06.2023</p>
                    <p class="text-muted"> Визуализира се „Окончателен акт“. Събитието следва да се появи автоматично в Timeline-а след като е създаден запис в раздел „Правна информация на Министерския съвет“. </p>
                  </li>
                  <li class="timeline-item mb-5">
                    <h5 class="fw-bold text-muted"> Представяне на законопроекта в страницата на Народното събрание</h5>
                    <p class="text-muted mb-2 fw-bold ">25.06.2023</p>
                    <p class="text-muted"> Това събитие ще бъде развито в обхвата на текущата поръчка, само ако Възложителят потвърди, че е организирана съответната координация със системата на Народното събрание. </p>
                  </li>
                </ul>
              </div>
            </div>
          </div>
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
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Начало</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="p-0 text-light">» Новини</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Публикации</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Мнения</a>
            </li>
          </ul>
        </div>
        <div class="col-6 col-md-2 mb-3">
          <h5 class="text-light">Информация</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» За нас</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Карта на сайта</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Условия за ползване</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Често задавани въпроси</a>
            </li>
          </ul>
        </div>
        <div class="col-6 col-md-2 mb-3">
          <h5 class="text-light">Контакти</h5>
          <ul class="nav flex-column footer-nav">
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Адрес</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Телефон</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Имейл</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class=" p-0 text-light">» Фейсбук</a>
            </li>
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