@extends('layouts.site')

@section('content')
    <section id="slider">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">


                    <img class="d-block w-100" src="../img/ms-w-2023.jpg" alt="First slide">

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
<section id="usefull-links">
    <div class="container">
        <div class="row">
            <div class="col-md-3">

                <a href="#" class="box-link">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="fa fa-file-lines text-light"></i>
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

                <a href="#" class="box-link">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="fa fa-calculator text-light"></i>
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
                <a href="#" class="box-link">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="fa fa-book text-light"></i>
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

                <a href="#" class="box-link">
                    <div class="info-box">
                        <div class="icon-wrap">
                            <i class="fa fa-list-check text-light"></i>
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
<section id="public-constultation">

    <div class="container">

        <div class="row mb-4">
            <div class="col-md-12 justify-content-start d-flex">
                <h2 class="m-0">Последни обществени консултации</h2>
            </div>
        </div>


        <div class="row search-consultation">
            <div class="col-md-4">
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Тип консултации	</option>
                        <option value="1">Национални</option>
                        <option value="2">Областни и общински</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Избор на тема</option>
                        <option value="1">Бизнес среда</option>
                        <option value="2">Енергетика</option>
                        <option value="3">Защита на потребителите</option>
                        <option value="4">Здравеопазване</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group mb-3">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Статус</option>
                        <option value="1">Всички</option>
                        <option value="2">Открити</option>
                        <option value="3">Приключили</option>

                    </select>
                </div>
            </div>



        </div>

        <div class="row search-consultation mb-3">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Ключова дума" aria-label="Ключова дума" aria-describedby="button-addon2">
                    <button class="btn custom-btn search-btn" type="button" id="button-addon2"><i class="fas fa-search me-2 text-light"></i>Търсене</button>
                </div>
            </div>
        </div>


        <div class="row mb-4">
            <div class="col-md-7 justify-content-start d-flex">
                <strong>Обществена консултация</strong>
            </div>

            <div class="col-md-3 text-center d-flex justify-content-center align-items-center">
          <span>
            <strong>Период</strong>
          </span>
            </div>

            <div class="col-md-2 text-center  d-flex justify-content-center align-items-center">
          <span >
            <strong>Статус</strong>
          </span>
            </div>
        </div>

        <div id="consultation-list">
            <div class="row consultation-wrapper py-3">
                <div class="col-md-2 mx-auto text-center">
                    <img src="../images/mccylogo.jpg" width="100px" >
                </div>
                <div class="col-md-5 mx-auto text-left">
                    <p>
                        <a href="#" class="const-cat">Национални Околна среда</a>
                    </p>
                    <a href="#" class="const-heading">Проект на Постановление на Министерския съвет за създаване на Консултативен съвет за Европейската зелена сделка</a>
                </div>
                <div class="col-md-3 mx-auto text-center">
                    <h5 class="period">
                        30.6.2023 г. - 14.7.2023 г.
                    </h5>
                </div>
                <div class="col-md-2 text-center">
            <span class="const-status open">
              Открит
            </span>
                </div>
            </div>

            <div class="row consultation-wrapper py-3">
                <div class="col-md-2 mx-auto text-center">
                    <img src="../images/mccylogo.jpg" width="100px" >
                </div>
                <div class="col-md-5 mx-auto text-left">
                    <p>
                        <a href="#" class="const-cat">Финанси и данъчна политика</a></p>
                    <a href="#" class="const-heading">Проект на заповед, която се издава от директора на Агенция ,,Митници“ на основание чл. 66б, ал. 2 от Закона за митниците</a>
                </div>
                <div class="col-md-3 mx-auto text-center">
                    <h5 class="period">
                        30.6.2023 г. - 30.7.2023 г.
                    </h5>
                </div>
                <div class="col-md-2 text-center">
            <span class="const-status open">
              Открит
            </span>
                </div>
            </div>


            <div class="row consultation-wrapper py-3">
                <div class="col-md-2 mx-auto text-center">
                    <img src="../images/mccylogo.jpg" width="100px" >
                </div>
                <div class="col-md-5 mx-auto text-left">
                    <p>
                        <a href="#" class="const-cat">Община Търговище</a></p>
                    <a href="#" class="const-heading">
                        Генерален план за организация на движението на територията на град Търговище
                    </a>
                </div>
                <div class="col-md-3 mx-auto text-center">
                    <h5 class="period">
                        30.6.2022 г. - 14.05.2023 г.
                    </h5>
                </div>
                <div class="col-md-2 text-center" >

            <span class="const-status closed">
              Затворен
            </span>
                </div>
            </div>


            <div class="row consultation-wrapper py-3">
                <div class="col-md-2 mx-auto text-center">
                    <img src="../images/mccylogo.jpg" width="100px" >
                </div>
                <div class="col-md-5 mx-auto text-left">
                    <p>
                        <a href="#" class="const-cat">Финанси и данъчна политика</a></p>
                    <a href="#" class="const-heading">
                        Проект на Стратегия за управление на държавния дълг за периода 2023-2025 г.
                    </a>
                </div>
                <div class="col-md-3 mx-auto text-center">
                    <h5 class="period">
                        30.6.2022 г. - 14.06.2023 г.
                    </h5>
                </div>
                <div class="col-md-2 text-center">

            <span class="const-status closed">
              Затворен
            </span>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-md-12  mt-4">
                <button class="btn custom-btn read-more " type="submit">Вижте повече <i class="	fas fa-long-arrow-right text-light"></i></button>
            </div>
        </div>

    </div>
</section>
<section id="strategic-documents">
    <div class="container">

        <div class="row">
            <div class="col-md-8 ">
                <h2 class="mb-4">Стратегически документи</h2>
            </div>
            <div class="col-md-4 d-flex justify-content-center align-items-center ">
                <span><strong>Валидност</strong></span>
            </div>
        </div>

        <div class="row document-row first">
            <div class="col-md-8 d-flex justify-content-start align-items-center">
                <a href="#" class="str-doc-h">
                    <i class="far fa-file-alt text-light me-3"></i>
                    <span>Морски пространствен план на Република България 2021-2035 г.</span>

                </a>
            </div>

            <div class="col-md-4 d-flex justify-content-center align-items-center ">
          <span>
             31.12.2035 г.
          </span>
            </div>
        </div>

        <div class="row document-row">
            <div class="col-md-8 d-flex justify-content-start align-items-center">
                <a href="#" class="str-doc-h">
                    <div> <i class="far fa-file-alt text-light me-3"></i></div>
                    <div>
                        <span>Национални програми за развитие на образованието за 2023 г.</span>
                    </div>
                </a>
            </div>

            <div class="col-md-4 d-flex justify-content-center align-items-center ">
          <span>
             31.12.2024 г.
          </span>
            </div>
        </div>

        <div class="row document-row">
            <div class="col-md-8 d-flex justify-content-start align-items-center">
                <a href="#" class="str-doc-h">
                    <div>
                        <i class="far fa-file-alt text-light me-3"></i>
                    </div>
                    <div>
                        <span>Национален план на Република България за развитие на способностите за управление на границите и за връщане на незаконно пребиваващи граждани на трети страни</span>
                    </div>

                </a>
            </div>

            <div class="col-md-4 d-flex justify-content-center align-items-center ">
          <span>
            Не е указан срок
          </span>
            </div>
        </div>


        <div class="row document-row">
            <div class="col-md-8 d-flex justify-content-start align-items-center">
                <a href="#" class="str-doc-h">
                    <div>
                        <i class="far fa-file-alt text-light me-3"></i>
                    </div>

                    <div>
                        <span>Национална пътна карта за подобряване на условията за разгръщане на потенциала за развитие на водородните технологии и механизмите за производство и доставка на водород </span>
                    </div>

                </a>
            </div>

            <div class="col-md-4 d-flex justify-content-center align-items-center ">
          <span>
            Не е указан срок
          </span>
            </div>
        </div>



        <div class="row document-row">
            <div class="col-md-8 d-flex justify-content-start align-items-center">
                <a href="#" class="str-doc-h">
                    <div>
                        <i class="far fa-file-alt text-light me-3"></i>
                    </div>

                    <div>
              <span>Национална програма за енергийна ефективност на многофамилните жилищни сгради
              </span>
                    </div>

                </a>
            </div>

            <div class="col-md-4 d-flex justify-content-center align-items-center ">
          <span>
            Не е указан срок
          </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12  mt-4">
                <button class="btn custom-btn read-more " type="submit">Вижте повече <i class="	fas fa-long-arrow-right text-light"></i></button>
            </div>
        </div>
    </div>
</section>
<section id="blog">
    <div class="container">

        <div class="row">
            <div class="col-md-12 ">
                <h2 class="mb-4">Новини</h2>
            </div>

        </div>

        <div class="row">

            <div class="col-lg-4">

                <div class="post-box">
                    <div class="post-img"><img src="../images/ms-2023.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date">17.05.2023</span>
                    <h3 class="post-title">Съветът за административната реформа одобри Годишния доклад за оценка на въздействието за 2022 г.</h3>
                    <a href="#" class="readmore stretched-link mt-auto">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="post-box">
                    <div class="post-img"><img src="../images/news-2.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date">05.04.2023</span>
                    <h3 class="post-title">Представен е доклад „Икономически преглед на България“ на ОИСР</h3>
                    <a href="#" class="readmore stretched-link mt-auto">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="post-box">
                    <div class="post-img"><img src="../images/news-3.jpg" class="img-fluid" alt=""></div>
                    <span class="post-date">21.11.2022</span>
                    <h3 class="post-title">Правителството одобри Първоначалния меморандум на РБ относно процеса по присъединяване към ОИСР</h3>
                    <a href="#" class="readmore stretched-link mt-auto">Прочетете още <i class="fas fa-long-arrow-right"></i></a>
                </div>
            </div>



        </div>
    </div>
</section>
@endsection
