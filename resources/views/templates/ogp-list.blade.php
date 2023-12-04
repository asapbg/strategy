@extends('layouts.site', ['fullwidth' => true])
<style>
     .public-page {
        padding: 0px 0px !important;
    }
</style>
@section('pageTitle', 'Партньорство за открито управление')

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

                                <li class="mb-2"><a href="#"
                                        class="link-dark text-decoration-none">Национални планове за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценка за изпълнението на плановете за действие - мониторинг</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Разработване на нов план за действие</a>
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


    <div class="col-lg-10 py-5 right-side-content">
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
                            <option value="1">Национални планове за действие</option>
                            <option value="1">Оценка за изпълнението на плановете за действие - мониторинг</option>
                            <option value="1">Разработване на нов план за действие</option>
                        </select>
                    </div>
                </div>
            </div>
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
                        <label for="exampleFormControlInput1" class="form-label">Дата от:</label>
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
                        <label for="exampleFormControlInput1" class="form-label">Дата до:</label>
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
                        <select class="form-select" id="paginationResults">
                            <option value="10" selected>9</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                        </select>
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
            <div class="col-md-4">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Категория
            </div>
            <div class="col-md-4">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Заглавие
            </div>
            <div class="col-md-4 cursor-pointer ">
                <p class="mb-0">
                    <i class="fa-solid fa-sort me-2"></i>Дата на публикуване
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

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>                    <span class="post-date text-secondary">11.09.2023</span>
                    <h3 class="post-title">България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин</h3>                       
                        <div class="row mb-2">
                            <div class="col-md-10">
                                <span class="blog-category">Национални планове за действие</span>
                            </div>
                            <div class="col-md-2">
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
                        </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        Две хиляди участници от 75 страни се включиха в годишната среща на Глобалната инициатива „Партньорство за открито...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>                    <span class="post-date text-secondary">08.09.2023</span>
                    <h3 class="post-title">Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“</h3>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Оценка за изпълнението на плановете за действие</span>
                        </div>
                        <div class="col-md-2">
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
                    </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        С решение на Министерския съвет вицепремиерът Мария Габриел бе определена за координатор за участието на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>
                    <span class="post-date text-secondary">11.05.2023</span>
                    <h3 class="post-title">България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“</h3>
                       <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Разработване на нов план за действие</span>
                        </div>
                        <div class="col-md-2">
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
                    </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        С публична дискусия на тема „Открито управление – цел и фокус на доброто управление“ страната ни се...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>                    <span class="post-date text-secondary">11.09.2023</span>
                    <h3 class="post-title">България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин</h3>                       
                        <div class="row mb-2">
                            <div class="col-md-10">
                                <span class="blog-category">Национални планове за действие</span>
                            </div>
                            <div class="col-md-2">
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
                        </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        Две хиляди участници от 75 страни се включиха в годишната среща на Глобалната инициатива „Партньорство за открито...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>                    <span class="post-date text-secondary">08.09.2023</span>
                    <h3 class="post-title">Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“</h3>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Оценка за изпълнението на плановете за действие</span>
                        </div>
                        <div class="col-md-2">
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
                    </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        С решение на Министерския съвет вицепремиерът Мария Габриел бе определена за координатор за участието на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>
                    <span class="post-date text-secondary">11.05.2023</span>
                    <h3 class="post-title">България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“</h3>
                       <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Разработване на нов план за действие</span>
                        </div>
                        <div class="col-md-2">
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
                    </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        С публична дискусия на тема „Открито управление – цел и фокус на доброто управление“ страната ни се...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>                    <span class="post-date text-secondary">11.09.2023</span>
                    <h3 class="post-title">България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин</h3>                       
                        <div class="row mb-2">
                            <div class="col-md-10">
                                <span class="blog-category">Национални планове за действие</span>
                            </div>
                            <div class="col-md-2">
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
                        </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        Две хиляди участници от 75 страни се включиха в годишната среща на Глобалната инициатива „Партньорство за открито...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="България се включи в годишната среща на Глобалната инициатива „Партньорство за открито управление“ в Талин">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>                    <span class="post-date text-secondary">08.09.2023</span>
                    <h3 class="post-title">Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“</h3>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Оценка за изпълнението на плановете за действие</span>
                        </div>
                        <div class="col-md-2">
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
                    </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        С решение на Министерския съвет вицепремиерът Мария Габриел бе определена за координатор за участието на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Вицепремиерът Мария Габриел поема националната координация по глобалната инициатива „Партньорство за открито управление“">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ogp-img.png" class="img-fluid" alt="OGP"></div>
                    <span class="post-date text-secondary">11.05.2023</span>
                    <h3 class="post-title">България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“</h3>
                       <div class="row mb-2">
                        <div class="col-md-10">
                            <span class="blog-category">Разработване на нов план за действие</span>
                        </div>
                        <div class="col-md-2">
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
                    </div>
                      <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                    <p class="short-decription text-secondary">                    
                        С публична дискусия на тема „Открито управление – цел и фокус на доброто управление“ страната ни се...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="България се включва в Седмицата на откритото управление по глобалната инициатива „Партньорство за открито управление“">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

        </div>
        <div class="row mb-4">
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
