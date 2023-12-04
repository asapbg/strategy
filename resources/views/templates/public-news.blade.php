@extends('layouts.site', ['fullwidth' => true])
<style>
     .public-page {
        padding: 0px 0px !important;
    }
</style>
@section('pageTitle', 'Новини')

@section('content')

<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Библиотека
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2  active-item-left p-1"><a href="#"
                                        class="link-dark text-decoration-none">Новини</a>
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
                            <option value="1">Държавна администрация</option>
                            <option value="1">България в ОИСР</option>
                            <option value="1">Правителствени</option>
                            <option value="1">Бизнес среда</option>
                        </select>
                    </div>
                </div>

                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Вид орган</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1">Министерски съвет</option>
                            <option value="1">Министър-председател</option>
                            <option value="1">Министър</option>
                            <option value="1">Държавна агенция</option>
                            <option value="1">Друг централен орган</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                        <input type="text" class="form-control" id="searchInTitle">
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
        <div class="row justify-content-end my-3">
            <div class="col-md-4">

            </div>
            <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
                    резултати:</label>
                <select class="form-select w-auto">
                    <option value="1">9</option>
                    <option value="1">20</option>
                    <option value="1">30</option>
                    <option value="1">40</option>
                    <option value="1">50</option>
                    <option value="1">100</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ms-2023.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">17.05.2023</span>
                    <h3 class="post-title">Съветът за административната реформа одобри Годишния доклад за оценка на
                        въздействието за 2022</h3>                       
                        <div class="row mb-2">
                            <div class="col-md-8">
                                <span class="blog-category">Държавна администрация</span>
                            </div>
                            <div class="col-md-4">
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
                        На 16.05.2023 г. на свое заседание Съветът за адм. реформа (САР), ръководен от вицепремиера Атанас Пеканов...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Съветът за административната реформа одобри Годишния доклад за оценка на
                    въздействието за 2022 г.">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/news-2.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">05.04.2023</span>
                    <h3 class="post-title">Представен е доклад „Икономически преглед на България“ на ОИСР.</h3>
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">България в ОИСР</span>
                        </div>
                        <div class="col-md-4">
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
                        На 4 април в Министерския съвет генералният секретар на Организацията за икономическо сътрудничество и развитие...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Представен е доклад „Икономически преглед на България“ на ОИСР.">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/news-3.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">21.11.2022</span>
                    <h3 class="post-title">Правителството одобри Първоначалния меморандум на РБ относно процеса по
                        присъединяване към ОИСР</h3>
                       <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">България в ОИСР</span>
                        </div>
                        <div class="col-md-4">
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
                        Министерският съвет одобри Първоначален меморандум на Република България във връзка с процеса на присъединяване на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Правителството одобри Първоначалния меморандум на РБ относно процеса по
                    присъединяване към ОИСР">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ms-2023.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">17.05.2023</span>
                    <h3 class="post-title">Съветът за административната реформа одобри Годишния доклад за оценка на
                        въздействието за 2022</h3>                       
                        <div class="row mb-2">
                            <div class="col-md-8">
                                <span class="blog-category">Държавна администрация</span>
                            </div>
                            <div class="col-md-4">
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
                        На 16.05.2023 г. на свое заседание Съветът за адм. реформа (САР), ръководен от вицепремиера Атанас Пеканов...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Съветът за административната реформа одобри Годишния доклад за оценка на
                    въздействието за 2022 г.">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/news-2.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">05.04.2023</span>
                    <h3 class="post-title">Представен е доклад „Икономически преглед на България“ на ОИСР.</h3>
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">България в ОИСР</span>
                        </div>
                        <div class="col-md-4">
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
                        На 4 април в Министерския съвет генералният секретар на Организацията за икономическо сътрудничество и развитие...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Представен е доклад „Икономически преглед на България“ на ОИСР.">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/news-3.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">21.11.2022</span>
                    <h3 class="post-title">Правителството одобри Първоначалния меморандум на РБ относно процеса по
                        присъединяване към ОИСР</h3>
                       <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">България в ОИСР</span>
                        </div>
                        <div class="col-md-4">
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
                        Министерският съвет одобри Първоначален меморандум на Република България във връзка с процеса на присъединяване на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Правителството одобри Първоначалния меморандум на РБ относно процеса по
                    присъединяване към ОИСР">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/ms-2023.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">17.05.2023</span>
                    <h3 class="post-title">Съветът за административната реформа одобри Годишния доклад за оценка на
                        въздействието за 2022</h3>                       
                        <div class="row mb-2">
                            <div class="col-md-8">
                                <span class="blog-category">Държавна администрация</span>
                            </div>
                            <div class="col-md-4">
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
                        На 16.05.2023 г. на свое заседание Съветът за адм. реформа (САР), ръководен от вицепремиера Атанас Пеканов...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Съветът за административната реформа одобри Годишния доклад за оценка на
                    въздействието за 2022 г.">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/news-2.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">05.04.2023</span>
                    <h3 class="post-title">Представен е доклад „Икономически преглед на България“ на ОИСР.</h3>
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">България в ОИСР</span>
                        </div>
                        <div class="col-md-4">
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
                        На 4 април в Министерския съвет генералният секретар на Организацията за икономическо сътрудничество и развитие...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Представен е доклад „Икономически преглед на България“ на ОИСР.">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="post-box">
                    <div class="post-img"><img src="/img/news-3.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date text-secondary">21.11.2022</span>
                    <h3 class="post-title">Правителството одобри Първоначалния меморандум на РБ относно процеса по
                        присъединяване към ОИСР</h3>
                       <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="blog-category">България в ОИСР</span>
                        </div>
                        <div class="col-md-4">
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
                        Министерският съвет одобри Първоначален меморандум на Република България във връзка с процеса на присъединяване на...
                    </p>
                    <a href="#" class="readmore stretched-link mt-1" title="Правителството одобри Първоначалния меморандум на РБ относно процеса по
                    присъединяване към ОИСР">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
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
