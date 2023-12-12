@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Разработване на нов план за действие - Вътрешна страница')

@section('content')

<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
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
                                    <a href="#" class="text-light text-decoration-none">Разработване на нов план за
                                        действие</a>
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
                <img src="/img/ogp-img.png" class="img-fluid rounded mt-2" alt="OGP">
            </div>
        </div>

    </div>


    <div class="col-lg-10 py-5 right-side-content">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="obj-title mb-4">Примерна област за събиране на предложения</h2>
            </div>
        </div>


        <div class="row mt-2 mb-3 align-items-center">
            <div class="col-md-8">
                <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2">
                        <span class="active-ks me-2">Активен</span>
                        <i class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>04.07.2023 г. -
                        31.12.2024 г.
                    </span>
                </a>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на план за действие
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="add-suggestion">
                    <h3 class="fs-4 mb-3">
                        Добавяне на ново предложение
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="regulatory-act" class="form-label fw-600">Примерен списък 1</label>
                                    <select class="form-select" id="regulatory-act">
                                        <option value="1">--</option>
                                        <option value="1">Избор 1</option>
                                        <option value="1">Избор 2</option>
                                        <option value="1">Избор 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="regulatory-act" class="form-label fw-600">Примерен списък 2</label>
                                    <select class="form-select" id="regulatory-act">
                                        <option value="1">--</option>
                                        <option value="1">Избор 1</option>
                                        <option value="1">Избор 2</option>
                                        <option value="1">Избор 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description-change" class="form-label fw-600">Примерно поле 1</label>
                                    <div class="summernote-wrapper">
                                        <textarea name="suggestion" id="add-sugestion" class="summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description-change" class="form-label fw-600">Примерно поле 2</label>
                                    <div class="summernote-wrapper">
                                        <textarea name="suggestion" id="add-sugestion" class="summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description-change" class="form-label fw-600">Примерно поле 3</label>
                                    <div class="summernote-wrapper">
                                        <textarea name="suggestion" id="add-sugestion" class="summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description-change" class="form-label fw-600">Примерно поле 4</label>
                                    <div class="summernote-wrapper">
                                        <textarea name="suggestion" id="add-sugestion" class="summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description-change" class="form-label fw-600">Примерно поле 5</label>
                                    <div class="summernote-wrapper">
                                        <textarea name="suggestion" id="add-sugestion" class="summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column w-100">
                                    <label for="description-change" class="form-label fw-600">Примерно поле 6</label>
                                    <div class="summernote-wrapper">
                                        <textarea name="suggestion" id="add-sugestion" class="summernote"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <button class="btn btn-primary">
                                Добавяне на предложение
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 mt-5">
            <div class="col-md-12">
                <h2 class="obj-title">Oбразец за формуляра на предложение</h2>
                <p>
                    Изтеглете формуляра за предложение, който впоследствие трябва да изпратите. След изпращане предложението става видимо в тази секция.
                </p>

                <button class="btn btn-success">
                    <i class="fa fa-solid fa-download me-1"></i> Изтегляне на образец
                </button>
            </div>
        </div>


        <div class="row mb-4 mt-5">
            <div class="col-md-12">
                <h2 class="obj-title mb-4">Списък с направени предложения</h2>
                <div class="accordion" id="accordionExample">


                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button text-dark fs-18 fw-600" type="button" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Предложение №1 <span class="ms-1 fs-18 fw-normal">от Иван Иванов 10.10.2023г.</span>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="accordion-body">

                                <div class="custom-card p-3 mb-2">
                                    <div class="row mb-3">
                                        <div class="suggestion-content mb-2 ">
                                            <div class="row br-30">
                                                <div class="new-plan-author">
                                                    <div class="info mb-4">
                                                        <p class="fw-600 fs-5 mb-1">Автор на предложението: </p>
                                                        <a href="#" class="text-decoration-none">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                                Иван Иванов
                                                            </span>
                                                            <span class="obj-icon-info me-2 text-muted">10.10.2023г. 19:05</span>
                                                        </a>  
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерен списък 1
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерен списък 2
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 1
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 2
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 3
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 4
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 5
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 6
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <hr class="custom-hr">
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="obj-comment comment-background p-2 rounded mb-3">
                                                <div class="info">
                                                    <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                        <i class="fa fa-solid fa-circle-user me-2 main-color"
                                                            title="Автор"></i>Георги
                                                        Георгиев</span>
                                                    <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                                                    <a href="#">
                                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                            role="button" title="Изтриване"></i>
                                                    </a>
                                                </div>
                                                <div class="comment rounded py-2">
                                                    <p class="mb-2">Имате ли предложения за мерки, които ще допринесат
                                                        за постигане целите
                                                        на
                                                        инициативата? (за черпене на идеи за формулиране на
                                                        предложенията може да се
                                                        използва
                                                        Opening government: A guide to best practice in transparency,
                                                        accountability and
                                                        civic
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
                                                        <i class="fa fa-solid fa-circle-user me-2 main-color"
                                                            title="Автор"></i>Стоян Иванов
                                                    </span>
                                                    <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                                                    <a href="#">
                                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                            role="button" title="Изтриване"></i>
                                                    </a>
                                                </div>
                                                <div class="comment rounded py-2">
                                                    <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция
                                                        "Митници" стартира
                                                        анкета
                                                        дали е необходимо да съществува фигурата "митнически агент".
                                                        Моля гласувайте
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
                                        <div class="col-md-12">
                                            <h3 class="mb-3 fs-4">Добавете коментар</h3>
                                            <div class="summernote-wrapper">
                                                <textarea class="form-control mb-3 rounded summernote"
                                                    id="exampleFormControlTextarea1" rows="2"
                                                    placeholder="Въведете коментар">
                                                  </textarea>

                                            </div>
                                            <button class=" cstm-btn btn btn-primary login mt-3">Добавяне на
                                                коментар</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed text-dark fs-18 fw-600" type="button" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Предложение №2 <span class="ms-1 fs-18 fw-normal">от Николай Николов 12.10.2023г.</span>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-parent="#accordionExample">
                            <div class="accordion-body">

                                <div class="custom-card p-3 mb-2">
                                    <div class="row mb-3">  
                                        <div class="suggestion-content mb-2 ">
                                            <div class="row br-30">
                                                <div class="new-plan-author">
                                                    <div class="info mb-4">
                                                        <p class="fw-600 fs-5 mb-1">Автор на предложението: </p>
                                                        <a href="#" class="text-decoration-none">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>
                                                                Николай Николов
                                                            </span>
                                                            <span class="obj-icon-info me-2 text-muted">12.10.2023г. 14:25</span>
                                                        </a>  
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерен списък 1
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерен списък 2
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 1
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 2
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 3
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 4
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 5
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <p class="fs-18 fw-600 mb-2">
                                                        Примерно поле 6
                                                    </p>
                                                    <p class="mb-0">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                                        enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                                        nisi ut aliquip ex ea commodo consequat.
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <hr class="custom-hr">
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="obj-comment comment-background p-2 rounded mb-3">
                                                <div class="info">
                                                    <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                        <i class="fa fa-solid fa-circle-user me-2 main-color"
                                                            title="Автор"></i>Георги
                                                        Георгиев</span>
                                                    <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                                                    <a href="#">
                                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                            role="button" title="Изтриване"></i>
                                                    </a>
                                                </div>
                                                <div class="comment rounded py-2">
                                                    <p class="mb-2">Имате ли предложения за мерки, които ще допринесат
                                                        за постигане целите
                                                        на
                                                        инициативата? (за черпене на идеи за формулиране на
                                                        предложенията може да се
                                                        използва
                                                        Opening government: A guide to best practice in transparency,
                                                        accountability and
                                                        civic
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
                                                        <i class="fa fa-solid fa-circle-user me-2 main-color"
                                                            title="Автор"></i>Стоян Иванов
                                                    </span>
                                                    <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                                                    <a href="#">
                                                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                            role="button" title="Изтриване"></i>
                                                    </a>
                                                </div>
                                                <div class="comment rounded py-2">
                                                    <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция
                                                        "Митници" стартира
                                                        анкета
                                                        дали е необходимо да съществува фигурата "митнически агент".
                                                        Моля гласувайте
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
                                        <div class="col-md-12">
                                            <h3 class="mb-3 fs-4">Добавете коментар</h3>
                                            <div class="summernote-wrapper">
                                                <textarea class="form-control mb-3 rounded summernote"
                                                    id="exampleFormControlTextarea1" rows="2"
                                                    placeholder="Въведете коментар">
                                                  </textarea>

                                            </div>
                                            <button class=" cstm-btn btn btn-primary login mt-3">Добавяне на
                                                коментар</button>
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
</div>
@endsection
