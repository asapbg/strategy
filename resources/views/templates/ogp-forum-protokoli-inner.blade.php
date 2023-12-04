@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'OGP Forum')



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
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Разработване на нов
                                        план за действие</a>
                                </li>
                                <li class="mb-2 active-item-left p-1"><a href="#"
                                        class="link-dark text-decoration-none">OGP FORUM</a>
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
        <div class="row">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">Дискусионен форум за "Протоколи от заседания и срещи"</h2>
            </div>
        </div>


        <div class="row mt-2 mb-3">
            <div class="col-md-8">
                <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i
                            class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>04.07.2023 г.</span>
                </a>
                <a href="#" class="text-decoration-none">
                    <span class="obj-icon-info me-2"><i class="bi bi-chat-dots me-1 dark-blue"
                            title="Сфера на действие"></i>Протоколи от заседания и срещи</span>
                </a>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на форум
                </button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="obj-comment comment-background p-2 rounded mb-3">
                    <div class="info">
                        <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                            <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Георги
                            Георгиев</span>
                        <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                        <a href="#">
                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button"
                                title="Изтриване"></i>
                        </a>
                    </div>
                    <div class="comment rounded py-2">
                        <p class="mb-2">Имате ли предложения за мерки, които ще допринесат за постигане целите на
                            инициативата? (за черпене на идеи за формулиране на предложенията може да се използва
                            Opening government: A guide to best practice in transparency, accountability and civic
                            engagement across the public sector)</p>
                        <div class="mb-0">
                            <a href="#" class="me-2 text-decoration-none">10<i
                                    class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                            <a href="#" class="text-decoration-none">1<i
                                    class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="obj-comment comment-background p-2 rounded mb-3">
                    <div class="info">
                        <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                            <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Петър Петров</span>
                        <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                        <a href="#">
                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button"
                                title="Изтриване"></i>
                        </a>
                    </div>
                    <div class="comment rounded py-2">
                        <p class="mb-2">До всички заинтересовани На портала strategy.bg е публикуван доклада по
                            независимия механизъм за оценка България във връзка с инициативата "Партньорство за открито
                            управление". Този доклад е основа и препоръка към България в подготовка на Втория план за
                            действие по инициативата 2014-2016. Всички заинтересовани имат възможност да коментират тук
                            или директно да изпращат своите предложения и препоръки до 10.04.2014 г. на
                            vpopova@mrrb.government.bg. Лице за контакт: Владима Попова, дирекция „Европейска
                            координация и международно сътрудничество“, Министерство на регионалното развитие, тел. 940
                            5 447.
                        </p>
                        <div class="mb-0">
                            <a href="#" class="me-2 text-decoration-none">3<i
                                    class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                            <a href="#" class="text-decoration-none">1<i
                                    class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="obj-comment comment-background p-2 rounded mb-3">
                    <div class="info">
                        <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                            <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Стоян Иванов </span>
                        <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                        <a href="#">
                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button"
                                title="Изтриване"></i>
                        </a>
                    </div>
                    <div class="comment rounded py-2">
                        <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция "Митници" стартира анкета
                            дали е необходимо да съществува фигурата "митнически агент". Моля гласувайте
                            http://customs.bg/bg/poll/10/
                        </p>
                        <div class="mb-0">
                            <a href="#" class="me-2 text-decoration-none">4<i
                                    class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                            <a href="#" class="text-decoration-none">5<i
                                    class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="custom-card p-3">
                    <h3 class="mb-3 fs-4">Добавете коментар</h3>
                    <div class="summernote-wrapper">
                        <textarea class="form-control mb-3 rounded summernote" id="exampleFormControlTextarea1" rows="2"
                            placeholder="Въведете коментар"></textarea>
                    </div>
                    <button class=" cstm-btn btn btn-primary login mt-3">Добавяне на коментар</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
