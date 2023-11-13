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
      <div class="container-fluid">
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
      <div class="container-fluid">
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="slider-content">
              <div class="page-heading">
                <h1> Списък на физическите и юридическите лица</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

<section>
  <div class="container-fluid">
    <div class="row breadcrumbs py-1">
      <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#">Начало</a></li>
            <li class="breadcrumb-item"><a href="#">Списък на физическите и юридическите лица
            </a></li>
          </ol>
        </ol>
    </nav>
    </div>
</section>

<div class="container-fluid">
    <div class="row edit-consultation m-0">
        <div class="col-md-12 text-end">
            <button class="btn btn-sm btn-primary main-color mt-2">
                <i class="fas fa-pen me-2 main-color"></i>Редактиране</button>
        </div>
    </div>
</div>

<section class="container-fluid">
    <div class="row mt-4">
        <div class="col-md-12">
            <p class="fs-18 fw-600 m-0">
                Списъкът се изготвя в изпълнение на § 1 от Допълнителните разпоредби на Закона за нормативните актове.
            </p>
        </div>
    </div>
    <hr>
    <div class="row filter-results mb-2">
        <h2 class="mb-4">
            Търсене
        </h2>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Изпълнител</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Предмет на договора</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Възложител</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Кратко описание</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <label for="exampleFormControlInput1" class="form-label">Начална дата:</label>
            <div class="input-group">
                <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
            </div>
        </div>



        <div class="col-md-3">
            <label for="exampleFormControlInput1" class="form-label">Крайна дата:</label>
            <div class="input-group">
                <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Брой резултати </label>
                    <select class="form-select" aria-label="Default select example">
                        <option value="1">5</option>
                        <option value="1">10</option>
                        <option value="1">50</option>
                        <option value="1">100</option>
                        <option value="1">150</option></option>
                        <option value="1">200</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Цена</label>
                    <span class="small">Над 1500лв.</span>
                    <input type="range" class="form-range" min="0" max="1000" step="0" id="customRange3">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn rss-sub main-color"><i class="fas fa-square-rss text-warning"></i>RSS Абониране</button>
            <button class="btn rss-sub main-color"><i class="fas fa-envelope"></i>Абониране</button>
        </div>
    </div>

<div class="row pt-4 pb-2 px-2">
    <div class="col-md-12">
            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2">
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>   Наименование на възложител	
                    </p>
                </div>
                <div class="col-md-2 cursor-pointer ">
                    <p class="mb-0">
                        <i class="fa-solid fa-sort"></i>  Наименование на изпълнител	
                    </p>
                </div>
                <div class="col-md-1">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  ЕИК <br>(за юридически лица)	
                    </p>
                </div>
                <div class="col-md-1">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Дата на договора	
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Предмет на договора	
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Кратко описание на извършените услуги	
                    </p>
                </div>
                <div class="col-md-1">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i>  Цена на договора (в лв. с ДДС)
                    </p>
                </div>
            </div>

      
   </div>
</div>  

<div class="row">
  <div class="col-12 mt-1 mb-2">
    <div class="info-consul text-start">
        <p class="fw-600">
            Общо 114 резултата
        </p>
    </div>
</div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="custom-card pt-1 pb-4 px-3 mb-3">
        <div class="row m-0">
            <div class="col-md-12 text-end p-0">
                   <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                  </a>
                  <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>

            </div>
        </div>
        <div class="row single-record">
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">1. Министерство на регионалното развитие и благоустройството	</a>
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"	</a> 	
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    <a href="#" class="main-color text-decoration-none">177282392	</a>
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    23.08.2018 г.	
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    Изготвяне на проект на нормативен акт - Наредба за сервитутите на водоснабдителните и канализационните проводи, мрежи и съоръжения		
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    22 008 лв.
                </p>
            </div>
            <div class="col-md-12">
              <p class="mb-0 text-end">
                <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
            </p>
          </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="custom-card pt-1 pb-4 px-3 mb-3">
            <div class="row m-0">
                <div class="col-md-12 text-end p-0">
                  <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                  </a>
                  <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>
                </div>
            </div>
            <div class="row single-record">
                <div class="col-md-2">
                    <p>
                        <a href="#" class="main-color text-decoration-none">2. Министерство на регионалното развитие и благоустройството</a> 
                    </p>
                </div>
                <div class="col-md-2">
                    <p>
                        <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"</a> 
                    </p>
                </div>
                <div class="col-md-1">
                    <p>
                        <a href="#" class="main-color text-decoration-none">177282392	</a>
                    </p>
                </div>
                <div class="col-md-1">
                    <p>
                        23.08.2018 г.	
                    </p>
                </div>
                <div class="col-md-3">
                    <p>
                        Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                    </p>
                </div>
                <div class="col-md-2">
                    <p>
                        Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи	                </p>
                </div>
                <div class="col-md-1">
                    <p>
                        27 678 лв.
                    </p>
                </div>
                <div class="col-md-12">
                  <p class="mb-0 text-end">
                    <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
                </p>
              </div>
            </div>
        </div>
</div>




<div class="col-md-12">
    <div class="custom-card pt-1 pb-4 px-3 mb-3">
        <div class="row m-0">
            <div class="col-md-12 text-end p-0">
              <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
              </a>
              <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>
            </div>
        </div>
        <div class="row single-record">
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">3. Министерство на регионалното развитие и благоустройството</a> 
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"		</a> 
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    <a href="#" class="main-color text-decoration-none">177282392	</a>
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    23.08.2018 г.	
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи	                </p>
            </div>
            <div class="col-md-1">
                <p>
                    27 678 лв.
                </p>
            </div>
            <div class="col-md-12">
              <p class="mb-0 text-end">
                <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
            </p>
          </div>
        </div>
    </div>
</div>



<div class="col-md-12">
    <div class="custom-card pt-1 pb-4 px-3 mb-3">
        <div class="row m-0">
            <div class="col-md-12 text-end p-0">
              <a href="#"><i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
              </a>
              <a href="#" title="Редактиране"> <i class="fas fa-pen-to-square float-end main-color fs-4"></i></a>
            </div>
        </div>
        <div class="row single-record">
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">4. Министерство на регионалното развитие и благоустройството</a> 
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a href="#" class="main-color text-decoration-none">ДЗЗД "Глобал Аквуекон"		</a> 
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    <a href="#" class="main-color text-decoration-none">177282392	</a>
                </p>
            </div>
            <div class="col-md-1">
                <p>
                    23.08.2018 г.	
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    Консултантски услуги за изготвяне на становища, анализи, договори, документи, предложения за промени в приложимата нормативна уредба, свързани със стопанисване, поддържане и експлоатация на ВиК системите и съоръженията и предоставяне на ВиК услугите за Обособена позиция 5: оказване на правно-консултантска и техническа подкрепа на МРРБ за изготвяне на становища, анализи, документи и предложения за промени в приложимата нормативна уредба, свързани с развитието и управлението на ВиК отрасъла	
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    Изготвяне на проект на нормативен акт - Наредба за изменение и допълнение на Наредба № 4 от 2004 г. за условията и реда за присъединяване на потребителите и за ползване на водоснабдителните и канализационните системи	                </p>
            </div>
            <div class="col-md-1">
                <p>
                    27 678 лв.
                </p>
            </div>
            <div class="col-md-12">
              <p class="mb-0 text-end">
                <strong>Информация за поръчката:</strong> <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
            </p>
          </div>
        </div>
    </div>
</div>


</div>

    <div class="row mb-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination m-0">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">«</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link" href="#">57</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">»</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
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
</html>