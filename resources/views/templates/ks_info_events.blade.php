@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Актуална информация и събития')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Консултативни съвети
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Контакти</a>
                                </li>
                                <li class="mb-2 active-item-left p-1 "><a href="#"
                                        class="link-dark text-decoration-none">Актуална информация и събития</a>
                                </li>
                                <li class="mb-2 "><a href="#" class="link-dark text-decoration-none">Новини</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>

    <div class="col-lg-10 py-5 right-side-content">
        <div class="row filter-results mb-2">
            <div class="col-md-12">
                <h2 class="mb-4">
                    Търсене
                </h2>
            </div>
            <div class="col-md-12">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column w-100">
                        <label for="exampleFormControlInput1" class="form-label">Категория</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">--</option>
                            <option value="1">Всички</option>
                            <option value="1">Промени относно съветите</option>
                            <option value="1">Промяна в устройствен акт</option>
                            <option value="1">Планирани и проведени заседания</option>
                            <option value="1">Последна информация</option>
                            <option value="1">Актуални събития</option>
                            <option value="1">Взети решения</option>
                            <option value="1">Планирани заседания</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в
                            Заглавие/Съдържание</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Дата от:</label>
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
                        <label for="exampleFormControlInput1" class="form-label">Дата до:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 action-btn-wrapper">
            <div class="col-md-3 col-sm-12">
                <button class="btn rss-sub main-color" id="searchBtn"><i class="fas fa-search main-color"></i>Търсене</button>
            </div>
            <div class="col-md-9 text-end col-sm-12">
                <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
                    Абониране</button>
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
            <div class="col-md-3 ">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Заглавие
                </p>
            </div>


            <div class="col-md-3">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Дата от
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Дата до
                </p>
            </div>
        </div>

        <div class="row justify-content-end my-3">
            <div class="col-md-4">
            </div>
            <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
                    резултати:</label>
                <select class="form-select w-auto">
                    <option value="1">4</option>
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
                        <div class="consult-img-holder">
                            <i class="fa-solid fa-users dark-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <h3>
                                                Как да накараме населението да се превърне в общество Единно, участващо в управлението на община?</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                        </a>
                                    </div>
                                </div>
                                <a href="#" title=" Партньорство за открито управление" class="text-decoration-none mb-3">
                                    Последно публикувана информация
                                </a>

                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 04.07.2023 г
                                    </span>

                                    <a href="#" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                        <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
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
                            <i class="fa-solid fa-user-tie dark-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <h3>Участие на гражданите в обсъжданията на законопроектите на Народното събрание.</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                        </a>
                                    </div>
                                </div>
                                <a href="#" title=" Партньорство за открито управление" class="text-decoration-none mb-3">
                                    Актуални събития
                                </a>

                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 04.07.2023 г
                                    </span>

                                    <a href="#" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                        <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
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
                            <i class="fa-solid fa-file-pen dark-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <h3>Промяна в нормативната уредба на търговията на дребно с лекарствени продукти</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                        </a>
                                    </div>
                                </div>
                                <a href="#" title=" Партньорство за открито управление" class="text-decoration-none mb-3">
                                    Планирани заседания
                                </a>

                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 04.07.2023 г
                                    </span>

                                    <a href="#" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                        <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
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
                            <i class="fa-solid fa-file-lines dark-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <h3>Морски пространствен план на Република България 2021-2035 г.</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                        <a href="#">
                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                        </a>
                                        <a href="#">
                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                            </i>
                                        </a>
                                    </div>
                                </div>
                                <a href="#" title=" Партньорство за открито управление" class="text-decoration-none mb-3">
                                    Взети решения
                                </a>

                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 04.07.2023 г
                                    </span>

                                    <a href="#" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                        <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
                                    </a>
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
