@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Профил на Потребител')

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
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Профил на {Потребител}
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                              <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Обща информация</a>
                              </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Абонаменти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Законодателни инициативи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценки на въздействието</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Публикувани коментари</a>
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
            <h2 class="mb-4">Основна информация</h2>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-user main-color me-1"></i>Име
            </div>

            <div class="col-md-9 pris-left-column">
            <span>Иван Иванов Иванов</span>
            </div>
        </div>
 
        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-envelope main-color me-1"></i>Електронна поща
            </div>

            <div class="col-md-9 pris-left-column">
                <a href="tel:+359888123123" class="text-decoration-none">ivan_ivanov@gmail.com</a>
            </div>
        </div>

        <div class="row pris-row pb-2 mb-2">
            <div class="col-md-3 pris-left-column">
                <i class="fa-solid fa-lock main-color me-1"></i>Парола
            </div>

            <div class="col-md-9 pris-left-column">
                <span>***********</span>
            </div>
        </div>

        <div class="row action-btn-wrapper mt-5">
            <div class="col-md-6">
                <button class="btn btn-primary d-inline">
                    <i class="fa-solid fa-pen me-1"></i>Редактиране на профил
                </button>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-danger d-inline">
                    <i class="fa-solid fa-trash me-1"></i>Изтриване на профил
                </button>
            </div>
        </div>
    </div>
</div>
</body>


@endsection
