@extends('layouts.site')

@section('pageTitle', 'Стратегически документи')

@section('content')
    <section class="public-constultation">

    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="mb-2">Информация</h2>
                <p>
                    Този раздел е предназначен за граждани, които желаят да се включат в обществения дебат и да споделят
                    мнението си. Позовава се на голям брой консултации, проведени в Интернет преди приемането на нормативни
                    текстове. Той също така идентифицира консултациите на гражданите с широката общественост
                </p>
            </div>
        </div>


        <div class="row all-consultations">
            <div class="col-md-3 search-inputs">
                <h3 class="mb-4">
                    Търсене
                </h3>

                <div class="filter-results">

                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Тип документ</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected="1">Всички</option>
                                <option value="1">Национални</option>
                                <option value="2">Областни и общински</option>

                            </select>
                        </div>
                    </div>



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

                    <div class="input-group date" id="datepicker">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Начална дата</label>
                            <span class="input-group-append d-flex">
                  <input type="text" id="startdate"  placeholder="Ден/Месец/Година" class="form-control"/>
                  <span class="input-group-text bg-light d-block start-date-icon">
                    <i class="bi bi-calendar-week"></i>
                  </span>
                </span>
                        </div>
                    </div>

                    <div class="input-group date" id="datepicker">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Крайна дата</label>
                            <span class="input-group-append d-flex">
                  <input type="text" id="startdate"  placeholder="Ден/Месец/Година" class="form-control"/>
                  <span class="input-group-text bg-light d-block start-date-icon">
                    <i class="bi bi-calendar-week"></i>
                  </span>
                </span>
                        </div>
                    </div>


                    <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                </div>

            </div>

            <div class="col-md-9 home-results">

                <div class="row">
                    <div class="col-md-8">
                        <div class="info-consul">
                            <h3>
                                Общо 125 резултата
                            </h3>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select class="form-select" aria-label="Default select example">
                            <option hidden>Сортирай по</option>
                            <option value="1">Най-нови</option>
                            <option value="2">Най-стари</option>

                        </select>
                    </div>
                </div>

                <div class="row">
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


                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="consul-wrapper">
                            <div class="single-consultation d-flex">
                                <div class="consult-img-holder">
                                    <i class="fa-solid fa-flask-vial light-blue"></i>
                                </div>
                                <div class="consult-body">
                                    <a href="#" class="consul-item">
                                        <p>
                                            <span class="consul-cat">Наука и технологии</span>
                                        </p>

                                        <h3>
                                            Национална пътна карта за подобряване на условията за разгръщане на потенциала за развитие на водородните технологии и механизмите за производство и доставка на водород
                                        </h3>

                                        <div class="meta-consul">
                                            <div>
                          <span class="text-secondary">
                            05.07.2023 г. - Не е указан срок | 4 <i class="far fa-comment text-secondary"></i>
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

            </div>
        </div>
    </div>
    </div>
</section>
@endsection
