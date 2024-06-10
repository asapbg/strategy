@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'OGP Forum')



@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Партньорство за открито управление
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Национални планове
                                        за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценка за
                                        изпълнението на плановете за действие - мониторинг</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Разработване на нов
                                        план за действие</a>
                                </li>
                                <li class="mb-2 active-item-left p-1"><a href="#"
                                        class="link-dark text-decoration-none">OGP FORUM</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини и събития</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
                <img src="/img/ogp-img.png" class="img-fluid rounded mt-2" alt="OGP">
            </div>
        </div>

    </div>


    <div class="col-lg-10 py-5 right-side-content">

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation">
                        <div class="forum-header-edit">
                            <a href="#">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                            </a>
                            <a href="#">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                </i>
                            </a>
                        </div>
                        <div class="forum-item-body p-3">
                            <div href="#" class="consul-item row align-items-center">

                                <div class="col-md-6 col-sm-12">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                            <h3>Правомощия, органи и структура 1
                                            </h3>
                                        </a>
                                        <span class="text-secondary"> Публикувано на </span>
                                        <span >
                                            <i class="far fa-calendar text-secondary  ms-1"></i>  08.06.2023
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-4">
                                    <div class="forum-info p-2">
                                        <i class="bi bi-bar-chart fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            141 Гласувания
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4 col-4">
                                    <div class="forum-info p-2">
                                        <i class="bi bi-chat-text fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            122 Отговора
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4  col-4">
                                    <div class="forum-info p-2">
                                        <i class="bi bi-eye fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            290 Прегледа
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation">
                        <div class="forum-header-edit">
                            <a href="#">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                            </a>
                            <a href="#">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                </i>
                            </a>
                        </div>
                        <div class="forum-item-body p-3">
                            <div href="#" class="consul-item row align-items-center">
                                <div class="col-md-6 col-sm-12">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                            <h3>Правомощия, органи и структура 2</h3>
                                        </a>
                                        <span class="text-secondary"> Публикувано на </span>
                                        <span >
                                            <i class="far fa-calendar text-secondary  ms-1"></i>  08.06.2023
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-bar-chart fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            256 Гласувания
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-chat-text fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            251 Отговора
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-eye fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            223 Прегледа
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation">
                        <div class="forum-header-edit">
                            <a href="#">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                            </a>
                            <a href="#">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                </i>
                            </a>
                        </div>
                        <div class="forum-item-body p-3">
                            <div href="#" class="consul-item row align-items-center">

                                <div class="col-md-6 col-sm-12">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                            <h3>Правомощия, органи и структура 3
                                            </h3>
                                        </a>
                                        <span class="text-secondary"> Публикувано на </span>
                                        <span >
                                            <i class="far fa-calendar text-secondary  ms-1"></i>  08.06.2023
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-bar-chart fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            245 Гласувания
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-chat-text fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            116 Отговора
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-eye fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            257 Прегледа
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation">
                        <div class="forum-header-edit">
                            <a href="#">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                            </a>
                            <a href="#">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                </i>
                            </a>
                        </div>
                        <div class="forum-item-body p-3">
                            <div href="#" class="consul-item row align-items-center">

                                <div class="col-md-6 col-sm-12">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                            <h3>Правомощия, органи и структура 4
                                            </h3>
                                        </a>
                                        <span class="text-secondary"> Публикувано на </span>
                                        <span >
                                            <i class="far fa-calendar text-secondary  ms-1"></i>  08.06.2023
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-bar-chart fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            775 Гласувания
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-chat-text fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            550 Отговора
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-eye fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            1097 Прегледа
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>     <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation">
                        <div class="forum-header-edit">
                            <a href="#">
                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                            </a>
                            <a href="#">
                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                </i>
                            </a>
                        </div>
                        <div class="forum-item-body p-3">
                            <div href="#" class="consul-item row align-items-center">
                                <div class="col-md-6 col-sm-12">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                            <h3>Правомощия, органи и структура 5
                                            </h3>
                                        </a>
                                        <span class="text-secondary"> Публикувано на </span>
                                        <span >
                                            <i class="far fa-calendar text-secondary  ms-1"></i>  08.06.2023
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-bar-chart fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            275 Гласувания
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-chat-text fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            119 Отговора
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 py-2 col-4">
                                    <div class="forum-info">
                                        <i class="bi bi-eye fs-2 dark-text mb-1"></i>
                                        <span class="dark-text">
                                            227 Прегледа
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
