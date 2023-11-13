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

.subscribe-row{
  border-bottom: 1px solid #c0dbf2;
}

.btn-primary {
  --bs-btn-bg: #20659e;
}


.btn-check:checked + .btn, :not(.btn-check) + .btn:active, .btn:first-child:active, .btn.active, .btn.show{
  color: #fff;
  background-color: #20659e;
  border-color: #20659e;
}

.btn.btn-labeled.bgr-main.rounded {
  background-color: #20659e;
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
            <div class="page-heading">
              <h1>
                Абонаменти
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
            Управление на абонаменти
        </h2>
      </div>
      <div class="row subscribe-row pb-2 mb-2 align-items-center">
          <div class="col-md-9">
            <h3 class="fs-5 mb-0 fw-normal">
              Обществени консултации
          </h3>
          </div>
          <div class="col-md-3 subscribe-action text-end">
            <button type="button" class="btn btn-labeled  bgr-main rounded">
              <i class="fa-solid fa-pen-to-square text-light"></i>
            </button>

           <button type="button" class="btn btn-labeled btn-warning">
             <i class="fa-solid fa-ban text-light"></i>
           </button>

           <button type="button" class="btn btn-labeled btn-danger">
            <i class="fa fa-trash text-light"></i>
           </button>
          </div>
        </div>

      <div class="row subscribe-row pb-2 mb-2 align-items-center">
        <div class="col-md-9">
          <h3 class="fs-5 mb-0 fw-normal">
            Стратегически документи
        </h3>
        </div>
        <div class="col-md-3 subscribe-action text-end">
          <button type="button" class="btn btn-labeled  bgr-main rounded">
            <i class="fa-solid fa-pen-to-square text-light"></i>
          </button>

         <button type="button" class="btn btn-labeled btn-warning">
           <i class="fa-solid fa-ban text-light"></i>
         </button>

         <button type="button" class="btn btn-labeled btn-danger">
          <i class="fa fa-trash text-light"></i>
         </button>
        </div>
    </div>

    <div class="row subscribe-row pb-2 mb-2 align-items-center">
      <div class="col-md-9">
        <h3 class="fs-5 mb-0 fw-normal">
          Kонсултативни съвети
      </h3>
      </div>
      <div class="col-md-3 subscribe-action text-end">
        <button type="button" class="btn btn-labeled  bgr-main rounded">
          <i class="fa-solid fa-pen-to-square text-light"></i>
        </button>

       <button type="button" class="btn btn-labeled btn-warning">
         <i class="fa-solid fa-ban text-light"></i>
       </button>

       <button type="button" class="btn btn-labeled btn-danger">
        <i class="fa fa-trash text-light"></i>
       </button>
      </div>
  </div>

  <div class="row subscribe-row pb-2 mb-2 align-items-center">
    <div class="col-md-9">
      <h3 class="fs-5 mb-0 fw-normal">
       Новини
    </h3>
    </div>
    <div class="col-md-3 subscribe-action text-end">
      <button type="button" class="btn btn-labeled  bgr-main rounded">
        <i class="fa-solid fa-pen-to-square text-light"></i>
      </button>

     <button type="button" class="btn btn-labeled btn-warning">
       <i class="fa-solid fa-ban text-light"></i>
     </button>

     <button type="button" class="btn btn-labeled btn-danger">
      <i class="fa fa-trash text-light"></i>
     </button>
    </div>
</div>

<div class="row subscribe-row pb-2 mb-2 align-items-center">
  <div class="col-md-9">
    <h3 class="fs-5 mb-0 fw-normal">
     Законодателни инициативи
  </h3>
  </div>
  <div class="col-md-3 subscribe-action text-end">
    <button type="button" class="btn btn-labeled  bgr-main rounded">
      <i class="fa-solid fa-pen-to-square text-light"></i>
    </button>

   <button type="button" class="btn btn-labeled btn-warning">
     <i class="fa-solid fa-ban text-light"></i>
   </button>

   <button type="button" class="btn btn-labeled btn-danger">
    <i class="fa fa-trash text-light"></i>
   </button>
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

