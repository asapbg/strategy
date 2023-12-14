@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Профил на Потребител')

@section('content')
<div class="row mt-5">

    {слайдер} <br>
    {пътечки}
</div>
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>{Профил на Потребител}
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Обща информация</a>
                                </li>
                                <li class="mb-2"><a href="#"
                                        class="link-dark text-decoration-none">Абонаменти</a>
                                </li>
                                <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Законодателни
                                        инициативи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценки на
                                        въздействието</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Публикувани
                                        коментари</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 right-side-content py-5">
        <div class="filter-results mb-2">
            <h2 class="mb-4">
                Търсене
            </h2>
            <form id="filter" class="row" action="https://strategy.asapbg.com/legislative-initiatives" method="GET">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="mb-3 d-flex flex-column w-100">
                            <label for="keywords" class="form-label">Ключови думи</label>
                            <input id="keywords" class="form-control" name="keywords" type="text" value="">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="institution" class="form-label">Институция</label>
                            <!-- Номенклатура - взел съм html от оригиналната страница -->
                            <select name="inst" id="inst" class="form-select select2 multiple">
                                <option value="3">Всички</option>
                                <option value="1">Бизнес среда</option>
                                <option value="2">COVID-19</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="institution" class="form-label">Статус</label>
                            <!-- Номенклатура - взел съм html от оригиналната страница -->
                            <select name="inst2" id="inst2" class="form-select select2 multiple">
                                <option value="3">Всички</option>
                                <option value="1">Активен</option>
                                <option value="2">Неактивен</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row m-0 mb-5 action-btn-wrapper ">
                    <div class="col-md-12 px-0">
                        <button class="btn rss-sub main-color" type="submit"><i
                                class="fas fa-search main-color"></i>Търсене
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-3">
                <a href="https://strategy.asapbg.com/legislative-initiatives?order_by=keywords"
                    class="mb-0 text-decoration-none text-dark ">
                    <i class="fa-solid fa-sort me-2 "></i>Ключова дума
                </a>
            </div>

            <div class="col-md-3 cursor-pointer ">
                <a href="https://strategy.asapbg.com/legislative-initiatives?order_by=institutions"
                    class="mb-0 text-decoration-none text-dark ">
                    <i class="fa-solid fa-sort me-2 "></i>Институция
                </a>
            </div>

            <div class="col-md-3 cursor-pointer ">
                <a href="https://strategy.asapbg.com/legislative-initiatives?order_by=institutions"
                    class="mb-0 text-decoration-none text-dark ">
                    <i class="fa-solid fa-sort me-2 "></i>Статус
                </a>
            </div>

            <div class="col-md-3">
                <a href="https://strategy.asapbg.com/legislative-initiatives?order_by=date"
                    class="mb-0 text-decoration-none text-dark ">
                    <i class="fa-solid fa-sort me-2 "></i>Дата
                </a>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6 mt-2">
                <div class="info-consul text-start">
                    <p class="fw-600">
                        Общо 5 резултата
                    </p>
                </div>
            </div>
            <div class="col-md-6 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой резултати:</label>
                        <select class="form-select w-auto " name="paginate" id="list-paginate" data-container="#listContainer">
                                                    <option value="1" data-url="http://strategy.test/public-consultations?paginate=1">10</option>
                                                    <option value="20" data-url="http://strategy.test/public-consultations?paginate=20" selected="">5</option>
                                                    <option value="30" data-url="http://strategy.test/public-consultations?paginate=30">30</option>
                                                    <option value="40" data-url="http://strategy.test/public-consultations?paginate=40">40</option>
                                                    <option value="50" data-url="http://strategy.test/public-consultations?paginate=50">50</option>
                            </select>
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
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/2/view"
                                            class="text-decoration-none" title="Промяна в ЗАКОН за администрацията">
                                            <h3>Промяна в
                                                закон за администрацията</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                    </div>
                                </div>

                                <a href="#" title="" class="text-decoration-none text-capitalize mb-3">

                                </a>

                                <div class="status">
                                    <div class="meta-consul justify-content-start">
                                        <span class="me-2 mb-2"><strong>Статус:</strong>
                                            <span class="active-li">Активна</span>
                                        </span>

                                        <span class="item-separator mb-2">|</span>

                                        <span class="ms-2 mb-2">
                                            <strong> Подкрепена:</strong>
                                            <span class="voted-li">
                                                1
                                                път
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="text-secondary">
                                                    <i class="far fa-calendar text-secondary"></i> 22.11.2023г.
                                                </span>
                                            </div>

                                            <div class="col-auto">
                                                <div class="mb-0">
                                                    <!-- LIKES -->

                                                    1

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/2/vote/store/like"
                                                        class="me-2 text-decoration-none">
                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                    </a>


                                                    <!-- DISLIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/2/vote/store/dislike"
                                                        class="text-decoration-none">
                                                        <i
                                                            class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/2/view"
                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <i class="fas fa-arrow-right read-more">
                                                <span class="d-none"></span>
                                            </i>
                                        </a>
                                    </div>
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
                            <i class="fa-solid fa-hospital light-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/4/view"
                                            class="text-decoration-none"
                                            title="Промяна в ПМС за одобряване на допълнителни разходи/трансфери по бюджета на Министерството на образованието и науката за 2023 г. за изплащане на минимални диференцирани размери на паричните средства за физическа активност, физическо възпитание, спорт и спортно-туристическа дейност	МОН	НЕ	НЕ	АВГУСТ	СЕПТЕМВРИ">
                                            <h3>Промяна в
                                                пмс за одобряване на допълнителни разходи/трансфери по бюджета на
                                                министерството на образованието и науката за 2023 г. за изплащане на
                                                минимални диференцирани размери на паричните средства за физическа
                                                активност, физическо възпитание, спорт и спортно-туристическа дейност
                                                мон не не август септември</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                    </div>
                                </div>

                                <a href="#" title="" class="text-decoration-none text-capitalize mb-3">

                                </a>

                                <div class="status">
                                    <div class="meta-consul justify-content-start">
                                        <span class="me-2 mb-2"><strong>Статус:</strong>
                                            <span class="active-li">Активна</span>
                                        </span>

                                        <span class="item-separator mb-2">|</span>

                                        <span class="ms-2 mb-2">
                                            <strong> Подкрепена:</strong>
                                            <span class="voted-li">
                                                0
                                                пъти
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="text-secondary">
                                                    <i class="far fa-calendar text-secondary"></i> 13.12.2023г.
                                                </span>
                                            </div>

                                            <div class="col-auto">
                                                <div class="mb-0">
                                                    <!-- LIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/4/vote/store/like"
                                                        class="me-2 text-decoration-none">
                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                    </a>


                                                    <!-- DISLIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/4/vote/store/dislike"
                                                        class="text-decoration-none">
                                                        <i
                                                            class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/4/view"
                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <i class="fas fa-arrow-right read-more">
                                                <span class="d-none"></span>
                                            </i>
                                        </a>
                                    </div>
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
                            <i class="fa-solid fa-hospital light-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/5/view"
                                            class="text-decoration-none" title="Промяна в ЗАКОН за администрацията">
                                            <h3>Промяна в
                                                закон за администрацията</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                    </div>
                                </div>

                                <a href="#" title="" class="text-decoration-none text-capitalize mb-3">

                                </a>

                                <div class="status">
                                    <div class="meta-consul justify-content-start">
                                        <span class="me-2 mb-2"><strong>Статус:</strong>
                                            <span class="active-li">Активна</span>
                                        </span>

                                        <span class="item-separator mb-2">|</span>

                                        <span class="ms-2 mb-2">
                                            <strong> Подкрепена:</strong>
                                            <span class="voted-li">
                                                0
                                                пъти
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="text-secondary">
                                                    <i class="far fa-calendar text-secondary"></i> 13.12.2023г.
                                                </span>
                                            </div>

                                            <div class="col-auto">
                                                <div class="mb-0">
                                                    <!-- LIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/5/vote/store/like"
                                                        class="me-2 text-decoration-none">
                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                    </a>


                                                    <!-- DISLIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/5/vote/store/dislike"
                                                        class="text-decoration-none">
                                                        <i
                                                            class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/5/view"
                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <i class="fas fa-arrow-right read-more">
                                                <span class="d-none"></span>
                                            </i>
                                        </a>
                                    </div>
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
                            <i class="fa-solid fa-hospital light-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/6/view"
                                            class="text-decoration-none" title="Промяна в Тест Маги">
                                            <h3>Промяна в
                                                тест маги</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                    </div>
                                </div>

                                <a href="#" title="" class="text-decoration-none text-capitalize mb-3">

                                </a>

                                <div class="status">
                                    <div class="meta-consul justify-content-start">
                                        <span class="me-2 mb-2"><strong>Статус:</strong>
                                            <span class="active-li">Активна</span>
                                        </span>

                                        <span class="item-separator mb-2">|</span>

                                        <span class="ms-2 mb-2">
                                            <strong> Подкрепена:</strong>
                                            <span class="voted-li">
                                                0
                                                пъти
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="text-secondary">
                                                    <i class="far fa-calendar text-secondary"></i> 13.12.2023г.
                                                </span>
                                            </div>

                                            <div class="col-auto">
                                                <div class="mb-0">
                                                    <!-- LIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/6/vote/store/like"
                                                        class="me-2 text-decoration-none">
                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                    </a>


                                                    <!-- DISLIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/6/vote/store/dislike"
                                                        class="text-decoration-none">
                                                        <i
                                                            class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/6/view"
                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <i class="fas fa-arrow-right read-more">
                                                <span class="d-none"></span>
                                            </i>
                                        </a>
                                    </div>
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
                            <i class="fa-solid fa-hospital light-blue"></i>
                        </div>
                        <div class="consult-body">
                            <div href="#" class="consul-item">
                                <div class="consult-item-header d-flex justify-content-between">
                                    <div class="consult-item-header-link">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/3/view"
                                            class="text-decoration-none"
                                            title="Промяна в ПМС за одобряване на допълнителни разходи/трансфери по бюджета на Министерството на образованието и науката за 2023 г. за изплащане на минимални диференцирани размери на паричните средства за физическа активност, физическо възпитание, спорт и спортно-туристическа дейност	МОН	НЕ	НЕ	АВГУСТ	СЕПТЕМВРИ">
                                            <h3>Промяна в
                                                пмс за одобряване на допълнителни разходи/трансфери по бюджета на
                                                министерството на образованието и науката за 2023 г. за изплащане на
                                                минимални диференцирани размери на паричните средства за физическа
                                                активност, физическо възпитание, спорт и спортно-туристическа дейност
                                                мон не не август септември</h3>
                                        </a>
                                    </div>
                                    <div class="consult-item-header-edit">
                                    </div>
                                </div>

                                <a href="#" title="" class="text-decoration-none text-capitalize mb-3">

                                </a>

                                <div class="status">
                                    <div class="meta-consul justify-content-start">
                                        <span class="me-2 mb-2"><strong>Статус:</strong>
                                            <span class="closed-li">Затворена</span>
                                        </span>

                                        <span class="item-separator mb-2">|</span>

                                        <span class="ms-2 mb-2">
                                            <strong> Подкрепена:</strong>
                                            <span class="voted-li">
                                                0
                                                пъти
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="text-secondary">
                                                    <i class="far fa-calendar text-secondary"></i> 28.11.2023г.
                                                </span>
                                            </div>

                                            <div class="col-auto">
                                                <div class="mb-0">
                                                    <!-- LIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/3/vote/store/like"
                                                        class="me-2 text-decoration-none">
                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                    </a>


                                                    <!-- DISLIKES -->

                                                    0

                                                    <a href="https://strategy.asapbg.com/legislative-initiatives/3/vote/store/dislike"
                                                        class="text-decoration-none">
                                                        <i
                                                            class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <a href="https://strategy.asapbg.com/legislative-initiatives/3/view"
                                            title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                            <i class="fas fa-arrow-right read-more">
                                                <span class="d-none"></span>
                                            </i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <nav aria-label="Page navigation example">

            </nav>
        </div>
    </div>

</div>
</body>


@endsection
