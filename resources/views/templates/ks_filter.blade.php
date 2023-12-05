@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Консултативни съвети')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Консултативни съвети
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Контакти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Актуална информация и събития</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини</a>
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

            <div class="col-md-6">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Област на политика</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1">Финанси и данъчна политика</option>
                            <option value="1">Партньорство за открито управление</option>
                            <option value="1">Енергетика</option>
                            <option value="1">Защита на потребителите</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
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

            <div class="col-md-6">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Акт на създаване</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="2">Закон</option>
                            <option value="3">Постановление на Министерския съвет (на основание чл. 21 от Закона за
                                администрацията)</option>
                            <option value="4">Заповед на председател на държавна агенция (на основание, чл. 47, ал.
                                8 от Закона за администрацията)</option>
                            <option value="5">Акт на друг централен орган</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Председател на съвета</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="2">Министър-председател</option>
                            <option value="3">Заместник министър-председател</option>
                            <option value="4">Министър</option>
                            <option value="5">Председател на държавна агенция</option>
                            <option value="6">Друго свободно добавяне в номенклатурата</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Наличие на представител на НПО</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1">Представител/и на национално представителните организации на
                                работодателите
                            </option>
                            <option value="2">Представител/и на национално представителните организации на работниците и
                                служителите;</option>
                            <option value="3">Представител/и на академичната общност</option>
                            <option value="4">Представител/и на местното самоуправление/на Националното сдружение на
                                общините в
                                Република България</option>
                            <option value="5">Представител/и на бизнеса</option>
                            <option value="6">Представител/и на държавни органи</option>
                            <option value="6">Други</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Статус</label>
                        <select class="form-select select2" multiple aria-label="Default select example">
                            <option value="1">Всички</option>
                            <option value="1" selected>Активни</option>
                            <option value="1">Неактивни</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-5">
            <div class="col-md-4">
                <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
            </div>
            <div class="col-md-8 text-end">
                <button class="btn btn-primary main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
                    Абониране</button>
                <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                <button class="btn btn-success text-success"><i
                        class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>

            </div>
        </div>

        <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer d-flex align-items-center">
                    <i class="fa-solid fa-sort me-2"></i>
                    <select class="form-select">
                        <option value="1">Област на политика</option>
                        <option value="1">Финанси и данъчна политика</option>
                        <option value="1">Партньорство за открито управление</option>
                        <option value="1">Енергетика</option>
                        <option value="1">Защита на потребителите</option>
                    </select>
                </p>
            </div>
            <div class="col-md-2 cursor-pointer ">
                <p class="mb-0 cursor-pointer d-flex align-items-center">
                    <i class="fa-solid fa-sort me-2"></i>
                    <select class="form-select">
                        <option value="1">Вид орган</option>
                        <option value="1">Министерски съвет</option>
                        <option value="1">Министър-председател</option>
                        <option value="1">Министър</option>
                        <option value="1">Държавна агенция</option>
                        <option value="1">Друг централен орган</option>
                    </select>
                </p>
            </div>


            <div class="col-md-2">
                <p class="mb-0 cursor-pointer d-flex align-items-center">
                    <i class="fa-solid fa-sort me-2"></i>
                    <select class="form-select">
                        <option value="1">Акт на създаване</option>
                        <option value="2">Закон</option>
                        <option value="3">Постановление на Министерския съвет (на основание чл. 21 от Закона за
                            администрацията)</option>
                        <option value="4">Заповед на председател на държавна агенция (на основание, чл. 47, ал.
                            8 от Закона за администрацията)</option>
                        <option value="5">Акт на друг централен орган</option>
                    </select>
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer d-flex align-items-center">
                    <i class="fa-solid fa-sort me-2"></i>
                    <select class="form-select">
                        <option value="1">Председател на съвета</option>
                        <option value="2">Министър-председател</option>
                        <option value="3">Заместник министър-председател</option>
                        <option value="4">Министър</option>
                        <option value="5">Председател на държавна агенция</option>
                        <option value="6">Друго свободно добавяне в номенклатурата</option>
                    </select>
                </p>
            </div>
            <div class="col-md-2">
                <p class="mb-0 cursor-pointer d-flex align-items-center">
                    <i class="fa-solid fa-sort me-2"></i>
                    <select class="form-select">
                        <option value="1">Представител</option>
                        <option value="1">Представител/и на национално представителните организации на
                            работодателите
                        </option>
                        <option value="2">Представител/и на национално представителните организации на работниците и
                            служителите;</option>
                        <option value="3">Представител/и на академичната общност</option>
                        <option value="4">Представител/и на местното самоуправление/на Националното сдружение на
                            общините в
                            Република България</option>
                        <option value="5">Представител/и на бизнеса</option>
                        <option value="6">Представител/и на държавни органи</option>
                        <option value="6">Други</option>
                    </select>
                </p>

            </div>
        </div>
        <div class="row justify-content-end my-3">
            <div class="col-md-4">
                <!-- Бутонът експорт да е видим само след зареждане на резултати        
            <button class="btn btn-primary main-color"><i
              class="fa-solid fa-download main-color me-1"></i>Експорт</button>
               -->
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
                        <div class="consult-body">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="https://strategy.asapbg.com/pris/1" class="text-decoration-none"
                                        title="Постановление №52 на Министерския съвет от 2023 г.">
                                        <h3>Висш експертен екологичен съвет
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
                            <div class="meta-consul">
                                <span>Статус: <span class="active-ks">Активен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
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
                        <div class="consult-body">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="https://strategy.asapbg.com/pris/1" class="text-decoration-none"
                                        title="Постановление №52 на Министерския съвет от 2023 г.">
                                        <h3>Консултативeн съвет за сътрудничество между държавните органи и лицата,
                                            осъществяващи икономически дейности, свързани с нефт и продукти от нефтен
                                            произход
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
                            <div class="meta-consul">
                                <span>Статус: <span class="active-ks">Активен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
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
                        <div class="consult-body">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="https://strategy.asapbg.com/pris/1" class="text-decoration-none"
                                        title="Постановление №52 на Министерския съвет от 2023 г.">
                                        <h3>Висш консултативен съвет по водите
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
                            <div class="meta-consul">
                                <span>Статус: <span class="active-ks">Активен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
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
                        <div class="consult-body">
                            <div class="consult-item-header d-flex justify-content-between">
                                <div class="consult-item-header-link">
                                    <a href="https://strategy.asapbg.com/pris/1" class="text-decoration-none"
                                        title="Постановление №52 на Министерския съвет от 2023 г.">
                                        <h3>Консултативен съвет за насърчаване на малките и средните предприятия
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
                            <div class="meta-consul">
                                <span>Статус: <span class="active-ks">Активен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
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
                    <li class="page-item"><a class="page-link" href="#">25</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">»</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>



        <!-- Дизайн за НЕАКТИВЕН КС
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="consul-wrapper">
                    <div class="single-consultation d-flex">
                        <div class="consult-body">
                            <a href="#" class="consul-item">
                            </a>
                            <p class="mb-1"><a href="#" class="consul-item">
                                </a><a href="#" class="main-color text-decoration-none fs-5">Съвет за развитие</a>
                            </p>
                            <div class="meta-consul">
                                <span>Статус: <span class="inactive-ks">Неактивен</span></span>
                                <a href="#"><i class="fas fa-arrow-right read-more text-end"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      -->
    </div>

</div>

@endsection
