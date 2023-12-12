@extends('layouts.site', ['fullwidth'=>true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Правна информация на Министерски съвет')

@section('content')
<section>
    <div class="container-fluid p-0">
        <div class="row breadcrumbs py-1">
            <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Начало</a></li>
                    <li class="breadcrumb-item"><a href="#">Актове на МС</a></li>
                    <li class="breadcrumb-item"><a href="#">Правна информация на Министерски съвет</a></li>
                </ol>
                </ol>
            </nav>
        </div>
</section>

<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">

        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Начало
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                <li class="mb-2  p-1"><a href="#" class="link-dark text-decoration-none">Планиране</a>
                                </li>
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                    <ul class="list-unstyled ps-3">
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#"
                                                class="link-dark  text-decoration-none">Законодателна програма</a></li>
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Оперативна
                                                програма</a></li>
                                        <hr class="custom-hr">
                                    </ul>
                                </ul>

                                <li class="mb-2 active-item-left p-1"><a href="#"
                                        class="link-dark text-decoration-none">Актове на МС</a></li>
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">
                                    <ul class="list-unstyled ps-3">
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#"
                                                class="link-dark  text-decoration-none">Постановления</a></li>
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Решения</a>
                                        </li>
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#"
                                                class="link-dark  text-decoration-none">Становища</a></li>
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#"
                                                class="link-dark  text-decoration-none">Протоколи</a></li>
                                        <hr class="custom-hr">
                                    </ul>
                                </ul>

                    </li>
                    <li class="mb-2"><a href="#" class="link-dark  text-decoration-none">Архив</a></li>
                </ul>
            </div>
            </li>
            <hr class="custom-hr">
            </ul>
        </div>
    </div>

</div>

<div class="col-lg-10  right-side-content py-5">
    <div class="row filter-results mb-2">
        <h2 class="mb-4">
            Търсене
        </h2>
        <div class="col-md-12">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Категория:</label>
                    <select class="form-select select2" multiple aria-label="Default select example">
                        <option value="1">Всички</option>
                        <option value="1">Постановления</option>
                        <option value="1">Разпореждания</option>
                        <option value="1">Решения</option>
                        <option value="1">Стенограми</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Съдържание:</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Заглавие:</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Основание:</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Термини:</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Вносител:</label>
                    <select class="form-select select2" multiple aria-label="Default select example">
                        <option value="1">Институция 1</option>
                        <option value="1">Институция 2</option>
                        <option value="1">Институция 3</option>
                    </select>
                </div>
            </div>
        </div>

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
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Номер:</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Държавен вестник (брой):</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="exampleFormControlInput1" class="form-label">Държавен вестник (година):</label>
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
                    <label for="exampleFormControlInput1" class="form-label">Промени:</label>
                    <input type="text" class="form-control">
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
    <div class="row mb-5">
        <div class="col-md-6">
            <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn rss-sub main-color"><i class="fas fa-square-rss text-warning"></i>RSS</button>
            <button class="btn rss-sub main-color"><i class="fas fa-envelope"></i>Абониране</button>
            <button class="btn btn-success text-success"><i
                    class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>

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
                                        <h3>Решение №752 на Министерския съвет от 2023 г.</h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#" class="me-2">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <a href="#" class="text-decoration-none"><i class="fas fa-sitemap me-2 main-color" title="Категория">
                                <span class="fw-normal main-color"> Решения</span></i>
                             </a>
                            <a href="#" class="text-decoration-none"><i class="fas fa-university fa-link main-color" title="Протокол"></i>
                                <span class="fw-normal main-color"> МС</span>
                            </a>
                            <div class="anotation text-secondary mb-2 mt-2">
                                <span class="main-color me-2">Относно:</span> Проект на Решение за одобряване проект на
                                Споразумение относно Централноевропейската програма за обмен в университетското
                                образование (CEEPUS IV)
                            </div>
                            <div class="meta-consul">
                                <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 30.06.2023
                                    г.</span>
                                <a href="{{ route('pris.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more"></i></a>
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
                                        title="Постановление №52 на Министерския съвет от 2023 г.">
                                        <h3>Постановление №52 на Министерския съвет от 2023 г.
                                      </h3>
                                    </a>
                                </div>
                                <div class="consult-item-header-edit">
                                    <a href="#">
                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                            role="button" title="Изтриване"></i>
                                    </a>
                                    <a href="#" class="me-2">
                                        <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                            title="Редакция">
                                        </i>
                                    </a>
                                </div>
                            </div>
                            <a href="#" class="text-decoration-none"><i class="fas fa-sitemap me-2 main-color" title="Категория">
                                <span class="fw-normal main-color"> Постановление</span></i>
                             </a>
                            <a href="#" class="text-decoration-none"><i class="fas fa-university fa-link main-color" title="Протокол"></i>
                                <span class="fw-normal main-color"> МС</span>
                            </a>
                            <div class="anotation text-secondary mb-2 mt-2">
                                <span class="main-color me-2">Относно:</span>  За прекратяване на ликвидацията и за
                                продължаване на дейността на еднолично акционерно дружество с държавно участие в
                                капитала "България Хепи Мед Сървиз" ЕАД

                            </div>
                            <div class="meta-consul">
                                <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 10.08.2023
                                    г.</span>
                                <a href="{{ route('pris.view', ['id' => 1]) }}"><i class="fas fa-arrow-right read-more"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

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
