@extends('layouts.site')

@section('content')

<section id="slider" class="home-slider">
    <div id="carouselExampleSlidesOnly" class="carousel slide  bgr-main " data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="/img/ms-w-2023.jpg" alt="First slide">
                <div class="row">
                    <div class="col-md-12">
                        <div class="centered-heading w-100">
                            <h1 class="text-light text-center" style="background: unset !important;">
                                Добре дошли в Портала за обществени консултации
                            </h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section id="second-links" class="public-page ">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="mb-2">Информирай се</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 position-relative">
                <div class="service-item  position-relative">
                    <a href="{{ route('lp.index') }}" title="Законодателна програма">
                        <div class="icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3>Законодателна програма</h3>
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="service-item  position-relative">
                    <a href="{{ route('op.index') }}" title="Оперативна програма">
                        <div class="icon">
                            <i class="bi bi-arrow-up-right-circle"></i>
                        </div>
                        <h3>Оперативна програма</h3>
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="service-item  position-relative">
                    <a href="{{ route('strategy-documents.index') }}" title="Стратегически документи">
                        <div class="icon">
                            <i class="bi bi-files"></i>

                        </div>
                        <h3>Стратегически документи</h3>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 position-relative">
                <div class="service-item  position-relative">
                    <a href="#" title="Оценки на въздействието">
                        <div class="icon">
                            <i class="bi bi-journal-plus"></i>
                        </div>
                        <h3>Оценки на въздействието</h3>
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="service-item  position-relative">
                    <a href="{{ route('pris.index') }}" title="Актове на Министерски съвет">
                        <div class="icon">
                            <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <h3>Актове на Министерски съвет</h3>
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="service-item  position-relative">
                    <a href="#" title="Отворено управление">
                        <div class="icon">
                            <i class="bi bi-bounding-box-circles"></i>
                        </div>
                        <h3>Отворено управление</h3>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>


<section class="public-page public-constultation pb-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="mb-2">Участвай</h2>
            </div>
        </div>
        <div class="row ">

            <div class="col-lg-6 col-md-12">
                <a href="#" class="box-link gr-color-bgr mb-4">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi-check2-square text-light"></i>
                        </div>
                        <div class="link-heading">
                            <span>
                                Включи се в обществени консултации
                            </span>
                        </div>
                    </div>
                </a>
                <div class="home-results mt-2">

                    <div class=" item-holder-one">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <h2 class="mb-2" style="font-size: 24px;">Последни обществени консултации</h2>
                                <p>
                                    Този раздел е предназначен за граждани, които желаят да се включат в обществения
                                    дебат и да споделят
                                    мнението си.</p>

                            </div>
                        </div>

                        <div class="row filter-results mb-2">
                            <h3 style="font-size: 20px;">
                                Търсене
                            </h3>

                            <div class="col-md-12 mb-2 mt-2">
                                <input type="text" class="form-control" placeholder="Въведете дума или израз"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-8">
                                <button class="btn rss-sub main-color"><i
                                        class="fas fa-search main-color"></i>Търсене</button>
                            </div>

                            <div class="col-md-4">
                                <div class="info-consul">
                                    <h4>
                                        Общо 225 резултата
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="consul-wrapper">
                                    <div class="single-consultation d-flex">
                                        <div class="consult-img-holder">
                                            <i class="fa-solid fa-tractor gr-color"></i>
                                        </div>
                                        <div class="consult-body">
                                            <div href="#" class="consul-item">
                                                <div class="consult-item-header d-flex justify-content-between">
                                                    <div class="consult-item-header-link">
                                                        <a href="#" class="text-decoration-none"
                                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                            <h3 class="strip-header-words">Проект на Решение на
                                                                Министерския съвет за приемане на Национален план за
                                                                развитие на биологичното производство до 2030 г.</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a href="#" title="Финанси и данъчна политика"
                                                    class="text-decoration-none mb-3">
                                                    Земеделие и селски райони
                                                </a>

                                                <div class="meta-consul mt-2">
                                                    <span class="text-secondary">
                                                        4.7.2023 г. - 3.8.2023 г. | 1 <i
                                                            class="far fa-comment text-secondary"></i>
                                                    </span>

                                                    <a href="#"
                                                        title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
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
                                            <i class="fa-solid fa-euro-sign light-blue"></i>
                                        </div>
                                        <div class="consult-body">
                                            <div href="#" class="consul-item">
                                                <div class="consult-item-header d-flex justify-content-between">
                                                    <div class="consult-item-header-link">
                                                        <a href="#" class="text-decoration-none"
                                                            title="Проект на заповед, която се издава от директора на Агенция,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците">
                                                            <h3 class="strip-header-words">Проект на заповед, която се
                                                                издава от директора на Агенция,Митници“ на основание чл.
                                                                66б, ал. 2 от Закона за митниците</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a href="#" title="Финанси и данъчна политика"
                                                    class="text-decoration-none mb-3">

                                                    Финанси и данъчна политика
                                                </a>

                                                <div class="meta-consul mt-2">
                                                    <span class="text-secondary">
                                                        30.06.2023 г. - 30.07.2023 г. | 5 <i
                                                            class="far fa-comment text-secondary"></i>
                                                    </span>

                                                    <a href="#"
                                                        title="Проект на заповед, която се издава от директора на Агенция,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
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
                                                        <a href="#" class="text-decoration-none"
                                                            title="Проект на Закон за изменение и допълнение на Закона за адвокатурата">
                                                            <h3 class="strip-header-words">Проект на Закон за изменение
                                                                и допълнение на Закона за адвокатурата</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <a href="#" title="Бизнес среда" class="text-decoration-none mb-3">

                                                    Бизнес среда
                                                </a>


                                                <div class="meta-consul mt-2">
                                                    <span class="text-secondary">
                                                        03.07.2023 г. - 04.08.2023 г. | 12 <i
                                                            class="far fa-comment text-secondary"></i>
                                                    </span>
                                                    <a href="#"
                                                        title="Проект на Закон за изменение и допълнение на Закона за адвокатурата">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
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
                                            <i class="fa-solid fa-leaf gr-color"></i>
                                        </div>
                                        <div class="consult-body">
                                            <div href="#" class="consul-item">
                                                <div class="consult-item-header d-flex justify-content-between">
                                                    <div class="consult-item-header-link">
                                                        <a href="#" class="text-decoration-none"
                                                            title="Проект на Постановление на Министерския съвет за създаване на Консултативен съвет за Европейската зелена сделка">
                                                            <h3 class="strip-header-words">Проект на Постановление на
                                                                Министерския съвет за създаване на
                                                                Консултативен съвет за Европейската
                                                                зелена сделка</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <a href="#" title="Околна среда" class="text-decoration-none mb-3">

                                                    Околна среда
                                                </a>


                                                <div class="meta-consul mt-2">
                                                    <div>
                                                        <span class="text-secondary">
                                                            30.06.2023 г. - 14.07.2023 г. | 2 <i
                                                                class="far fa-comment text-secondary"></i>
                                                        </span>
                                                    </div>
                                                    <a href="#"
                                                        title="Проект на Постановление на Министерския съвет за създаване на Консултативен съвет за Европейската зелена сделка">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination m-0">
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
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
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <button class="btn rss-sub main-color">Всички консултации <i
                                        class="fas fa-long-arrow-right main-color"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="#" class="box-link light-blue-bgr my-4">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi bi-lightbulb text-light"></i>
                        </div>
                        <div class="link-heading">
                            <span>
                                Предложи мерки за добро управление
                            </span>
                        </div>
                    </div>
                </a>

                <div class="col-md-12 mt-4 custom-card p-3 mb-4">
                    <h3 class="mb-3" style="font-size: 24px;">Списък отворени планове</h3>
                    <ul class="list-group questionnaire">
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Финанси и данъчна политика</a>
                            <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Национална сигурност</a>
                            <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Земеделие и селски райони</a>
                            <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Околна среда</a>
                            <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-decoration-none">Бизнес среда</a>
                            <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                        </li>
                    </ul>
                    <button class="btn btn-primary main-color mt-4">Всички планове <i
                            class="fas fa-long-arrow-right main-color"></i></button>
                </div>


            </div>

            <div class="col-lg-6 col-md-12">
                <a href="#" class="box-link navy-marine-bgr  mb-4">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi-folder-check text-light"></i>
                        </div>
                        <div class="link-heading">
                            <span>
                                Предложи/Подкрепи законодателна инициатива
                            </span>
                        </div>
                    </div>
                </a>
                <div class="home-results mt-2">

                    <div class=" item-holder-one">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <h2 class="mb-2" style="font-size: 24px;">Списък отворени законодателни инициативи</h2>
                                <p>
                                    Този раздел е предназначен за граждани, които желаят да се включат в обществения
                                    дебат и да споделят
                                    мнението си.</p>

                            </div>
                        </div>

                        <div class="row filter-results mb-2">
                            <h3 style="font-size: 20px;">
                                Търсене
                            </h3>

                            <div class="col-md-12 mb-2 mt-2">
                                <input type="text" class="form-control" placeholder="Въведете дума или израз"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-8">
                                <button class="btn rss-sub main-color"><i
                                        class="fas fa-search main-color"></i>Търсене</button>
                            </div>

                            <div class="col-md-4">
                                <div class="info-consul">
                                    <h4>
                                        Общо 225 резултата
                                    </h4>
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
                                                        <a href="#" class="text-decoration-none"
                                                            title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                                            <h3 class="strip-header-words">Промяна в нормативната уредба
                                                                на търговията на дребно с лекарствени продукти</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a href="#" title=" Партньорство за открито управление"
                                                    class="text-decoration-none mb-3">
                                                    Здравеопазване
                                                </a>
                                                <div class="meta-consul mt-2">
                                                    <span>Подкрепено: <span class="voted-li">585 пъти</span></span>
                                                    <a href="#"
                                                        title="Проект на заповед, която се издава от директора на Агенция,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
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
                                                        <a href="#" class="text-decoration-none"
                                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                            <h3 class="strip-header-words">Участие на гражданите в обсъжданията на законопроектите
                                                                на Народното събрание.</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a href="#" title=" Партньорство за открито управление"
                                                    class="text-decoration-none mb-3">
                                                    Партньорство за открито управление
                                                </a>


                                                <div class="meta-consul mt-2">
                                                    <span>Подкрепено: <span class="voted-li">235 пъти</span></span>
                                                    <a href="#"
                                                        title="Проект на заповед, която се издава от директора на Агенция,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
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
                                                        <a href="#" class="text-decoration-none"
                                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                            <h3 class="strip-header-words">Как да накараме населението да се превърне в общество
                                                                Единно, участващо в управлението на община?</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a href="#" title=" Партньорство за открито управление"
                                                    class="text-decoration-none mb-3">
                                                    Партньорство за открито управление
                                                </a>


                                                <div class="meta-consul mt-2">
                                                    <span>Подкрепено: <span class="voted-li">182 пъти</span></span>

                                                    <a href="#"
                                                        title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
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
                                                        <a href="#" class="text-decoration-none"
                                                            title="Адекватни ли са административните услуги и съответно таксите?">
                                                            <h3 class="strip-header-words">Адекватни ли са административните услуги и съответно
                                                                таксите?</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        <a href="#">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                                role="button" title="Изтриване"></i>
                                                        </a>
                                                        <a href="#">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                                role="button" title="Редакция">
                                                            </i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <a href="#" title="Административни такси и услуги"
                                                    class="text-decoration-none mb-3">
                                                    Административни такси и услуги
                                                </a>
                                                <div class="meta-consul mt-2">
                                                    <span>Подкрепено: <span class="voted-li">84 пъти</span></span>

                                                    <a href="#"
                                                        title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                        <i class="fas fa-arrow-right read-more"><span
                                                                class="d-none">Линк</span></i>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination m-0">
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
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
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <button class="btn rss-sub main-color">Всички инициативи <i
                                        class="fas fa-long-arrow-right main-color"></i></button>
                            </div>
                        </div>

                    </div>

                    <a href="#" class="box-link dark-blue-bgr my-4">
                        <div class="info-box">
                            <div class="icon-wrap">
                                <i class="bi bi-patch-question text-light"></i>
                            </div>
                            <div class="link-heading">
                                <span>
                                    Участвай в анкета
                                </span>
                            </div>
                        </div>
                    </a>

                    <div class="col-md-12 mt-4 custom-card p-3">
                        <h3 class="mb-3" style="font-size: 24px;">Списък отворени анкети</h3>
                        <ul class="list-group questionnaire">
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Финанси и данъчна политика</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Национална сигурност</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Земеделие и селски райони</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Околна среда</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Бизнес среда</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                        </ul>
                        <button class="btn btn-primary main-color mt-4">Всички анкети <i
                                class="fas fa-long-arrow-right main-color"></i></button>

                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    </div>
</section>


{{--    <section id="usefull-links">--}}
{{--      <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-3">--}}

{{--              <a href="#" class="box-link gr-color-bgr">--}}
{{--                <div class="info-box">--}}
{{--                  <div class="icon-wrap">--}}
{{--                    <i class="fa fa-file-lines text-light"></i>--}}
{{--                  </div>--}}
{{--                  <div class="link-heading" style="background: #62b4a4;--}}
{{--                  filter: revert;">--}}
{{--                    <span>--}}
{{--                      Стандарти за провеждане на обществени консултации--}}
{{--                    </span>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </a>--}}

{{--            </div>--}}

{{--            <div class="col-md-3">--}}
{{--              <a href="#" class="box-link light-blue-bgr">--}}
{{--                <div class="info-box">--}}
{{--                  <div class="icon-wrap">--}}
{{--                    <i class="fa fa-calculator text-light"></i>--}}
{{--                  </div>--}}
{{--                  <div class="link-heading">--}}
{{--                    <span>--}}
{{--                      Интерактивен калкулатор на Административния товар--}}
{{--                    </span>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </a>--}}
{{--            </div>--}}

{{--            <div class="col-md-3">--}}
{{--              <a href="{{ route('impact_assessment.index') }}" class="box-link dark-blue-bgr">--}}
{{--                <div class="info-box">--}}
{{--                  <div class="icon-wrap">--}}
{{--                    <i class="fa fa-book text-light"></i>--}}
{{--                  </div>--}}
{{--                  <div class="link-heading">--}}
{{--                    <span>--}}
{{--                      Ръководство за извършване на оценка на въздействието--}}
{{--                    </span>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </a>--}}
{{--            </div>--}}

{{--            <div class="col-md-3">--}}

{{--              <a href="#" class="box-link navy-marine-bgr">--}}
{{--                <div class="info-box">--}}
{{--                  <div class="icon-wrap">--}}
{{--                    <i class="fa fa-list-check text-light"></i>--}}
{{--                  </div>--}}
{{--                  <div class="link-heading">--}}
{{--                    <span>--}}
{{--                      Списък на стратегически документи--}}
{{--                    </span>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </a>--}}

{{--            </div>--}}


{{--        </div>--}}
{{--      </div>--}}
{{--    </section>--}}
{{--    <section id="extra-links">--}}
{{--      <div class="container">--}}
{{--        <div class="row mb-4">--}}
{{--          <div class="col-md-12">--}}
{{--            <h2 class="mb-2">Полезна информация</h2>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="row">--}}
{{--          <div class="col-md-4">--}}

{{--            <a class="info-icon" href="#">--}}
{{--              <span class="info-icon-box">--}}
{{--                <i class="bi bi-shield-check dark-blue"></i>--}}
{{--              </span>--}}
{{--              <span class="info-icon-heading main-text-color">--}}
{{--                Законодателни програми--}}
{{--              </span>--}}

{{--              <span class="content-extra">--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат.--}}
{{--              </span>--}}
{{--            </a>--}}


{{--          </div>--}}

{{--          <div class="col-md-4">--}}
{{--            <a class="info-icon" href="#">--}}
{{--              <span class="info-icon-box">--}}
{{--                <i class="bi bi-arrow-up-right-circle main-text-color "></i>--}}
{{--              </span>--}}
{{--              <span class="info-icon-heading main-text-color">--}}
{{--                Оперативни програми--}}
{{--              </span>--}}
{{--              <span class="content-extra">--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат.--}}
{{--              </span>--}}
{{--            </a>--}}

{{--          </div>--}}

{{--          <div class="col-md-4">--}}
{{--            <a class="info-icon" href="#">--}}
{{--              <span class="info-icon-box">--}}
{{--                <i class="bi bi-people main-text-color "></i>--}}
{{--              </span>--}}
{{--              <span class="info-icon-heading main-text-color">--}}
{{--                Списък на изпълнители по ЗНА--}}
{{--              </span>--}}
{{--              <span class="content-extra">--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат.--}}
{{--              </span>--}}
{{--            </a>--}}
{{--          </div>--}}

{{--        </div>--}}


{{--        <div class="row mt-5">--}}
{{--          <div class="col-md-4">--}}

{{--            <a class="info-icon" href="#">--}}
{{--              <span class="info-icon-box">--}}
{{--                <i class="bi bi-journal-plus main-text-color"></i>--}}
{{--              </span>--}}
{{--              <span class="info-icon-heading main-text-color">--}}
{{--                Законодателни инициативи--}}
{{--              </span>--}}

{{--              <span class="content-extra">--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат.--}}
{{--              </span>--}}
{{--            </a>--}}


{{--          </div>--}}

{{--          <div class="col-md-4">--}}
{{--            <a class="info-icon" href="#">--}}
{{--              <span class="info-icon-box">--}}
{{--                <i class="bi bi-question-circle main-text-color"></i>--}}
{{--              </span>--}}
{{--              <span class="info-icon-heading main-text-color">--}}
{{--                Често задавани въпроси--}}
{{--              </span>--}}
{{--              <span class="content-extra">--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат.--}}
{{--              </span>--}}
{{--            </a>--}}

{{--          </div>--}}

{{--          <div class="col-md-4">--}}
{{--            <a class="info-icon" href="#">--}}
{{--              <span class="info-icon-box">--}}
{{--                <i class="bi bi-file-earmark-text main-text-color"></i>--}}
{{--              </span>--}}
{{--              <span class="info-icon-heading main-text-color">--}}
{{--                Анкети--}}
{{--              </span>--}}
{{--              <span class="content-extra">--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат.--}}
{{--              </span>--}}
{{--            </a>--}}
{{--          </div>--}}

{{--        </div>--}}
{{--      </div>--}}
{{--    </section>--}}
{{--    <section class="public-constultation">--}}
{{--      <div class="container all-consultations">--}}

{{--         <div class="col-lg-12  home-results  " style="padding: 0px !important;">--}}

{{--          <div class="row mb-4">--}}
{{--            <div class="col-md-12">--}}
{{--              <h2 class="mb-2">Последни обществени консултации</h2>--}}
{{--              <p>--}}
{{--                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат и да споделят мнението си. Позовава се на голям брой консултации, проведени в Интернет преди приемането на нормативни текстове.</p>--}}

{{--              <button class="btn rss-sub "><i class='fas fa-rss'></i>RSS Абониране</button>--}}
{{--            </div>--}}
{{--          </div>--}}

{{--          <div class="row filter-results mb-2">--}}
{{--            <h3 class="mb-4">--}}
{{--              Търсене--}}
{{--            </h3>--}}
{{--            <div class="col-md-3">--}}
{{--              <div class="input-group ">--}}
{{--                <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                  <label for="exampleFormControlInput1" class="form-label">Тип консултации</label>--}}
{{--                  <select class="form-select" aria-label="Default select example" >--}}
{{--                    <option selected ="1">Всички</option>--}}
{{--                    <option value="1">Национални</option>--}}
{{--                    <option value="2">Областни и общински</option>--}}
{{--                  </select>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-3">--}}
{{--              <div class="input-group ">--}}
{{--                <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                  <label for="exampleFormControlInput1" class="form-label">Избор на тема</label>--}}
{{--                  <select class="form-select" aria-label="Default select example" >--}}
{{--                    <option selected ="1">Всички</option>--}}
{{--                    <option value="2">Енергетика</option>--}}
{{--                    <option value="3">Защита на потребителите</option>--}}
{{--                    <option value="4">Здравеопазване</option>--}}
{{--                  </select>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-3">--}}
{{--              <div class="input-group ">--}}
{{--                <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                  <label for="exampleFormControlInput1" class="form-label">Статут</label>--}}
{{--                  <select class="form-select" aria-label="Default select example" >--}}
{{--                    <option selected ="1">Всички</option>--}}
{{--                    <option value="1">Открити</option>--}}
{{--                    <option value="2">Приключили</option>--}}
{{--                  </select>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-3">--}}
{{--              <div class="input-group ">--}}
{{--                <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                  <label for="exampleFormControlInput1" class="form-label">Сортиране</label>--}}
{{--                  <select class="form-select" aria-label="Default select example">--}}
{{--                    <option value="1">Най-нови</option>--}}
{{--                    <option value="2">Най-стари</option>--}}
{{--                  </select>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}

{{--          <div class="row">--}}
{{--            <div class="col-md-8">--}}
{{--              <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>--}}
{{--            </div>--}}

{{--            <div class="col-md-4">--}}
{{--              <div class="info-consul">--}}
{{--                <h4>--}}
{{--                  Общо 225 резултата--}}
{{--                </h4>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}

{{--            <div class="row mt-5">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                      <div class="consult-img-holder">--}}
{{--                        <i class="fa-solid fa-tractor gr-color"></i>--}}
{{--                      </div>--}}
{{--                      <div class="consult-body">--}}
{{--                        <a href="#" class="consul-item">--}}
{{--                          <p>--}}
{{--                            <span class="consul-cat">Земеделие и селски райони</span>--}}
{{--                          </p>--}}

{{--                          <h3>--}}
{{--                            Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.--}}
{{--                          </h3>--}}

{{--                          <div class="meta-consul">--}}
{{--                            <span class="text-secondary">--}}
{{--                              4.7.2023 г. -  3.8.2023 г. | 1 <i class="far fa-comment text-secondary"></i>--}}
{{--                            </span>--}}

{{--                            <i class="fas fa-arrow-right read-more"></i>--}}
{{--                          </div>--}}
{{--                        </a>--}}

{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              </div>--}}
{{--            </div>--}}


{{--            <div class="row">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                      <div class="consult-img-holder">--}}
{{--                        <i class="fa-solid fa-euro-sign light-blue"></i>--}}
{{--                      </div>--}}
{{--                      <div class="consult-body">--}}
{{--                        <a href="#" class="consul-item">--}}
{{--                          <p>--}}
{{--                            <span class="consul-cat">Финанси и данъчна политика</span>--}}
{{--                          </p>--}}

{{--                          <h3>--}}
{{--                            Проект на заповед, която се издава от директора на Агенция ,,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците--}}
{{--                          </h3>--}}

{{--                          <div class="meta-consul">--}}
{{--                            <span class="text-secondary">--}}
{{--                              30.06.2023 г. -  30.07.2023 г. | 5 <i class="far fa-comment text-secondary"></i>--}}
{{--                            </span>--}}

{{--                            <i class="fas fa-arrow-right read-more"></i>--}}
{{--                          </div>--}}
{{--                        </a>--}}

{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              </div>--}}
{{--            </div>--}}



{{--            <div class="row">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                      <div class="consult-img-holder">--}}
{{--                        <i class="fa-solid fa-user-tie dark-blue"></i>--}}
{{--                      </div>--}}
{{--                      <div class="consult-body">--}}
{{--                        <a href="#" class="consul-item">--}}
{{--                          <p>--}}
{{--                            <span class="consul-cat">Бизнес среда</span>--}}
{{--                          </p>--}}

{{--                          <h3>--}}
{{--                            Проект на Закон за изменение и допълнение на Закона за адвокатурата--}}
{{--                          </h3>--}}

{{--                          <div class="meta-consul">--}}

{{--                              <span class="text-secondary">--}}
{{--                                03.07.2023 г. -  04.08.2023 г. | 12 <i class="far fa-comment text-secondary"></i>--}}
{{--                              </span>--}}


{{--                            <div>--}}
{{--                              <i class="fas fa-arrow-right read-more"></i>--}}
{{--                            </div>--}}

{{--                          </div>--}}
{{--                        </a>--}}

{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              </div>--}}
{{--            </div>--}}


{{--            <div class="row ">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                      <div class="consult-img-holder">--}}
{{--                        <i class="fa-solid fa-leaf gr-color"></i>--}}
{{--                      </div>--}}
{{--                      <div class="consult-body">--}}
{{--                        <a href="#" class="consul-item">--}}
{{--                          <p>--}}
{{--                            <span class="consul-cat">Околна среда</span>--}}
{{--                          </p>--}}

{{--                          <h3>--}}
{{--                            Проект на Постановление на Министерския съвет за създаване на Консултативен съвет за Европейската зелена сделка--}}
{{--                          </h3>--}}

{{--                          <div class="meta-consul">--}}
{{--                            <div>--}}
{{--                              <span class="text-secondary">--}}
{{--                                30.06.2023 г. -  14.07.2023 г. | 2 <i class="far fa-comment text-secondary"></i>--}}
{{--                              </span>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                              <i class="fas fa-arrow-right read-more"></i>--}}
{{--                            </div>--}}
{{--                          </div>--}}
{{--                        </a>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--              <nav aria-label="Page navigation example">--}}
{{--                <ul class="pagination m-0">--}}
{{--                  <li class="page-item">--}}
{{--                    <a class="page-link" href="#" aria-label="Previous">--}}
{{--                      <span aria-hidden="true">&laquo;</span>--}}
{{--                      <span class="sr-only">Previous</span>--}}
{{--                    </a>--}}
{{--                  </li>--}}
{{--                  <li class="page-item active"><a class="page-link" href="#">1</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">...</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">57</a></li>--}}
{{--                  <li class="page-item">--}}
{{--                    <a class="page-link" href="#" aria-label="Next">--}}
{{--                      <span aria-hidden="true">&raquo;</span>--}}
{{--                      <span class="sr-only">Next</span>--}}
{{--                    </a>--}}
{{--                  </li>--}}
{{--                </ul>--}}
{{--              </nav>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--              <div class="col-md-8">--}}
{{--                <button class="btn rss-sub main-color">Всички консултации <i class="fas fa-long-arrow-right main-color"></i></button>--}}
{{--              </div>--}}
{{--          </div>--}}

{{--            <div class="row mb-4 mt-5">--}}
{{--              <div class="col-md-12">--}}
{{--                <h2 class="mb-2">Стратегически документи</h2>--}}
{{--                <p>--}}
{{--                  Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат и да споделят мнението си. Позовава се на голям брой консултации, проведени в Интернет преди приемането на нормативни--}}
{{--                  текстове.--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="row filter-results mb-2">--}}
{{--              <h3 class="mb-4">--}}
{{--                Търсене--}}
{{--              </h3>--}}
{{--              <div class="col-md-3">--}}
{{--                <div class="input-group ">--}}
{{--                  <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                    <label for="exampleFormControlInput1" class="form-label">Тип документ</label>--}}
{{--                    <select class="form-select" aria-label="Default select example" >--}}
{{--                      <option selected ="1">Всички</option>--}}
{{--                      <option value="1">Национални</option>--}}
{{--                      <option value="2">Областни и общински</option>--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}

{{--              <div class="col-md-3">--}}
{{--                <div class="input-group ">--}}
{{--                  <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                    <label for="exampleFormControlInput1" class="form-label">Избор на тема</label>--}}
{{--                    <select class="form-select" aria-label="Default select example" >--}}
{{--                      <option selected ="1">Всички</option>--}}
{{--                      <option value="2">Енергетика</option>--}}
{{--                      <option value="3">Защита на потребителите</option>--}}
{{--                      <option value="4">Здравеопазване</option>--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}

{{--              <div class="col-md-3">--}}
{{--                <div class="input-group ">--}}
{{--                  <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                    <label for="exampleFormControlInput1" class="form-label">Статут</label>--}}
{{--                    <select class="form-select" aria-label="Default select example" >--}}
{{--                      <option selected ="1">Всички</option>--}}
{{--                      <option value="1">Открити</option>--}}
{{--                      <option value="2">Приключили</option>--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}

{{--              <div class="col-md-3">--}}
{{--                <div class="input-group ">--}}
{{--                  <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                    <label for="exampleFormControlInput1" class="form-label">Сортиране</label>--}}
{{--                    <select class="form-select" aria-label="Default select example">--}}
{{--                      <option value="1">Най-нови</option>--}}
{{--                      <option value="2">Най-стари</option>--}}
{{--                    </select>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--              <div class="col-md-8">--}}
{{--                <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>--}}
{{--              </div>--}}

{{--              <div class="col-md-4">--}}
{{--                <div class="info-consul">--}}
{{--                  <h4>--}}
{{--                    Общо 100 резултата--}}
{{--                  </h4>--}}
{{--                </div>--}}
{{--              </div>--}}



{{--            <div class="row mt-5">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                    <div class="consult-img-holder">--}}
{{--                      <i class="bi bi-mortarboard-fill light-blue"></i>--}}
{{--                    </div>--}}
{{--                    <div class="consult-body">--}}
{{--                      <a href="#" class="consul-item">--}}
{{--                        <p>--}}
{{--                          <span class="consul-cat">Образование</span>--}}
{{--                        </p>--}}

{{--                        <h3>--}}
{{--                          Национални програми за развитие на образованието за 2023 г.--}}
{{--                        </h3>--}}

{{--                        <div class="meta-consul">--}}
{{--                          <span class="text-secondary">--}}
{{--                            4.7.2023 г. - 31.12.2024 г. | 1 <i class="far fa-comment text-secondary"></i>--}}
{{--                          </span>--}}

{{--                          <i class="fas fa-arrow-right read-more"></i>--}}
{{--                        </div>--}}
{{--                      </a>--}}

{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}


{{--            <div class="row">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                    <div class="consult-img-holder">--}}
{{--                      <i class="fa-solid fa-circle-nodes dark-blue"></i>--}}
{{--                    </div>--}}
{{--                    <div class="consult-body">--}}
{{--                      <a href="#" class="consul-item">--}}
{{--                        <p>--}}
{{--                          <span class="consul-cat">Регионална политика</span>--}}
{{--                        </p>--}}

{{--                        <h3>--}}
{{--                          Морски пространствен план на Република България 2021-2035 г.--}}
{{--                        </h3>--}}

{{--                        <div class="meta-consul">--}}
{{--                          <span class="text-secondary">--}}
{{--                            30.06.2023 г. - 31.12.2035 г. | 5 <i class="far fa-comment text-secondary"></i>--}}
{{--                          </span>--}}

{{--                          <i class="fas fa-arrow-right read-more"></i>--}}
{{--                        </div>--}}
{{--                      </a>--}}

{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}



{{--            <div class="row">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                    <div class="consult-img-holder">--}}
{{--                      <i class="bi bi-shield-fill-check gr-color"></i>--}}
{{--                    </div>--}}
{{--                    <div class="consult-body">--}}
{{--                      <a href="#" class="consul-item">--}}
{{--                        <p>--}}
{{--                          <span class="consul-cat">Външна политика, сигурност и отбрана</span>--}}
{{--                        </p>--}}

{{--                        <h3>--}}
{{--                          Национален план на Република България за развитие на способностите за управление на границите и за връщане на незаконно пребиваващи граждани на трети страни.--}}
{{--                        </h3>--}}

{{--                        <div class="meta-consul">--}}

{{--                          <span class="text-secondary">--}}
{{--                            11.05.2023 г. - Не е указан срок | 12 <i class="far fa-comment text-secondary"></i>--}}
{{--                          </span>--}}


{{--                          <div>--}}
{{--                            <i class="fas fa-arrow-right read-more"></i>--}}
{{--                          </div>--}}

{{--                        </div>--}}
{{--                      </a>--}}

{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}


{{--            <div class="row">--}}
{{--              <div class="col-md-12">--}}
{{--                <div class="consul-wrapper">--}}
{{--                  <div class="single-consultation d-flex">--}}
{{--                    <div class="consult-img-holder">--}}
{{--                      <i class="fa-solid fa-circle-nodes dark-blue"></i>--}}
{{--                    </div>--}}
{{--                    <div class="consult-body">--}}

{{--                      <a href="#" class="consul-item">--}}
{{--                        <p>--}}
{{--                          <span class="consul-cat">Регионална политика</span>--}}
{{--                        </p>--}}

{{--                        <h3>--}}
{{--                          Национална програма за енергийна ефективност на многофамилните жилищни сгради--}}
{{--                        </h3>--}}

{{--                        <div class="meta-consul">--}}
{{--                          <div>--}}
{{--                            <span class="text-secondary">--}}
{{--                              30.06.2023 г. - 14.07.2023 г. | 2 <i class="far fa-comment text-secondary"></i>--}}
{{--                            </span>--}}
{{--                          </div>--}}
{{--                          <div>--}}
{{--                            <i class="fas fa-arrow-right read-more"></i>--}}
{{--                          </div>--}}
{{--                        </div>--}}
{{--                      </a>--}}

{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--              <nav aria-label="Page navigation example">--}}
{{--                <ul class="pagination m-0">--}}
{{--                  <li class="page-item">--}}
{{--                    <a class="page-link" href="#" aria-label="Previous">--}}
{{--                      <span aria-hidden="true">&laquo;</span>--}}
{{--                      <span class="sr-only">Previous</span>--}}
{{--                    </a>--}}
{{--                  </li>--}}
{{--                  <li class="page-item active"><a class="page-link" href="#">1</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">...</a></li>--}}
{{--                  <li class="page-item"><a class="page-link" href="#">25</a></li>--}}
{{--                  <li class="page-item">--}}
{{--                    <a class="page-link" href="#" aria-label="Next">--}}
{{--                      <span aria-hidden="true">&raquo;</span>--}}
{{--                      <span class="sr-only">Next</span>--}}
{{--                    </a>--}}
{{--                  </li>--}}
{{--                </ul>--}}
{{--              </nav>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--              <div class="col-md-8">--}}
{{--                <button class="btn rss-sub main-color">Всички документи <i class="fas fa-long-arrow-right main-color"></i></button>--}}
{{--              </div>--}}
{{--          </div>--}}




{{--           </div>--}}
{{--        </div>--}}


{{--        </div>--}}
{{--      </div>--}}
{{--    </section>--}}

</section>
<section id="blog" class="public-page">
    <div class="container">

        <div class="row mb-4">
            <div class="col-md-12 ">
                <h2 class="mb-2">Най-новото</h2>
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
        </div>

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary">Всички новини <i
                        class="fas fa-long-arrow-right main-color"></i></button>
            </div>
        </div>
    </div>
</section>

@endsection
