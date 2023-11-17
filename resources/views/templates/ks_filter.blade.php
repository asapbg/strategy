<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Портал за обществени консултации</title>


    <link href="{{ asset('/vendor/bootstrap/bootstrap.css') }}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <!-- Font-awesome -->
    <link href="/vendor/fontawesome-free-6.4.0-web/css/all.css" rel="stylesheet">

    <!-- Custom css -->
    <link href="{{ asset('css/app_vendor.css') }}" rel="stylesheet">

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

  .active-ks {
  color:#fff !important;
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
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
                <a href="#">Начало</a> »
              </div>
              <div class="page-heading">
                <h1>
                  Консултативни съвети
                </h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="public-page">
      <div class="container">

        <div class="row filter-results mb-2">
          <h2 class="mb-4">
              Търсене
          </h2>

          <div class="row">
            <div class="col-md-4">
              <div class="input-group ">
                  <div class="mb-3 d-flex flex-column  w-100">
                      <label for="exampleFormControlInput1" class="form-label">Области на политика</label>
                      <select class="form-select">
                          <option value="1">--</option>
                          <option value="1">Финанси и данъчна политика</option>
                          <option value="1">Партньорство за открито управление</option>
                          <option value="1">Енергетика</option>
                          <option value="1">Защита на потребителите</option>
                      </select>
                  </div>
              </div>
          </div>

          <div class="col-md-4">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Вид орган</label>
                    <select class="form-select">
                      <option value="1">--</option>
                        <option value="1">Министерски съвет</option>
                        <option value="1">Министър-председател</option>
                        <option value="1">Министър</option>
                        <option value="1">Държавна агенция</option>
                        <option value="1">Друг централен орган</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-4">
          <div class="input-group ">
              <div class="mb-3 d-flex flex-column  w-100">
                  <label for="exampleFormControlInput1" class="form-label">Акт на създаване</label>
                  <select class="form-select">
                      <option value="1">--</option>
                      <option value="2">Закон</option>
                      <option value="3">Постановление на Министерския съвет (на основание чл. 21 от Закона за администрацията)</option>
                      <option value="4">Заповед на председател на държавна агенция (на основание, чл. 47, ал. 8 от Закона за администрацията)</option>
                      <option value="5">Акт на друг централен орган</option>
                  </select>
              </div>
          </div>
      </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="input-group ">
                  <div class="mb-3 d-flex flex-column  w-100">
                      <label for="exampleFormControlInput1" class="form-label">Председател на съвета</label>
                      <select class="form-select">
                          <option value="1">--</option>
                          <option value="2">Министър-председател</option>
                          <option value="3">Заместник министър-председател</option>
                          <option value="4">Министър</option>
                          <option value="5">Председател на държавна агенция</option>
                          <option value="6">Друго свободно добавяне в номенклатурата</option>
                      </select>
                  </div>
              </div>
          </div>

          <div class="col-md-4">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Наличие на представител на НПО</label>
                    <select class="form-select">
                      <option value="1">--</option>
                      <option value="Да">Да</option>
                      <option value="Не">Не</option>

                  </select>
                </div>
            </div>
        </div>

        <div class="col-md-4">
          <div class="input-group ">
              <div class="mb-3 d-flex flex-column  w-100">
                  <label for="exampleFormControlInput1" class="form-label">Статус</label>
                  <select class="form-select">
                      <option value="1">--</option>
                      <option value="1">Активен</option>
                      <option value="1">Неактивен</option>
                  </select>
              </div>
          </div>
      </div>
          </div>

          <div class="row mb-5">
            <div class="col-md-4">
                <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
            </div>
            <div class="col-md-8 text-end">
                <button class="btn btn-primary main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS Абониране</button>
                <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                <button class="btn btn-primary main-color"><i class="fa-solid fa-download main-color me-1"></i>Експорт</button>
                <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
    
            </div>
        </div>

          <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-2">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Категория
                </p>
            </div>
            <div class="col-md-2 cursor-pointer ">
                <p class="mb-0">
                    <i class="fa-solid fa-sort me-2"></i>Заглавие
                </p>
            </div>
    
    
            <div class="col-md-2">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Вносител
                </p>
            </div>
            <div class="col-md-2">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Номер
                </p>
            </div>
            <div class="col-md-2">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Термини
            </div>
            <div class="col-md-2">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Дата
                </p>
            </div>
        </div>


        <div class="row mb-2">
          <div class="col-12 mt-2">
              <div class="info-consul text-start">
                  <p class="fw-600">
                      Общо 98 резултата
                  </p>
              </div>
          </div>
      </div>

        <div class="row mb-3">
          <div class="col-md-12">
              <div class="consul-wrapper">
                  <div class="single-consultation d-flex">
                      <div class="consult-body">
                          <a href="#" class="consul-item">
                              </a><p class="mb-1"><a href="#" class="consul-item">
                                  </a><a href="#" class="main-color text-decoration-none fs-5">Висш експертен екологичен съвет</a>
                              </p>
                              <div class="meta-consul">
                                <span>Статус: <span class="active-ks">Активен</span></span>
                                  <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                              </div>


                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-body">
                        <a href="#" class="consul-item">
                            </a><p class="mb-1"><a href="#" class="consul-item">
                                </a><a href="#" class="main-color text-decoration-none fs-5">Висш консултативен съвет по водите</a>
                            </p>
                            <div class="meta-consul">
                              <span>Статус: <span class="active-ks">Активен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                            </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-12">
          <div class="consul-wrapper">
              <div class="single-consultation d-flex">
                  <div class="consult-body">
                      <a href="#" class="consul-item">
                          </a><p class="mb-1"><a href="#" class="consul-item">
                              </a><a href="#" class="main-color text-decoration-none fs-5">Консултативeн съвет за сътрудничество между държавните органи и лицата, осъществяващи икономически дейности, свързани с нефт и продукти от нефтен произход		</a>
                          </p>
                          <div class="meta-consul">
                            <span>Статус: <span class="active-ks">Активен</span></span>
                              <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                          </div>


                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
        <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-body">
                    <a href="#" class="consul-item">
                        </a><p class="mb-1"><a href="#" class="consul-item">
                            </a><a href="#" class="main-color text-decoration-none fs-5">Консултативен съвет за насърчаване на малките и средните предприятия</a>
                        </p>
                        <div class="meta-consul">
                          <span>Статус: <span class="active-ks">Активен</span></span>
                            <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                        </div>


                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
        <div class="consul-wrapper">
            <div class="single-consultation d-flex">
                <div class="consult-body">
                    <a href="#" class="consul-item">
                        </a><p class="mb-1"><a href="#" class="consul-item">
                            </a><a href="#" class="main-color text-decoration-none fs-5">Съвет за развитие</a>
                        </p>
                        <div class="meta-consul">
                          <span>Статус: <span class="inactive-ks">Неактивен</span></span>
                            <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                        </div>


                </div>
            </div>
        </div>
    </div>
  </div>

      <div class="row mb-3">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-body">
                        <a href="#" class="consul-item">
                            </a><p class="mb-1"><a href="#" class="consul-item">
                                </a><a href="#" class="main-color text-decoration-none fs-5">Съвет по децентрализация на държавното управление</a>
                            </p>
                            <div class="meta-consul">
                              <span>Статус: <span class="inactive-ks">Неактивен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                            </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


      </div>
    </div></section>



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







  </body></html>
