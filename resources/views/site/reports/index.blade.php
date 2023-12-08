@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Отчети')

@section('content')
<div class="container-fluid">
    <div class="row edit-consultation m-0">
        <div class="col-md-12 text-end">
            <button class="btn btn-sm btn-primary main-color mt-2">
                <i class="fas fa-pen me-2 main-color"></i>Редактиране
            </button>
        </div>
    </div>
</div>

    <div class="row filter-results mb-2">
        <h2 class="mb-4">
            Търсене
        </h2>
        <div class="col-md-4">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Типа консултация</label>
                    <select class="form-select select2" aria-label="Default select example">
                        <option value="1">Национални</option>
                        <option value="2">Областни и общински</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Тема</label>
                    <select class="form-select select2" aria-label="Default select example">
                        <option value="0">Всички</option>
                        <option value="1">COVID-19</option>
                        <option value="2">Бизнес среда</option>
                        <option value="3">Външна политика, сигурност и отбрана</option>
                        <option value="4">Държавна администрация</option>
                        <option value="5">Енергетика</option>
                        <option value="6">Защита на потребителите</option>
                        <option value="7">Здравеопазване</option>
                        <option value="8">Земеделие и селски райони</option>
                        <option value="9">Качество и безопасност на храните</option>
                        <option value="10">Култура</option>
                        <option value="11">Междусекторни политики</option>
                        <option value="12">Младежка политика</option>
                        <option value="13">Наука и технологии</option>
                        <option value="14">Образование</option>
                        <option value="15">Околна среда</option>
                        <option value="16">Правосъдие и вътрешни работи</option>
                        <option value="17">Регионална политика</option>
                        <option value="18">Социална политика и заетост</option>
                        <option value="19">Спорт</option>
                        <option value="20">Транспорт</option>
                        <option value="21">Туризъм</option>
                        <option value="22">Финанси и данъчна политика</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Покажи</label>
                    <select class="form-select select2" aria-label="Default select example">
                        <option value="1">5</option>
                        <option value="1">10</option>
                        <option value="1">50</option>
                        <option value="1">100</option>
                        <option value="1">150</option>
                        </option>
                        <option value="1">200</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn rss-sub main-color"><i class="fas fa-square-rss text-warning"></i>{{ __('custom.rss_subscribe') }}</button>
            <button class="btn rss-sub main-color"><i class="fas fa-envelope"></i>{{ __('custom.subscribe') }}</button>
        </div>
    </div>

    <div class="row pt-4 pb-2 px-2">
        <div class="col-md-12">
            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2">
                <div class="col-md-4">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i> Наименование
                    </p>
                </div>
                <div class="col-md-2 cursor-pointer ">
                    <p class="mb-0">
                        <i class="fa-solid fa-sort"></i> Област на политика
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i> Целева група
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i> Дата на откриване
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort"></i> Дата на приключване
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
            <div class="custom-card p-3 mb-3">
                <div class="row single-record">
                    <div class="col-md-4">
                        <p>
                            <a href="/report/view" class="main-color text-decoration-none">Проект на Закон за изменение и
                                допълнение на Закона за малките и средните предприятия <span class="active-li ms-2">Активна</span></a> <!-- ЗА Неактивен статус ! <span class="inactive-ks text-dark ms-2">Неактивна</span> -->
                        </p>

                        <p class="mb-0">
                            Коментари (0)
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Бизнес среда</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Всички заинтересовани</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            16.11.2023 г.
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            16.12.2023 г.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="custom-card p-3 mb-3">
                <div class="row single-record">
                    <div class="col-md-4">
                        <p>
                            <a href="/report/view" class="main-color text-decoration-none">Проект на Решение на Министерския съвет за утвърждаване на Национална аптечна карта <span class="active-li ms-2">Активна</span></a>
                        </p>

                        <p class="mb-0">
                            Коментари (0)
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Здравеопазване </a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Всички заинтересовани</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            14.09.2023 г.
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            14.11.2023 г.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="custom-card p-3 mb-3">
                <div class="row single-record">
                    <div class="col-md-4">
                        <p>
                            <a href="/report/view" class="main-color text-decoration-none">Проект на Постановление на Министерския съвет за изменение и допълнение на Устройствения правилник на Агенцията по заетостта <span class="active-li ms-2">Активна</span></a>
                        </p>

                        <p class="mb-0">
                            Коментари (1)
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Социална политика и заетост</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Всички заинтересовани</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            15.07.2023 г.
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            15.08.2023 г.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="custom-card p-3 mb-3">
                <div class="row single-record">
                    <div class="col-md-4">
                        <p>
                            <a href="/report/view" class="main-color text-decoration-none">Проект на Закон за изменение и допълнение на Наказателния кодекс<span class="active-li ms-2">Активна</span></a>
                        </p>

                        <p class="mb-0">
                            Коментари (2)
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Правосъдие и вътрешни работи</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            <a href="#" class="main-color text-decoration-none">Всички заинтересовани</a>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            15.04.2023 г.
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p>
                            15.05.2023 г.
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-4">
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
@endsection
