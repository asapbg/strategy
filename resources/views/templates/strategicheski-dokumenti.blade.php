@extends('layouts.site', ['fullwidth' => true])


<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Стратегически документи - вътрешна страница')

@section('content')

<div class="row pb-5">
    <div class="col-md-12">
        <ul class=" tab nav nav-tabs mb-3" id="myTab">
            <li class="nav-item pb-0">
                <a href="#table-view" class="nav-link tablinks active" data-toggle="tab">Табличен изглед</a>
            </li>
            <li class="nav-item pb-0">
                <a href="#tree-view" class="nav-link tablinks" data-toggle="tab">Дървовиден изглед</a>
            </li>

        </ul>
        <div class="tab-content">

            <div class="tab-pane fade show active" id="table-view">
                <div class="row filter-results mb-2">
                    <div class="col-md-6">
                        <h2 class="mb-4">
                            Търсене
                        </h2>
                    </div>
                    <div class="col-md-6 text-end">
                        <!--
                        И в двата вида визуализация (табличен и дървовиден), 
                        нека най-отгоре да има линк към основната справка
                         „Справка с всички документи“, която да сваля PDF 
                         с дървото и всички прилежащи файлове за всеки документ 
                         като линкове, за които е дадено „видими в справката“. 
                         Това е подобно на сегашната функционалност:
                        -->
                        <button class="btn btn-primary main-color"><i
                                class="fas fa-download main-color me-1"></i>Справка с всички документи</button>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Област на политика</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="1">Всички</option>
                                    <option value="1">Регионална политика</option>
                                    <option value="1">Образование</option>
                                    <option value="1">Външна политика, сигурност и отбрана</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Категория</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="1">Всички</option>
                                    <option value="1">Действащи</option>
                                    <option value="1">Изтекли</option>
                                    <option value="1">Процес на консултация</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Търсене в
                                    Заглавие/Съдържание</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Валидна от:</label>
                                <div class="input-group">
                                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                        class="form-control datepicker">
                                    <span class="input-group-text" id="basic-addon2"><i
                                            class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Валидна до:</label>
                                <div class="input-group">
                                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                        class="form-control datepicker">
                                    <span class="input-group-text" id="basic-addon2"><i
                                            class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Ниво</label>
                                <select class="form-select">
                                    <!-- 
                                        Важно - При второто и третото трябва да се появява или да става активно поле за избор на съответната област или община.
                                    -->
                                    <option value="1">--</option>
                                    <option value="1">Централно</option>
                                    <option value="1">Областно</option>
                                    <option value="1">Общинско</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 action-btn-wrapper">
                    <div class="col-md-3 col-sm-12">
                        <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                    </div>
                    <div class="col-md-9 text-end col-sm-12">
                        <button class="btn btn-primary  main-color"><i
                                class="fas fa-square-rss text-warning me-1"></i>RSS
                            Абониране</button>
                        <button class="btn btn-primary main-color"><i
                                class="fas fa-envelope me-1"></i>Абониране</button>
                        <button class="btn btn-success text-success"><i
                                class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
                    </div>
                </div>

                <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer ">
                            <i class="fa-solid fa-sort me-2"></i> Област на политика
                        </p>
                    </div>
                    <div class="col-md-3 ">
                        <p class="mb-0 cursor-pointer">
                            <i class="fa-solid fa-sort me-2"></i>Заглавие
                        </p>
                    </div>


                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer">
                            <i class="fa-solid fa-sort me-2"></i>Валидна от
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer ">
                            <i class="fa-solid fa-sort me-2"></i>Валидна до
                        </p>
                    </div>
                </div>

                <div class="row justify-content-end my-3">
                    <div class="col-md-4">

                    </div>
                    <div
                        class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                        <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
                            резултати:</label>
                        <select class="form-select w-auto">
                            <option value="1">10</option>
                            <option value="1">20</option>
                            <option value="1">30</option>
                            <option value="1">40</option>
                            <option value="1">50</option>
                            <option value="1">100</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="consul-wrapper">
                            <div class="single-consultation d-flex">
                                <div class="consult-img-holder p-2">
                                    <i class="bi bi-mortarboard-fill light-blue"></i>
                                </div>
                                <div class="consult-body">
                                    <div href="#" class="consul-item">

                                        <div class="consult-item-header d-flex justify-content-between">
                                            <div class="consult-item-header-link">
                                                <a href="#" class="text-decoration-none"
                                                    title="Национални програми за развитие на образованието за 2023 г.">
                                                    <h3>Национални програми за развитие на образованието за 2023 г.</h3>
                                                </a>
                                            </div>
                                            <div class="consult-item-header-edit">
                                                <a href="#">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                        role="button" title="Изтриване"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                        role="button" title="Редакция">
                                                    </i>
                                                </a>
                                            </div>
                                        </div>

                                        <a href="#" title="Образование" class="text-decoration-none mb-3">
                                            <i class="bi bi-mortarboard-fill me-1" title="Образование"></i>
                                            Образование
                                        </a>

                                        <div class="meta-consul mt-2">
                                            <span class="text-secondary">
                                                04.07.2023 г. - 31.12.2024 г.
                                            </span>

                                            <a href="#"
                                                title="Национални програми за развитие на образованието за 2023 г.">
                                                <i class="fas fa-arrow-right read-more"></i>
                                            </a>
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
                            <div class="single-consultation d-flex">
                                <div class="consult-img-holder">
                                    <i class="fa-solid fa-circle-nodes dark-blue"></i>
                                </div>
                                <div class="consult-body">
                                    <div href="#" class="consul-item">

                                        <div class="consult-item-header d-flex justify-content-between">
                                            <div class="consult-item-header-link">
                                                <a href="#" class="text-decoration-none"
                                                    title="Морски пространствен план на Република България 2021-2035 г.">
                                                    <h3>
                                                        Морски пространствен план на Република България 2021-2035 г.
                                                    </h3>
                                                </a>
                                            </div>
                                            <div class="consult-item-header-edit">
                                                <a href="#">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                        role="button" title="Изтриване"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                        role="button" title="Редакция">
                                                    </i>
                                                </a>
                                            </div>
                                        </div>

                                        <a href="#" title="Образование" class="text-decoration-none mb-3">
                                            <i class="fa-solid fa-circle-nodes me-1" title="Регионална политика"></i>
                                            Регионална политика
                                        </a>

                                        <div class="meta-consul mt-2">
                                            <span class="text-secondary">
                                                30.06.2023 г. - 31.12.2024 г.
                                            </span>

                                            <a href="#"
                                                title="Морски пространствен план на Република България 2021-2035 г.">
                                                <i class="fas fa-arrow-right read-more"></i>
                                            </a>
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
                            <div class="single-consultation d-flex">
                                <div class="consult-img-holder">
                                    <i class="bi bi-shield-fill-check gr-color"></i>
                                </div>
                                <div class="consult-body">
                                    <div href="#" class="consul-item">

                                        <div class="consult-item-header d-flex justify-content-between">
                                            <div class="consult-item-header-link">
                                                <a href="#" class="text-decoration-none"
                                                    title="Национален план на Република България за развитие на способностите за управление
                                                на границите и за връщане на незаконно пребиваващи граждани на трети страни.">
                                                    <h3>
                                                        Национален план на Република България за развитие на
                                                        способностите за управление
                                                        на границите и за връщане на незаконно пребиваващи граждани на
                                                        трети страни.
                                                    </h3>
                                                </a>
                                            </div>
                                            <div class="consult-item-header-edit">
                                                <a href="#">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                        role="button" title="Изтриване"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                        role="button" title="Редакция">
                                                    </i>
                                                </a>
                                            </div>
                                        </div>

                                        <a href="#" title="Образование" class="text-decoration-none mb-3">
                                            <i class="bi bi-shield-fill-check me-1"
                                                title="Външна политика, сигурност и отбрана"></i>
                                            Външна политика, сигурност и отбрана
                                        </a>

                                        <div class="meta-consul mt-2">
                                            <span class="text-secondary">
                                                11.05.2023 г. - Не е указан срок
                                            </span>

                                            <a href="#"
                                                title="Национални програми за развитие на образованието за 2023 г.">
                                                <i class="fas fa-arrow-right read-more"></i>
                                            </a>
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
                            <div class="single-consultation d-flex">
                                <div class="consult-img-holder">
                                    <i class="fa-solid fa-flask-vial light-blue"></i>
                                </div>
                                <div class="consult-body">
                                    <div href="#" class="consul-item">

                                        <div class="consult-item-header d-flex justify-content-between">
                                            <div class="consult-item-header-link">
                                                <a href="#" class="text-decoration-none" title="Национална пътна карта за подобряване на условията за разгръщане на потенциала
                                                за развитие на водородните технологии и механизмите за производство и доставка
                                                на водород">
                                                    <h3>

                                                        Национална пътна карта за подобряване на условията за разгръщане
                                                        на потенциала
                                                        за развитие на водородните технологии и механизмите за
                                                        производство и доставка
                                                        на водород

                                                    </h3>
                                                </a>
                                            </div>
                                            <div class="consult-item-header-edit">
                                                <a href="#">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                        role="button" title="Изтриване"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                        role="button" title="Редакция">
                                                    </i>
                                                </a>
                                            </div>
                                        </div>

                                        <a href="#" title="Образование" class="text-decoration-none mb-3">
                                            <i class="fa-solid fa-flask-vial me-1"
                                                title="Външна политика, сигурност и отбрана"></i>
                                            Външна политика, сигурност и отбрана
                                        </a>

                                        <div class="meta-consul mt-2">
                                            <span class="text-secondary">
                                                05.07.2023 г. - Не е указан срок
                                            </span>

                                            <a href="#" title=" Национална пътна карта за подобряване на условията за разгръщане на потенциала
                                        за развитие на водородните технологии и механизмите за производство и доставка
                                        на водород">
                                                <i class="fas fa-arrow-right read-more"></i>
                                            </a>
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
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">25</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="tab-pane fade" id="tree-view">
                <div class="row filter-results mb-2">
                    <div class="col-md-6">
                        <h2 class="mb-4">
                            Търсене
                        </h2>
                    </div>
                    <div class="col-md-6 text-end">
                        <!--
                        И в двата вида визуализация (табличен и дървовиден), 
                        нека най-отгоре да има линк към основната справка
                         „Справка с всички документи“, която да сваля PDF 
                         с дървото и всички прилежащи файлове за всеки документ 
                         като линкове, за които е дадено „видими в справката“. 
                         Това е подобно на сегашната функционалност:
                        -->
                        <button class="btn btn-primary main-color"><i
                                class="fas fa-download main-color me-1"></i>Справка с всички документи</button>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Област на политика</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="1">Всички</option>
                                    <option value="1">Регионална политика</option>
                                    <option value="1">Образование</option>
                                    <option value="1">Външна политика, сигурност и отбрана</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Категория</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="1">Всички</option>
                                    <option value="1">Действащи</option>
                                    <option value="1">Изтекли</option>
                                    <option value="1">Процес на консултация</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Категории спрямо цикъла си на
                                    живот</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="1">Действащи</option> <!-- default -->
                                    <option value="1">Изтекли</option>
                                    <option value="1">В процес на консултация</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Търсене в
                                    Заглавие/Съдържание</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Валидна от:</label>
                                <div class="input-group">
                                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                        class="form-control datepicker">
                                    <span class="input-group-text" id="basic-addon2"><i
                                            class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Валидна до:</label>
                                <div class="input-group">
                                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                        class="form-control datepicker">
                                    <span class="input-group-text" id="basic-addon2"><i
                                            class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1" class="form-label">Ниво</label>
                                <select class="form-select">
                                    <!-- 
                                        Важно - При второто и третото трябва да се появява или да става активно поле за избор на съответната област или община.
                                    -->
                                    <option value="1">--</option>
                                    <option value="1">Централно</option>
                                    <option value="1">Областно</option>
                                    <option value="1">Общинско</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 action-btn-wrapper">
                    <div class="col-md-3 col-sm-12">
                        <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                    </div>
                    <div class="col-md-9 text-end col-sm-12">
                        <button class="btn btn-primary  main-color"><i
                                class="fas fa-square-rss text-warning me-1"></i>RSS
                            Абониране</button>
                        <button class="btn btn-primary main-color"><i
                                class="fas fa-envelope me-1"></i>Абониране</button>
                        <button class="btn btn-success text-success"><i
                                class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
                    </div>
                </div>

                <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer ">
                            <i class="fa-solid fa-sort me-2"></i> Област на политика
                        </p>
                    </div>
                    <div class="col-md-3 ">
                        <p class="mb-0 cursor-pointer">
                            <i class="fa-solid fa-sort me-2"></i>Заглавие
                        </p>
                    </div>


                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer">
                            <i class="fa-solid fa-sort me-2"></i>Валидна от
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer ">
                            <i class="fa-solid fa-sort me-2"></i>Валидна до
                        </p>
                    </div>
                </div>

                <div class="row justify-content-end mt-3">
                    <div class="col-md-4">

                    </div>
                    <div
                        class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                        <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
                            резултати:</label>
                        <select class="form-select w-auto">
                            <option value="1">10</option>
                            <option value="1">20</option>
                            <option value="1">30</option>
                            <option value="1">40</option>
                            <option value="1">50</option>
                            <option value="1">100</option>
                        </select>
                    </div>
                </div>
                <div class="easy-tree">
                    <ul>
                        <li class="parent_li">
                            <span>
                                <span class="glyphicon"></span>
                                <a href="#" class="main-color fs-18 fw-600" data-toggle="collapse"
                                    href="#multiCollapseExample1" role="button" aria-expanded="true"
                                    aria-controls="multiCollapseExample1">
                                    <i class="bi bi-pin-map-fill me-1 main-color" title="Национални"></i>
                                    Национални
                                </a>
                            </span>
                            <ul>
                                <li class="active-node parent_li">
                                    <span>
                                        <a href="#" class="main-color fs-18" data-toggle="collapse"
                                            data-target="#center-level">
                                            <i class="fa-solid fa-arrow-right-to-bracket me-1 main-color"
                                                title="Национални"></i>
                                            Централно ниво
                                        </a>
                                    </span>
                                    <ul class="collapse show" id="center-level">
                                        <li class=" wait active-node" data-href="#">
                                            <span>
                                                <a href="#">
                                                    Национална стратегия за околна среда 2021 – 2030 г.
                                                </a>
                                            </span>
                                        </li>

                                        <li class=" wait active-node" data-href="#">
                                            <span>
                                                <a href="#">
                                                    Национален план за действие за борба с антисемитизма (2023 - 2027
                                                    г.)
                                                </a>
                                            </span>
                                        </li>
                                    </ul>
                                </li>

                                <li class="active-node parent_li">
                                    <span><!-- Това е стр документ без родителска категория                                    
                                        Във всяка от така дефинираните единици стратегиите са йерархични, 
                                        като всички без родител са първо ниво, всички със родител са под родителя си с collapse/expand
                                    -->
                                        <a href="#" class="dark-text">
                                            Национални програми за развитие на образованието за 2023 г.    
                                        </a>
                                    </span>
                                </li>
                                <li class="active-node parent_li">
                                    <span><!-- Това е стр документ без родителска категория                                    
                                        Във всяка от така дефинираните единици стратегиите са йерархични, 
                                        като всички без родител са първо ниво, всички със родител са под родителя си с collapse/expand
                                    -->
                                        <a href="#" class="dark-text">    
                                            Национален план за противодействие на тероризма
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </li>

                        <li class="parent_li">
                            <span>
                                <span class="glyphicon"></span>
                                <a href="#" class="main-color fs-18 fw-600" data-toggle="collapse"
                                    href="#multiCollapseExample1" role="button" aria-expanded="true"
                                    aria-controls="multiCollapseExample1">
                                    <i class="bi bi-pin-map-fill me-1 main-color" title="Национални"></i>
                                    Регионални
                                </a>
                            </span>
                            <ul>
                                <li class="active-node parent_li">
                                    <span>
                                        <a href="#" class="main-color fs-18" data-toggle="collapse"
                                            data-target="#district-level">
                                            <i class="fa-solid fa-arrow-right-to-bracket me-1 main-color"
                                                title="Национални"></i>
                                            Областно ниво
                                        </a>
                                    </span>
                                    <ul class="collapse show" id="district-level">
                                        <li class=" wait active-node" data-href="#">
                                            <span>
                                                <a href="#">
                                                    Областна стратегия за развитие на Област Монтана 2014-2020 г.
                                                </a>
                                            </span>
                                        </li>

                                        <li class=" wait active-node" data-href="#">
                                            <span>
                                                <a href="#">
                                                    Областна стратегия за развитие на Област Бургас за периода 2014
                                                    -2020 г.
                                                </a>
                                            </span>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active-node parent_li">
                                    <span>
                                        <a href="#" class="main-color fs-18" data-toggle="collapse"
                                            data-target="#regional-level">
                                            <i class="fa-solid fa-arrow-right-to-bracket me-1 main-color"
                                                title="Национални"></i>
                                            Регионално ниво
                                        </a>
                                    </span>
                                    <ul class="collapse show" id="regional-level">
                                        <li class=" wait active-node" data-href="#">
                                            <span>
                                                <a href="#">
                                                    План за интегрирано развитие на община Първомай за периода 2021 –
                                                    2027 г.
                                                </a>
                                            </span>
                                        </li>

                                        <li class=" wait active-node" data-href="#">
                                            <span>
                                                <a href="#">
                                                    План за интегрирано развитие на община Лъки 2021-2027 г.
                                                </a>
                                            </span>
                                        </li>
                                    </ul>

                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>


            </div>

        </div>
    </div>
</div>
@endsection
