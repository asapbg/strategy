@extends('layouts.site')
@section('content')
<section id="slider">
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">


                <img class="d-block w-100" src="../images/ms-2023.jpg" alt="First slide">

                <div class="row">
                    <div class="col-md-12">
                        <div class="centered-heading w-100">
                            <h1 class="text-light text-center">
                                Добре дошли в сайта на Портал за обществени консултации
                            </h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<section class="public-constultation">
    <div class="container-fluid">
        <div class="row justify-content-center">

            <div class="col-lg-3">
                <section id="usefull-links-two">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col-md-12 mb-4">

                                <a href="#" class="box-link gradient-one">
                                    <div class="info-box">
                                        <div class="icon-wrap">
                                            <i class="bi bi-shield-check text-light"></i>
                                        </div>
                                        <div class="link-heading">
                        <span>
                          Законодателни програми
                        </span>
                                        </div>
                                    </div>
                                </a>

                            </div>

                            <div class="col-md-12  mb-4">
                                <a href="#" class="box-link gradient-two">
                                    <div class="info-box">
                                        <div class="icon-wrap">
                                            <i class="bi bi-arrow-up-right-circle  text-light"></i>
                                        </div>
                                        <div class="link-heading">
                        <span >
                         Оперативни програми
                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-12 mb-4">
                                <a href="#" class="box-link gradient-three">
                                    <div class="info-box">
                                        <div class="icon-wrap">
                                            <i class="bi bi-journal-plus  text-light"></i>
                                        </div>
                                        <div class="link-heading">
                        <span>
                          Законодателни инициативи
                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-12">

                                <a href="#" class="box-link gradient-four">
                                    <div class="info-box">
                                        <div class="icon-wrap">
                                            <i class="bi bi-file-earmark-text text-light"></i>
                                        </div>
                                        <div class="link-heading">
                        <span>
                          Анкети
                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>


                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-4  home-results home-results-two fix-one" >

                <div class=" item-holder-one">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h2 class="mb-2" style="font-size: 24px;">Обществени консултации</h2>
                            <p>
                                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат и да споделят
                                мнението си.</p>

                        </div>
                    </div>

                    <div class="row filter-results mb-2">
                        <h3 class="mb-2" style="font-size: 20px;">
                            Търсене
                        </h3>
                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Тип консултации</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected="1">Всички</option>
                                        <option value="1">Национални</option>
                                        <option value="2">Областни и общински</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Избор на тема</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected="1">Всички</option>
                                        <option value="2">Енергетика</option>
                                        <option value="3">Защита на потребителите</option>
                                        <option value="4">Здравеопазване</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Статут</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected="1">Всички</option>
                                        <option value="1">Открити</option>
                                        <option value="2">Приключили</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Сортиране</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option value="1">Най-нови</option>
                                        <option value="2">Най-стари</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                        </div>

                        <div class="col-md-4">
                            <div class="info-consul">
                                <h4>
                                    Общо 225 резултата
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-tractor gr-color"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Земеделие и селски райони</span>
                                            </p>

                                            <h3>
                                                Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното
                                                производство до 2030 г.
                                            </h3>

                                            <div class="meta-consul">
                          <span class="text-secondary">
                            4.7.2023 г. - 3.8.2023 г. | 1 <i class="far fa-comment text-secondary"></i>
                          </span>

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
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-euro-sign light-blue"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Финанси и данъчна политика</span>
                                            </p>

                                            <h3>
                                                Проект на заповед, която се издава от директора на Агенция ,,Митници“ на основание чл. 66б, ал. 2
                                                от Закона за митниците
                                            </h3>

                                            <div class="meta-consul">
                          <span class="text-secondary">
                            30.06.2023 г. - 30.07.2023 г. | 5 <i class="far fa-comment text-secondary"></i>
                          </span>

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
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-user-tie dark-blue"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Бизнес среда</span>
                                            </p>

                                            <h3>
                                                Проект на Закон за изменение и допълнение на Закона за адвокатурата
                                            </h3>

                                            <div class="meta-consul">

                          <span class="text-secondary">
                            03.07.2023 г. - 04.08.2023 г. | 12 <i class="far fa-comment text-secondary"></i>
                          </span>


                                                <div>
                                                    <i class="fas fa-arrow-right read-more"></i>
                                                </div>

                                            </div>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row ">
                        <div class="col-md-12">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-leaf gr-color"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Околна среда</span>
                                            </p>

                                            <h3>
                                                Проект на Постановление на Министерския съвет за създаване на Консултативен съвет за Европейската
                                                зелена сделка
                                            </h3>

                                            <div class="meta-consul">
                                                <div>
                            <span class="text-secondary">
                              30.06.2023 г. - 14.07.2023 г. | 2 <i class="far fa-comment text-secondary"></i>
                            </span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-arrow-right read-more"></i>
                                                </div>
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



            <div class="col-lg-4 home-results-two home-results fix-two">

                <div class="item-holder-two">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h2 class="mb-2" style="font-size: 24px;">Стратегически документи</h2>
                            <p>
                                Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат и да споделят
                                мнението си.
                        </div>
                    </div>


                    <div class="row filter-results mb-2">
                        <h3 class="mb-2" style="font-size: 20px;">
                            Търсене
                        </h3>
                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Тип документ</label>
                                    <select class="form-select" aria-label="Default select example" >
                                        <option selected ="1">Всички</option>
                                        <option value="1">Национални</option>
                                        <option value="2">Областни и общински</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Избор на тема</label>
                                    <select class="form-select" aria-label="Default select example" >
                                        <option selected ="1">Всички</option>
                                        <option value="2">Енергетика</option>
                                        <option value="3">Защита на потребителите</option>
                                        <option value="4">Здравеопазване</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Статут</label>
                                    <select class="form-select" aria-label="Default select example" >
                                        <option selected ="1">Всички</option>
                                        <option value="1">Открити</option>
                                        <option value="2">Приключили</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group ">
                                <div class="mb-3 d-flex flex-column  w-100">
                                    <label for="exampleFormControlInput1" class="form-label">Сортиране</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option value="1">Най-нови</option>
                                        <option value="2">Най-стари</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-8">
                            <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                        </div>

                        <div class="col-md-4">
                            <div class="info-consul">
                                <h4>
                                    Общо 100 резултата
                                </h4>
                            </div>
                        </div>
                    </div>



                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-img-holder">
                                        <i class="bi bi-mortarboard-fill light-blue"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Образование</span>
                                            </p>

                                            <h3>
                                                Национални програми за развитие на образованието за 2023 г.
                                            </h3>

                                            <div class="meta-consul">
                          <span class="text-secondary">
                            4.7.2023 г. - 31.12.2024 г. | 1 <i class="far fa-comment text-secondary"></i>
                          </span>

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
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-circle-nodes dark-blue"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Регионална политика</span>
                                            </p>

                                            <h3>
                                                Морски пространствен план на Република България 2021-2035 г.
                                            </h3>

                                            <div class="meta-consul">
                          <span class="text-secondary">
                            30.06.2023 г. - 31.12.2035 г. | 5 <i class="far fa-comment text-secondary"></i>
                          </span>

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
                                    <div class="consult-img-holder">
                                        <i class="bi bi-shield-fill-check gr-color"></i>
                                    </div>
                                    <div class="consult-body">
                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Външна политика, сигурност и отбрана</span>
                                            </p>

                                            <h3>
                                                Национален план на Република България за развитие на способностите за управление на границите и за връщане на незаконно пребиваващи граждани на трети страни.
                                            </h3>

                                            <div class="meta-consul">

                          <span class="text-secondary">
                            11.05.2023 г. - Не е указан срок | 12 <i class="far fa-comment text-secondary"></i>
                          </span>


                                                <div>
                                                    <i class="fas fa-arrow-right read-more"></i>
                                                </div>

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
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-circle-nodes dark-blue"></i>
                                    </div>
                                    <div class="consult-body">

                                        <a href="#" class="consul-item">
                                            <p>
                                                <span class="consul-cat">Регионална политика</span>
                                            </p>

                                            <h3>
                                                Национална програма за енергийна ефективност на многофамилните жилищни сгради
                                            </h3>

                                            <div class="meta-consul">
                                                <div>
                            <span class="text-secondary">
                              30.06.2023 г. - 14.07.2023 г. | 2 <i class="far fa-comment text-secondary"></i>
                            </span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-arrow-right read-more"></i>
                                                </div>
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
                                        <span aria-hidden="true">&laquo;</span>
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
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <button class="btn rss-sub main-color">Всички документи <i class="fas fa-long-arrow-right main-color"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    </div>
</section>
<section id="usefull-links">
    <div class="container">
        <div class="row">
            <div class="col-md-3">

                <a href="#" class="box-link gr-color-bgr">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi-file-earmark-check text-light"></i>
                        </div>
                        <div class="link-heading">
                <span>
                  Стандарти за провеждане на обществени консултации
                </span>
                        </div>
                    </div>
                </a>

            </div>

            <div class="col-md-3">
                <a href="#" class="box-link light-blue-bgr">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi-calculator text-light"></i>
                        </div>
                        <div class="link-heading">
                <span>
                  Интерактивен калкулатор на Административния товар
                </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="#" class="box-link dark-blue-bgr">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi-journal-check text-light"></i>
                        </div>
                        <div class="link-heading">
                <span>
                  Ръководство за извършване на оценка на въздействието
                </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">

                <a href="#" class="box-link navy-marine-bgr">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="bi bi-file-earmark-medical text-light"></i>
                        </div>
                        <div class="link-heading">
                <span>
                  Списък на стратегически документи
                </span>
                        </div>
                    </div>
                </a>
            </div>


        </div>
    </div>
</section>
@endsection

