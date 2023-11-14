@extends('layouts.site')

@section('pageTitle', 'Стратегически документи')

@section('content')
<section class="public-constultation py-5">
    <div class="container">
        <div class="row filter-results mb-2">
            <h2 class="mb-4">
                Търсене
            </h2>

            <div class="col-md-12">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Тема:</label>
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
                        <label for="exampleFormControlInput1" class="form-label">Изработила институция:</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1">Министерски съвет</option>
                            <option value="1">Конституционен съд</option>
                            <option value="1">Върховен административен съд</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Срок за приемане от:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Срок за приемане до:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Брой резултати:</label>
                        <select class="form-select">
                            <option value="1">10</option>
                            <option value="1">20</option>
                            <option value="1">30</option>
                            <option value="1">40</option>
                            <option value="1">50</option>
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
                <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
                    Абониране</button>
                <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                <button class="btn btn-success text-success"><i
                        class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
            </div>
        </div>

        <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Тема
                </p>
            </div>
            <div class="col-md-3 ">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Изработила институция
                </p>
            </div>


            <div class="col-md-3">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Заглавие
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Срок на приемане
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
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                                title="Редакция">
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

                                    <a href="#" title="Национални програми за развитие на образованието за 2023 г.">
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
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                                title="Редакция">
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

                                    <a href="#" title="Морски пространствен план на Република България 2021-2035 г.">
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
                                                Национален план на Република България за развитие на способностите за управление
                                                на границите и за връщане на незаконно пребиваващи граждани на трети страни.
                                            </h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                role="button" title="Изтриване"></i>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                                title="Редакция">
                                            </i>
                                        </a>
                                    </div>
                                </div>

                                <a href="#" title="Образование" class="text-decoration-none mb-3">
                                    <i class="bi bi-shield-fill-check me-1" title="Външна политика, сигурност и отбрана"></i>
                                    Външна политика, сигурност и отбрана
                                </a>

                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        11.05.2023 г. - Не е указан срок
                                    </span>

                                    <a href="#" title="Национални програми за развитие на образованието за 2023 г.">
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
                                        <a href="#" class="text-decoration-none"
                                            title="Национална пътна карта за подобряване на условията за разгръщане на потенциала
                                            за развитие на водородните технологии и механизмите за производство и доставка
                                            на водород">
                                            <h3>
                                                
                                        Национална пътна карта за подобряване на условията за разгръщане на потенциала
                                        за развитие на водородните технологии и механизмите за производство и доставка
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
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                                title="Редакция">
                                            </i>
                                        </a>
                                    </div>
                                </div>

                                <a href="#" title="Образование" class="text-decoration-none mb-3">
                                    <i class="fa-solid fa-flask-vial me-1" title="Външна политика, сигурност и отбрана"></i>
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
    </div>
    </div>
    </div>
</section>
@endsection
