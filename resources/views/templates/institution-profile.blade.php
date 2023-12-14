@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Профил на {Име на институция}')

@section('content')
<div class="row mt-5">

 {слайдер} <br> 
 {пътечки}
</div>
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Профил на {Име на институция}
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                              <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Данни за
                                институцията в ИИСДА</a>
                              </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Обществени
                                        консултации</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Стратегически
                                        документи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Законодателни
                                        програми</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">ПРИС</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Модератори</a>
                                </li>        
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 right-side-content py-5 ">


        <div class="col-md-12">
            <h2 class="mb-2">Основна информация</h2>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-hashtag main-color me-1"></i>ЕИК
            </div>

            <div class="col-md-9 pris-left-column">
            <span>129009984</span>
            </div>
        </div>
        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-arrow-right-from-bracket main-color me-1"></i>Раздел
            </div>

            <div class="col-md-9 pris-left-column">
                <a href="#" class="text-decoration-none">Други</a>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-circle-check main-color me-1"></i>Статус
            </div>

            <div class="col-md-9 pris-left-column">
                <a href="#"><span class="pris-tag-active">Активен</span></a>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-copyright main-color me-1"></i>Наименование
            </div>

            <div class="col-md-9 pris-left-column">
                {Име профил на институция}
            </div>
        </div>


        <div class="col-md-12 mt-5">
            <h2 class="mb-2">Адрес за кореспонденция</h2>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-city main-color me-1"></i>Област
            </div>

            <div class="col-md-9 pris-left-column">
               <span>Хасково</span>
            </div>
        </div>
        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-city main-color me-1"></i>Община
            </div>

            <div class="col-md-9 pris-left-column">
                <span>Хасково</span>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-house main-color me-1"></i>Населено място
            </div>

            <div class="col-md-9 pris-left-column">
                <span>{населено място}</span>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-location-dot main-color me-1"></i>Адрес
            </div>

            <div class="col-md-9 pris-left-column">
                бул. "България" №85
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-inbox main-color me-1"></i>Пощенски код
            </div>

            <div class="col-md-9 pris-left-column">
                6300
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-phone main-color me-1"></i>Телефон
            </div>

            <div class="col-md-9 pris-left-column">
                <a href="tel:+359888123123" class="text-decoration-none">+359 888123123</a>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-fax main-color me-1"></i>Факс
            </div>

            <div class="col-md-9 pris-left-column">
                <a href="fax:+359888123127" class="text-decoration-none">+359888123127</a>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-envelope main-color me-1"></i>Електронна поща
            </div>

            <div class="col-md-9 pris-left-column">
                <a href="tel:+359888123123" class="text-decoration-none">haskovo@mvr.bg</a>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-circle-info main-color me-1"></i>Допълнителна информация
            </div>

            <div class="col-md-9 pris-left-column">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>


    </div>
</div>
</body>


@endsection
