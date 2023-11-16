@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2  active-item-left p-1"><a href="#"
                                        class="link-dark text-decoration-none">Законодателни инициативи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
                                        управление</a>
                                </li>
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                    <ul class="list-unstyled ps-3">
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Планове
                                            </a></li>
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Отчети</a>
                                        </li>
                                        <hr class="custom-hr">
                                    </ul>
                                </ul>

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Анкети</a>
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
                        <label for="keywords" class="form-label">Ключови думи</label>
                        <input type="text" class="form-control" id="keywords">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="politic-range" class="form-label">Област на политика</label>
                        <select class="form-select" class="politic-range">
                            <option value="1">--</option>
                            <option value="1">Финанси и данъчна политика</option>
                            <option value="1">Партньорство за открито управление</option>
                            <option value="1">Енергетика</option>
                            <option value="1">Защита на потребителите</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="institution" class="form-label">Институция</label>
                        <select class="form-select" class="institution">
                        <option value="1">--</option>
                        <option value="1">Министерство на вътрешните работи</option>
                        <option value="1">Министерство на финансите</option>
                        <option value="1">Министерство на външните работи</option>
                        <option value="1">Министерство на правосъдието</option>
                    </select>
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
                <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добави инициатива</button>
            </div>
        </div>
        <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Ключова дума
                </p>
            </div>
            <div class="col-md-3 cursor-pointer ">
                <p class="mb-0">
                    <i class="fa-solid fa-sort me-2"></i>Област на политика
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Институция
                </p>
            </div>

            <div class="col-md-3">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Дата
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
                        <div class="consult-img-holder">
                            <i class="fa-solid fa-hospital light-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
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
                                    Здравеопазване
                                </a>
                          
                                 <div class="status mt-2">
                                    <div>
                                        <span>Статус: <span class="active-li">Активен</span></span>
                                        <span class="mx-1">|</span>
                                        <span>Подкрепено: <span class="voted-li">585 пъти</span></span>                     
                                    </div>
                                </div>   
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 12.09.2023 г
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
                                    Партньорство за открито управление
                                </a>
                          
                                 <div class="status mt-2">
                                    <div>
                                        <span>Статус: <span class="active-li">Активен</span></span>
                                        <span class="mx-1">|</span>
                                        <span>Подкрепено: <span class="voted-li">235 пъти</span></span>                     
                                    </div>
                                </div>   
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
                                            <h3>Как да накараме населението да се превърне в общество Единно, участващо в управлението на община?</h3>
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
                                    Партньорство за открито управление
                                </a>
                          
                                 <div class="status mt-2">
                                    <div>
                                        <span>Статус: <span class="active-li">Активен</span></span> 
                                        <span class="mx-1">|</span>
                                         <span>Подкрепено: <span class="voted-li">182 пъти</span></span>                
                                    </div>
                                </div>   
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 01.07.2023 г
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
                            <i class="fa-regular fa-file-lines dark-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Адекватни ли са административните услуги и съответно таксите?">
                                            <h3>Адекватни ли са административните услуги и съответно таксите?</h3>
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
                                <a href="#" title="Административни такси и услуги" class="text-decoration-none mb-3">
                                    Административни такси и услуги
                                </a>
                          
                                 <div class="status mt-2">
                                    <div>
                                        <span>Статус: <span class="send-li">Изпратено до администрация</span></span>
                                        <span class="mx-1">|</span>
                                         <span>Подкрепено: <span class="voted-li">84 пъти</span></span>                
                                    </div>
                                </div>   
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 21.05.2023 г
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
                            <i class="fa-solid fa-car gr-color"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="#" class="text-decoration-none" title="Адекватни ли са административните услуги и съответно таксите?">
                                            <h3>Относно промените чл. 55 от ЗМДТ</h3>
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
                                <a href="#" title="Транспорт" class="text-decoration-none mb-3">
                                    Транспорт
                                </a>
                          
                                 <div class="status mt-2">
                                    <div>
                                        <span>Статус: <span class="closed-li">Затворен</span></span>
                                        <span class="mx-1">|</span>
                                        <span>Подкрепено: <span class="voted-li">10 пъти</span></span>                   
                                    </div>
                                </div>   
                                <div class="meta-consul mt-2">
                                    <span class="text-secondary">
                                        <i class="far fa-calendar text-secondary me-1"></i> 21.01.2023 г
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
</body>


@endsection
