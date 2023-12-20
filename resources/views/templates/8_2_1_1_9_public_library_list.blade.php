@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Списък на физическите и юридическите лица')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Библиотека
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Публикации</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>

    <div class="col-lg-10  py-5 right-side-content">
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
                            <option value="1">Оценка на въздействието</option>
                            <option value="1">Стратегическо планиране</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Публикувана след:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Публикувана преди:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 action-btn-wrapper">
            <div class="col-md-4">
                <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
            </div>
            <div class="col-md-8 text-end">
                <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS</button>
                <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
            </div>
        </div>

        <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Категория
                </p>
            </div>
            <div class="col-md-3 cursor-pointer ">
                <p class="mb-0">
                    <i class="fa-solid fa-sort me-2"></i>Заглавие/Съдържание
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Публикувана преди
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Публикувана след
                </p>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6 mt-2">
                <div class="info-consul text-start">
                    <p class="fw-600">
                        Общо 3 резултата
                    </p>
                </div>
            </div>
            <div class="col-md-6 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой резултати:</label>
                <select class="form-select w-auto " name="paginate" id="list-paginate" data-container="#listContainer">
                    <option value="20" selected="">3</option>
                    <option value="30">10</option>
                    <option value="40">20</option>
                    <option value="50">30</option>
               </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-library d-flex">
                        <div class="library-img-holder">
                            <img class="img-fluid" src="{{ asset('\img\library.jpg') }}" alt="Img title">
                        </div>
                        <div class="consult-body">
                            <div class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none"
                                            title="Европейски механизъм за върховенството на закона">
                                            <h3>Европейски механизъм за върховенството на закона</h3>
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
                                <a href="#" title="Оценка на въздействието" class="text-decoration-none mb-3">
                                    <i class="fas fa-sitemap me-1" title="Област на политика"></i>
                                    Оценка на въздействието
                                </a>
                                <div class="anotation text-secondary mb-2 mt-2">
                                    Комитетът за регулаторен контрол е създаден от Европейската комисия през 2015 г., като
                                    заменя Комитета по оценка на въздействието и има по-широки отговорности от него.
                                    <!-- Ако може да има лимит на думите при описанието на дадена публикация. Да е колкото сегашния брой. -->
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                            title="Публикувано"></i> 30.06.2023 г.</span>
                                    <a href="#">
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
                    <div class="single-library d-flex">
                        <div class="library-img-holder">
                            <img class="img-fluid" src="{{ asset('\img\library.jpg') }}" alt="Img title">
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">

                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none"
                                            title="Доклади за състоянието на администрацията">
                                            <h3>Доклади за състоянието на администрацията</h3>
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
                                <a href="#" class="text-decoration-none" title=" Стратегическо планиране"> <i
                                        class="fas fa-sitemap me-1" title="Област на политика"></i>
                                    Стратегическо планиране
                                </a>
                                <div class="anotation text-secondary mb-2 mt-2">
                                <!-- Ако може да има лимит на думите при описанието на дадена публикация. Да е колкото сегашния брой. -->
                                    Комитетът за регулаторен контрол е създаден от Европейската комисия през 2015 г., като
                                    заменя Комитета по оценка на въздействието и има по-широки отговорности от него.
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                            title="Публикувано"></i> 19.6.2023 г.</span>
                                    <a href="#">
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
                    <div class="single-library d-flex">
                        <div class="library-img-holder">
                            <img class="img-fluid" src="{{ asset('\img\library.jpg') }}" alt="Img title">
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">

                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none"
                                            title="Доклади на Комитета за регулаторен контрол към Европейската комисия">
                                            <h3>Доклади на Комитета за регулаторен контрол към Европейската комисия</h3>
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


                                <a href="#" class="text-decoration-none"><i class="fas fa-sitemap me-1"
                                        title="Област на политика"></i>
                                    Оценка на въздействието
                                </a>
                                <div class="anotation text-secondary mb-2 mt-2">
                                <!-- Ако може да има лимит на думите при описанието на дадена публикация. Да е колкото сегашния брой. -->
                                    Комитетът за регулаторен контрол е създаден от Европейската комисия през 2015 г., като
                                    заменя Комитета по оценка на въздействието и има по-широки отговорности от него.
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary"
                                            title="Публикувано"></i> 07.04.2023 г.</span>
                                    <a href="#">
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
