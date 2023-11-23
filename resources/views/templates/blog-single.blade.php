@extends('layouts.site', ['fullwidth' => true])
<style>
     .public-page {
        padding: 0px 0px !important;
    }
</style>
@section('pageTitle', 'Новини')

@section('content')

<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Библиотека
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2 p-1"><a href="#"
                                        class="link-dark text-decoration-none">Новини</a>
                                </li>
                                <li class="mb-2 p-1 active-item-left"><a href="#" class="link-dark text-decoration-none">Публикации</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 py-5">
        <h2 class="obj-title mb-4">Удостоверение за наследници няма да се изисква пред Службите по кадастър, НОИ и Агенцията по
            горите</h2>
        <div class="row">
            <div class="col-md-8">
                <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i class="far fa-calendar me-1 dark-blue"
                            title="Дата на публикуване"></i>12.7.2023 г.</span>
                </a>
                <a href="#" class="text-decoration-none">
                    <span class="obj-icon-info me-2"><i class="fas fa-sitemap me-1 dark-blue"
                            title="Област на политика"></i>Държавна администрация</span>

                </a>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на публикация
                </button>
                <button class="btn btn-sm btn-danger">
                    <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>Изтриване на публикация
                </button>
            </div>
        </div>
        <hr>
        <div>
            Удостоверение за наследници няма да се изисква от гражданите от Службите по геодезия, картография и кадастър,
            Националния осигурителен институт (НОИ) и Изпълнителна агенция по горите. Администрациите ще си набавят по служебен
            път необходимата им информация чрез реализираната от Министерство на електронното управление (МЕУ) нова вътрешна
            услуга. Тя се предоставя през Системата за сигурно електронно връчване на МЕУ.<br>
            Новата услуга е създадена в изпълнение на Закона за електронно управление и цели намаляване на административната
            тежест за гражданите и бизнеса.<br>
            Източник: <a href="">Министерство на електронното управление</a>
        </div>
        <a class="btn btn-primary mt-4 mb-5" href="#">Обратно към списъка с новини</a>
    </div>
</div>
@endsection
