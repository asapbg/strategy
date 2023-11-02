@extends('layouts.site')

@section('pageTitle', 'Правна информация на Министерски съвет')

@section('content')
    <div class="col-lg-12  home-results home-results-two pris-list" style="padding: 0px !important;">
        <hr>
        <div class="row filter-results mb-2">
            <h2 class="mb-4">
                Търсене
            </h2>
            <div class="col-md-3">
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
                <label for="exampleFormControlInput1" class="form-label">Начална дата:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker" >
                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label for="exampleFormControlInput1" class="form-label">Крайна дата:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker" >
                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Относно:</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Вносител:</label>
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
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Промени:</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Правно основание:</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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
            <div class="col-12">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Термини:</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
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
            </div>

            <div class="col-12 mt-4">
                <div class="info-consul text-start">
                    <h4>
                        Общо 98 резултата
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-body">
                            <a href="#" class="consul-item">
                                <p>
                                    <i class="me-1 dark-blue fw-bold fst-normal" title="Номер">№</i>38.15 |
                                    <i class="fas fa-sitemap me-1 dark-blue" title="Категория"></i>Протокол |
                                    <i class="fas fa-university me-1 dark-blue" title="Вносител"></i>МОН
                                    <i class="fas fa-pen-to-square float-end dark-blue fs-4" role="button" title="Редакция"></i>
                                </p>
                                <div class="anotation text-secondary mb-2">
                                    <span class="dark-blue me-2">Относно:</span> Проект на Решение за одобряване проект на Споразумение относно Централноевропейската програма за обмен в университетското образование (CEEPUS IV)
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 30.06.2023 г.</span>
                                    <i class="fas fa-arrow-right read-more"></i>
                                </div>
                            </a>

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
                            <a href="#" class="consul-item">
                                <p>
                                    <i class="me-1 dark-blue fw-bold fst-normal" title="Номер">№</i>6 |
                                    <i class="fas fa-sitemap me-1 dark-blue" title="Категория"></i>Разпореждане |
                                    <i class="fas fa-university me-1 dark-blue" title="Вносител"></i>МТС
                                    <i class="fas fa-pen-to-square float-end dark-blue fs-4" role="button" title="Редакция"></i>
                                </p>
                                <div class="anotation text-secondary mb-2">
                                    <span class="dark-blue me-2">Относно:</span> За прекратяване на ликвидацията и за продължаване на дейността на еднолично акционерно дружество с държавно участие в капитала "България Хепи Мед Сървиз" ЕАД
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 10.08.2023 г.</span>
                                    <i class="fas fa-arrow-right read-more"></i>
                                </div>
                            </a>

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
                            <a href="#" class="consul-item">
                                <p>
                                    <i class="me-1 dark-blue fw-bold fst-normal" title="Номер">№</i>38 |
                                    <i class="fas fa-sitemap me-1 dark-blue" title="Категория"></i>Стенограми |
                                    <i class="fas fa-link me-1 dark-blue" title="Протокол"></i>37
                                    <i class="fas fa-pen-to-square float-end dark-blue fs-4" role="button" title="Редакция"></i>
                                </p>
                                <div class="anotation text-secondary mb-2">
                                    <span class="dark-blue me-2">Относно:</span> Стенограма за заседание на МС проведено на 23.08.2023
                                </div>
                                <div class="meta-consul">
                                    <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> 23.08.2023 г.</span>
                                    <i class="fas fa-arrow-right read-more"></i>
                                </div>
                            </a>

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
@endsection
