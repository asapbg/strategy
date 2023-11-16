@extends('layouts.site', ['fullwidth'=>true])

@section('pageTitle', 'Правна информация на Министерски съвет')

@section('content')
    <style>
        .public-page {
            padding: 0;
        }
    </style>

    <div class="row">
        <div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">
            <div class="left-nav-panel" style="background: #fff !important;">
                <div class="flex-shrink-0 p-2">
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                               data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                <i class="fa-solid fa-bars me-2 mb-2"></i>Начало
                            </a>
                            <hr class="custom-hr">
                            <div class="collapse show mt-3" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                    <li class="mb-2 active-item-left p-1">
                                        <a href="/archive" class="link-dark text-decoration-none">Постановления</a>
                                    </li>
                                    <li class="mb-2 p-1">
                                        <a href="/archive" class="link-dark text-decoration-none">Протоколи</a>
                                    </li>
                                    <li class="mb-2 p-1">
                                        <a href="/archive" class="link-dark text-decoration-none">Разпореждания</a>
                                    </li>
                                    <li class="mb-2 p-1">
                                        <a href="/archive" class="link-dark text-decoration-none">Решения</a>
                                    </li>
                                    <li class="mb-2 p-1">
                                        <a href="/archive" class="link-dark text-decoration-none">Стенограми</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5">
            <div class="row filter-results mb-2">
                <h2 class="mb-4">Търсене</h2>

                <div class="col-md-3">
                    <label for="exampleFormControlInput1" class="form-label">Начална дата:</label>
                    <div class="input-group">
                        <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                               class="form-control datepicker">
                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="exampleFormControlInput1" class="form-label">Крайна дата:</label>
                    <div class="input-group">
                        <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                               class="form-control datepicker">
                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                    </div>
                </div>

                <div class="row my-5">
                    <div class="col-md-6">
                        <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                    </div>
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
                        <i class="fa-solid fa-sort me-2"></i>Вносител
                    </p>
                </div>


                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer">
                        <i class="fa-solid fa-sort me-2"></i>Дата
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i>Номер
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i>Заглавие
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i>Основание
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

            <div class="row">
                <div class="col-md-12">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
                            <div class="consult-body">
                                <div class="consul-item">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="{{ route('pris.view', ['id' => 1]) }}" class="text-decoration-none"
                                               title="Решение №752 на Министерския съвет от 2023 г.">
                                                <h3>Постановление <span class="fw-normal"> | 41550:13.11.2023</span></h3>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="anotation text-secondary mb-2 mt-2">
                                        <span class="main-color me-2">No.:</span> 224;
                                        <span class="main-color me-2">Дата:</span> 13.11.2023;
                                        <span class="main-color me-2">Относно:</span> ЗА ОДОБРЯВАНЕ НА ДОПЪЛНИТЕЛНИ
                                        ТРАНСФЕРИ ПО БЮДЖЕТИТЕ НА ОБЩИНИТЕ ЗА 2023 Г. ЗА ФИНАНСОВО ОСИГУРЯВАНЕ НА
                                        ДЕЙНОСТИ ПО НАЦИОНАЛНИ ПРОГРАМИ ЗА РАЗВИТИЕ НА ОБРАЗОВАНИЕТО, ОДОБРЕНИ С РЕШЕНИЕ
                                        № 408 НА МИНИСТЕРСКИЯ СЪВЕТ ОТ 2023 Г.;
                                        <span class="main-color me-2">Вносител:</span> МОН;
                                    </div>
                                    <div class="row">
                                        <span class="text-secondary"><span
                                                class="main-color me-2">Версия:</span> 1.0</span>
                                    </div>
                                    <div class="meta-consul">
                                        <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 30.06.2023г.</span>
                                        <a href="{{ route('pris.view', ['id' => 1]) }}"><i
                                                class="fas fa-arrow-right read-more"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
                            <div class="consult-body">
                                <div class="consul-item">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="{{ route('pris.view', ['id' => 1]) }}" class="text-decoration-none"
                                               title="Решение №752 на Министерския съвет от 2023 г.">
                                                <h3>Постановление <span class="fw-normal">| 41544:09.11.2023</span></h3>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="anotation text-secondary mb-2 mt-2">
                                        <span class="main-color me-2">No.:</span> 222;
                                        <span class="main-color me-2">Дата:</span> 09.11.2023;
                                        <span class="main-color me-2">Относно:</span> ЗА ОДОБРЯВАНЕ НА ДОПЪЛНИТЕЛНИ
                                        ТРАНСФЕРИ ОТ РЕЗЕРВА ПО ЧЛ. 1, АЛ. 2, РАЗДЕЛ ІІ, Т. 5.1 ОТ ЗАКОНА ЗА ДЪРЖАВНИЯ
                                        БЮДЖЕТ НА РЕПУБЛИКА БЪЛГАРИЯ ЗА 2023 Г. ЗА НЕПРЕДВИДЕНИ И/ИЛИ НЕОТЛОЖНИ РАЗХОДИ
                                        ЗА ПРЕДОТВРАТЯВАНЕ, ОВЛАДЯВАНЕ И ПРЕОДОЛЯВАНЕ НА ПОСЛЕДИЦИТЕ ОТ БЕДСТВИЯ;
                                        <span class="main-color me-2">Вносител:</span> МВР;
                                        <span class="main-color me-2">ДВ Брой:</span> 98;
                                        <span class="main-color me-2">ДВ Година:</span> 2023;
                                    </div>
                                    <div class="row">
                                        <span class="text-secondary"><span
                                                class="main-color me-2">Версия:</span> 2.0</span>
                                    </div>
                                    <div class="meta-consul">
                                        <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 30.06.2023г.</span>
                                        <a href="{{ route('pris.view', ['id' => 1]) }}"><i
                                                class="fas fa-arrow-right read-more"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
        </div>
    </div>
@endsection
