@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Разработване на нов план за действие')

@section('content')

<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
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
                                <li class="mb-2 active-item-left p-1">
                                    <a href="#"
                                        class="text-light text-decoration-none">Разработване на нов план за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">OGP FORUM</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини и събития</a>
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
        <div class="row filter-results mb-2">
            <h2 class="mb-4">
                Търсене
            </h2>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                        <input type="text" class="form-control" id="searchInTitle">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Срок от:</label>
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
                        <label for="exampleFormControlInput1" class="form-label">Срок до:</label>
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
                        <label for="exampleFormControlInput1" class="form-label">Статус:</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1">Активни</option>
                            <option value="1">Неактивни</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 action-btn-wrapper">
            <div class="col-md-3 col-sm-12">
                <button class="btn rss-sub main-color" id="searchBtn"><i
                        class="fas fa-search main-color"></i>Търсене</button>
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
                <a href="https://strategy.asapbg.com/strategy-documents?order_by=policy-area"
                    class="mb-0 text-decoration-none text-dark">
                    <i class="fa-solid fa-sort me-2 "></i>Заглавие/Съдържание
                </a>
            </div>
            <div class="col-md-3 ">
                <a href="https://strategy.asapbg.com/strategy-documents?order_by=title"
                    class="mb-0 text-decoration-none text-dark">
                    <i class="fa-solid fa-sort me-2 "></i>Срок от
                </a>
            </div>


            <div class="col-md-3">
                <a href="https://strategy.asapbg.com/strategy-documents?order_by=valid-from"
                    class="mb-0 text-decoration-none text-dark">
                    <i class="fa-solid fa-sort me-2 "></i>Срок до
                </a>
            </div>
            <div class="col-md-3">
                <a href="https://strategy.asapbg.com/strategy-documents?order_by=valid-to"
                    class="mb-0 text-decoration-none text-dark">
                    <i class="fa-solid fa-sort me-2 "></i>Статус
                </a>
            </div>
        </div>

        <div class="row justify-content-end my-3">
            <div class="col-md-4">
            </div>
            <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
                    резултати:</label>
                <select class="form-select w-auto" id="paginationResults">
                    <option value="5">5</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Национални програми за развитие на образованието за 2023 г.">
                                            <h3>Примерна област за събиране на предложения</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                            <span class="d-none">Delete</span>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                            <span class="d-none">Edit</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>Статус: <span class="active-ks">Активен</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">Срок: </span> 04.07.2023 г. - 31.12.2024 г.
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
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Национални програми за развитие на образованието за 2023 г.">
                                            <h3>Примерна област за събиране на предложения</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                            <span class="d-none">Delete</span>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                            <span class="d-none">Edit</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>Статус: <span class="active-ks">Активен</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">Срок: </span> 04.07.2023 г. - 31.12.2024 г.
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
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Национални програми за развитие на образованието за 2023 г.">
                                            <h3>Примерна област за събиране на предложения</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                            <span class="d-none">Delete</span>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                            <span class="d-none">Edit</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>Статус: <span class="active-ks">Активен</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">Срок: </span> 04.07.2023 г. - 31.12.2024 г.
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
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Национални програми за развитие на образованието за 2023 г.">
                                            <h3>Примерна област за събиране на предложения</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                            <span class="d-none">Delete</span>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                            <span class="d-none">Edit</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>Статус: <span class="active-ks">Активен</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">Срок: </span> 04.07.2023 г. - 31.12.2024 г.
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
                        <div class="consult-img-holder p-2">
                            <i class="bi bi-clipboard2-plus main-color"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Национални програми за развитие на образованието за 2023 г.">
                                            <h3>Примерна област за събиране на предложения</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                            <span class="d-none">Delete</span>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                            <span class="d-none">Edit</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="status mt-2">
                                    <span>Статус: <span class="active-ks">Активен</span></span>
                                </div>
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                    <span class="text-dark">Срок: </span> 04.07.2023 г. - 31.12.2024 г.
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
                    <li class="page-item"><a class="page-link" href="#">25</a></li>
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
