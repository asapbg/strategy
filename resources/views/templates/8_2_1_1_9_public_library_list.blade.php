@extends('layouts.site')

@section('pageTitle', 'Списък на физическите и юридическите лица')

@section('content')
<div class="col-lg-12  home-results home-results-two " style="padding: 0px !important;">
    <hr>
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
                    <label for="exampleFormControlInput1" class="form-label">Публикувана след:</label>
                    <div class="input-group">
                        <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
            <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS</button>
            <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
            <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
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
                    <div class="consult-img-holder">
                        <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">
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
                                Европейският механизъм за върховенството на закона осигурява процес на диалог относно
                                принципите на върховенството на закона между Европейската Комисията, Съвета на ЕС и
                                Европейския парламент съвместно с държавите от Европейския съюз, включително с техните
                                правителства, гражданското общество и други заинтересовани страни.
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
    <div class="row">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-img-holder">
                        <img class="img-thumbnail" src="{{ asset('\img\default_library_img.jpg') }}">
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
                                *Публикацията е обновена през месец юни 2023 г.
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

    <div class="row">
        <div class="col-md-12">
            <div class="consul-wrapper">
                <div class="single-consultation d-flex">
                    <div class="consult-img-holder">
                        <img class="img-thumbnail" src="{{ asset('\img\templates\library_img_1.jpg') }}">
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
                                Комитетът за регулаторен контрол е създаден от Европейската комисия през 2015 г., като
                                заменя Комитета по оценка на въздействието и има по-широки отговорности от него. Той е
                                независим орган на Комисията, съставен от нейни служители и експерти извън нея, за да
                                съветва комисарите.
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
<div>
    <div>
    </div>
</div>
@endsection
