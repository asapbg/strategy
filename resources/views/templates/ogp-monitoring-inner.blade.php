@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>
@section('pageTitle', 'Оценка за изпълнението на плановете за действие - мониторинг')

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
                                <li class="mb-2  active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Оценка за
                                        изпълнението на плановете за действие - мониторинг</a>
                                </li>
                                <li class="mb-2">
                                    <a href="#"
                                        class="link-dark text-decoration-none">Разработване на нов план за действие</a>
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
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="mb-3">Информация</h2>
            </div>
            <div class="col-md-12 text-start">
                <div class="dropdown d-inline">
                    <button class="btn btn-primary main-color dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-download main-color me-2"></i>
                        Експорт
                    </button>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Експорт като Pdf</a></li>
                        <li><a class="dropdown-item" href="#">Експорт като Excel</a></li>
                        <li><a class="dropdown-item" href="#">Експорт като Csv</a></li>
                    </ul>
                </div>
                <button class="btn rss-sub main-color">
                    <i class="fas fa-square-rss text-warning me-2"></i>RSS</button>
                <button class="btn rss-sub main-color">
                    <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12 d-flex align-items-center">
                <h3 class="mb-2 fs-4">Вид доклад: </h3>
                <div class="mb-2 ms-2 fs-4">
                    <a href="#" class="main-color text-decoration-none">
                        <i class="fas fa-solid fa-list-check me-1 main-color" title="Номер на консултация "></i> Самооценка на администрацията по действащи планове за действие

                    </a>
                </div>

            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">Начало на изпълнение</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18" title="Тип консултация"></i>24.02.2021г.</span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">Край на изпълнение</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar-check me-2 main-color fs-18" title="Тип консултация"></i>2023
                        г.</span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">Статус</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="active-ks fs-16">Изпълнен</span>
                </a>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3 class="fs-4 mb-3">
                   Обща част
                </h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quam adipiscing vitae proin sagittis nisl rhoncus mattis. Vel risus commodo viverra maecenas accumsan lacus vel facilisis. Pharetra sit amet aliquam id diam maecenas ultricies. Arcu risus quis varius quam quisque id diam vel quam. Diam volutpat commodo sed egestas egestas fringilla phasellus faucibus. Vestibulum mattis ullamcorper velit sed ullamcorper morbi tincidunt. Ridiculus mus mauris vitae ultricies leo. Adipiscing vitae proin sagittis nisl rhoncus mattis. Mattis vulputate enim nulla aliquet.
                </p>
                <p>
                    Nec dui nunc mattis enim ut. Eros donec ac odio tempor orci dapibus ultrices in iaculis. Pretium viverra suspendisse potenti nullam ac tortor vitae purus. In hac habitasse platea dictumst vestibulum rhoncus est pellentesque elit. Quis varius quam quisque id diam vel quam elementum pulvinar. Non blandit massa enim nec dui. Sollicitudin tempor id eu nisl nunc mi ipsum faucibus vitae. Sit amet commodo nulla facilisi. Mauris augue neque gravida in fermentum et sollicitudin ac. Lectus vestibulum mattis ullamcorper velit sed ullamcorper morbi tincidunt. Amet mattis vulputate enim nulla aliquet porttitor lacus luctus. Eu tincidunt tortor aliquam nulla facilisi cras fermentum odio eu. Ultricies integer quis auctor elit sed vulputate mi.                </p>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h3 class="fs-4 mb-3">
                           Изпълнение на мерки
                         </h3>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button text-dark fs-18 fw-600" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Тематична област №1 - Електронно управление
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" style="">
                                    <div class="accordion-body">

                                        <div class="custom-card p-3 mb-2">
                                            <div class="row ">
                                                <div class="document-info-body">
                                                    <p> <strong>АНГАЖИМЕНТ</strong>

                                                    </p>
                                                    <p> <strong>1. Българското правителство ще подобри достъпа и
                                                            качеството на публичните услуги чрез
                                                            внедряване на инструментите на електронно
                                                            управление</strong>

                                                    </p>
                                                    <p> <strong>1.1. МЕРКИ</strong>

                                                    </p>
                                                    <p> <strong>1.1.1. Усъвършенстване на съществуващите и въвеждане на
                                                            нови електронни услуги от
                                                            НАП</strong>

                                                    </p>
                                                    <p> <strong>Контекст:</strong>
                                                        В момента Националната агенция за приходите предлага най-много
                                                        услуги в
                                                        електронен вид в публичната администрация, но за някои услуги
                                                        все още е необходимо
                                                        посещение на място, което изисква ресурс от време и представлява
                                                        административна тежест за
                                                        данъкоплатците.
                                                        Основна цел: Разширяване
                                                    </p>
                                                    <p> <strong>Основна цел:</strong>
                                                        Разширяване на обхвата и качеството на предлаганите от НАП
                                                        електронни услуги
                                                        с оглед допълнително намаляване на бюрократичните пречки чрез
                                                        ориентирани към клиента
                                                        услуги и подобряване вътрешните бизнес процеси и процедури.
                                                    </p>
                                                    <p> <strong>Амбиция:</strong>
                                                        Опростяване на процедурите и подобряване на подходите,
                                                        ориентирани към нуждите
                                                        на данъкоплатците.
                                                    </p>
                                                    <p> <strong>Релевантност и съответствие с големите предизвикателства
                                                            на ПОУ:</strong>
                                                        Мярката
                                                        Мярката отговаря на голямото предизвикателство на ПОУ за
                                                        подобряване на ефективността на
                                                        публичните услуги.
                                                    </p>
                                                    <p> <strong>Отговорна институция:</strong>
                                                        Национална агенция за приходите
                                                    </p>
                                                    <p> <strong>Други ангажирани публични институции:</strong>
                                                        Министерство на финансите
                                                    </p>
                                                    <p> <strong>Очаквано въздействие:</strong>

                                                    </p>
                                                    <p> <strong>Улеснен и удобен достъп до услуги, предлагани от НАП;
                                                            намалена
                                                            административна тежест за гражданите и бизнеса; икономически
                                                            ползи от спестено време и
                                                            човешки ресурс за бизнеса и гражданите; подобрено управление
                                                            на процесите в НАП и по-
                                                            висока ефективност на приходната администрация.</strong>

                                                    </p>
                                                    <p> <strong>Срок на изпълнение:</strong>
                                                        постоянен
                                                    </p>
                                                    <p> <strong>1.1.2. Разработване на секторна Стратегия за
                                                            е-управление в МОСВ и Пътна карта към
                                                            нея за периода 2016-2020 в съответствие със Стратегията за
                                                            развитие на електронно
                                                            управление в Република България 2014 – 2020 г.</strong>

                                                    </p>

                                                    <p> <strong>Контекст:</strong>
                                                        В момента Националната агенция за приходите предлага най-много
                                                        услуги в
                                                        електронен вид в публичната администрация, но за някои услуги
                                                        все още е необходимо
                                                        посещение на място, което изисква ресурс от време и представлява
                                                        административна тежест за
                                                        данъкоплатците.
                                                        Основна цел: Разширяване
                                                    </p>
                                                    <p> <strong>Основна цел:</strong>
                                                        Разширяване на обхвата и качеството на предлаганите от НАП
                                                        електронни услуги
                                                        с оглед допълнително намаляване на бюрократичните пречки чрез
                                                        ориентирани към клиента
                                                        услуги и подобряване вътрешните бизнес процеси и процедури.
                                                    </p>
                                                    <p> <strong>Амбиция:</strong>
                                                        Опростяване на процедурите и подобряване на подходите,
                                                        ориентирани към нуждите
                                                        на данъкоплатците.
                                                    </p>
                                                    <p> <strong>Релевантност и съответствие с големите предизвикателства
                                                            на ПОУ:</strong>
                                                        Мярката
                                                        Мярката отговаря на голямото предизвикателство на ПОУ за
                                                        подобряване на ефективността на
                                                        публичните услуги.
                                                    </p>
                                                    <p> <strong>Отговорна институция:</strong>
                                                        Национална агенция за приходите
                                                    </p>
                                                    <p> <strong>Други ангажирани публични институции:</strong>
                                                        Министерство на финансите
                                                    </p>
                                                    <p> <strong>Очаквано въздействие:</strong>

                                                    </p>
                                                    <p> <strong>Улеснен и удобен достъп до услуги, предлагани от НАП;
                                                            намалена
                                                            административна тежест за гражданите и бизнеса; икономически
                                                            ползи от спестено време и
                                                            човешки ресурс за бизнеса и гражданите; подобрено управление
                                                            на процесите в НАП и по-
                                                            висока ефективност на приходната администрация.</strong>

                                                    </p>
                                                    <p> <strong>Срок на изпълнение:</strong>
                                                        постоянен
                                                    </p>

                                                    <button class="btn btn-primary  main-color mb-3">
                                                        <i class="fa-solid fa-download main-color me-2"></i>
                                                        Изтегляне
                                                    </button>
                                                </div>
                                            </div>

                                            <hr class="custom-hr">


                                            <div class="row mb-2 mt-3">
                                                <div class="col-md-12">
                                                    <h4 class="mb-3 fs-5">Секция с коментари</h4>
                                                    <div class="obj-comment comment-background p-2 rounded mb-3">
                                                        <div class="info">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Георги
                                                                Георгиев</span>
                                                            <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                                                            <a href="#">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                                            </a>
                                                        </div>
                                                        <div class="comment rounded py-2">
                                                            <p class="mb-2">Имате ли предложения за мерки, които ще допринесат за постигане целите на
                                                                инициативата? (за черпене на идеи за формулиране на предложенията може да се използва
                                                                Opening government: A guide to best practice in transparency, accountability and civic
                                                                engagement across the public sector)</p>
                                                            <div class="mb-0">
                                                                <a href="#" class="me-2 text-decoration-none">10<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                                                                <a href="#" class="text-decoration-none">1<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-12">
                                                    <div class="obj-comment comment-background p-2 rounded mb-3">
                                                        <div class="info">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Стоян Иванов </span>
                                                            <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                                                            <a href="#">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                                            </a>
                                                        </div>
                                                        <div class="comment rounded py-2">
                                                            <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция "Митници" стартира анкета
                                                                дали е необходимо да съществува фигурата "митнически агент". Моля гласувайте
                                                                http://customs.bg/bg/poll/10/
                                                            </p>
                                                            <div class="mb-0">
                                                                <a href="#" class="me-2 text-decoration-none">4<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                                                                <a href="#" class="text-decoration-none">5<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="mb-3 fs-5">Добавете коментар</h4>
                                                    <div class="summernote-wrapper">
                                                        <textarea class="summernote"></textarea>
                                                    </div>
                                                    <button class="btn btn-primary mt-3">Добавяне на коментар</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                  <button class=" accordion-button text-dark fs-18 fw-600 collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

                                    Тематична област №2 - Отворени градове

                                  </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="accordion-body">

                                        <div class="custom-card p-3 mb-2">
                                            <div class="row ">
                                                <div class="document-info-body">
                                                    <p> <strong>АНГАЖИМЕНТ</strong>

                                                    </p>
                                                    <p> <strong>1. Българското правителство ще подобри достъпа и
                                                            качеството на публичните услуги чрез
                                                            внедряване на инструментите на електронно
                                                            управление</strong>

                                                    </p>
                                                    <p> <strong>1.1. МЕРКИ</strong>

                                                    </p>
                                                    <p> <strong>1.1.1. Усъвършенстване на съществуващите и въвеждане на
                                                            нови електронни услуги от
                                                            НАП</strong>

                                                    </p>
                                                    <p> <strong>Контекст:</strong>
                                                        В момента Националната агенция за приходите предлага най-много
                                                        услуги в
                                                        електронен вид в публичната администрация, но за някои услуги
                                                        все още е необходимо
                                                        посещение на място, което изисква ресурс от време и представлява
                                                        административна тежест за
                                                        данъкоплатците.
                                                        Основна цел: Разширяване
                                                    </p>
                                                    <p> <strong>Основна цел:</strong>
                                                        Разширяване на обхвата и качеството на предлаганите от НАП
                                                        електронни услуги
                                                        с оглед допълнително намаляване на бюрократичните пречки чрез
                                                        ориентирани към клиента
                                                        услуги и подобряване вътрешните бизнес процеси и процедури.
                                                    </p>
                                                    <p> <strong>Амбиция:</strong>
                                                        Опростяване на процедурите и подобряване на подходите,
                                                        ориентирани към нуждите
                                                        на данъкоплатците.
                                                    </p>
                                                    <p> <strong>Релевантност и съответствие с големите предизвикателства
                                                            на ПОУ:</strong>
                                                        Мярката
                                                        Мярката отговаря на голямото предизвикателство на ПОУ за
                                                        подобряване на ефективността на
                                                        публичните услуги.
                                                    </p>
                                                    <p> <strong>Отговорна институция:</strong>
                                                        Национална агенция за приходите
                                                    </p>
                                                    <p> <strong>Други ангажирани публични институции:</strong>
                                                        Министерство на финансите
                                                    </p>
                                                    <p> <strong>Очаквано въздействие:</strong>

                                                    </p>
                                                    <p> <strong>Улеснен и удобен достъп до услуги, предлагани от НАП;
                                                            намалена
                                                            административна тежест за гражданите и бизнеса; икономически
                                                            ползи от спестено време и
                                                            човешки ресурс за бизнеса и гражданите; подобрено управление
                                                            на процесите в НАП и по-
                                                            висока ефективност на приходната администрация.</strong>

                                                    </p>
                                                    <p> <strong>Срок на изпълнение:</strong>
                                                        постоянен
                                                    </p>
                                                    <p> <strong>1.1.2. Разработване на секторна Стратегия за
                                                            е-управление в МОСВ и Пътна карта към
                                                            нея за периода 2016-2020 в съответствие със Стратегията за
                                                            развитие на електронно
                                                            управление в Република България 2014 – 2020 г.</strong>

                                                    </p>

                                                    <p> <strong>Контекст:</strong>
                                                        В момента Националната агенция за приходите предлага най-много
                                                        услуги в
                                                        електронен вид в публичната администрация, но за някои услуги
                                                        все още е необходимо
                                                        посещение на място, което изисква ресурс от време и представлява
                                                        административна тежест за
                                                        данъкоплатците.
                                                        Основна цел: Разширяване
                                                    </p>
                                                    <p> <strong>Основна цел:</strong>
                                                        Разширяване на обхвата и качеството на предлаганите от НАП
                                                        електронни услуги
                                                        с оглед допълнително намаляване на бюрократичните пречки чрез
                                                        ориентирани към клиента
                                                        услуги и подобряване вътрешните бизнес процеси и процедури.
                                                    </p>
                                                    <p> <strong>Амбиция:</strong>
                                                        Опростяване на процедурите и подобряване на подходите,
                                                        ориентирани към нуждите
                                                        на данъкоплатците.
                                                    </p>
                                                    <p> <strong>Релевантност и съответствие с големите предизвикателства
                                                            на ПОУ:</strong>
                                                        Мярката
                                                        Мярката отговаря на голямото предизвикателство на ПОУ за
                                                        подобряване на ефективността на
                                                        публичните услуги.
                                                    </p>
                                                    <p> <strong>Отговорна институция:</strong>
                                                        Национална агенция за приходите
                                                    </p>
                                                    <p> <strong>Други ангажирани публични институции:</strong>
                                                        Министерство на финансите
                                                    </p>
                                                    <p> <strong>Очаквано въздействие:</strong>

                                                    </p>
                                                    <p> <strong>Улеснен и удобен достъп до услуги, предлагани от НАП;
                                                            намалена
                                                            административна тежест за гражданите и бизнеса; икономически
                                                            ползи от спестено време и
                                                            човешки ресурс за бизнеса и гражданите; подобрено управление
                                                            на процесите в НАП и по-
                                                            висока ефективност на приходната администрация.</strong>

                                                    </p>
                                                    <p> <strong>Срок на изпълнение:</strong>
                                                        постоянен
                                                    </p>

                                                    <button class="btn btn-primary  main-color mb-3">
                                                        <i class="fa-solid fa-download main-color me-2"></i>
                                                        Изтегляне
                                                    </button>
                                                </div>
                                            </div>

                                            <hr class="custom-hr">


                                            <div class="row mb-2 mt-3">
                                                <div class="col-md-12">
                                                    <h4 class="mb-3 fs-5">Секция с коментари</h4>
                                                    <div class="obj-comment comment-background p-2 rounded mb-3">
                                                        <div class="info">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Георги
                                                                Георгиев</span>
                                                            <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                                                            <a href="#">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                                            </a>
                                                        </div>
                                                        <div class="comment rounded py-2">
                                                            <p class="mb-2">Имате ли предложения за мерки, които ще допринесат за постигане целите на
                                                                инициативата? (за черпене на идеи за формулиране на предложенията може да се използва
                                                                Opening government: A guide to best practice in transparency, accountability and civic
                                                                engagement across the public sector)</p>
                                                            <div class="mb-0">
                                                                <a href="#" class="me-2 text-decoration-none">10<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                                                                <a href="#" class="text-decoration-none">1<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-12">
                                                    <div class="obj-comment comment-background p-2 rounded mb-3">
                                                        <div class="info">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Стоян Иванов </span>
                                                            <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                                                            <a href="#">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                                            </a>
                                                        </div>
                                                        <div class="comment rounded py-2">
                                                            <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция "Митници" стартира анкета
                                                                дали е необходимо да съществува фигурата "митнически агент". Моля гласувайте
                                                                http://customs.bg/bg/poll/10/
                                                            </p>
                                                            <div class="mb-0">
                                                                <a href="#" class="me-2 text-decoration-none">4<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                                                                <a href="#" class="text-decoration-none">5<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="mb-3 fs-5">Добавете коментар</h4>
                                                    <div class="summernote-wrapper">
                                                        <textarea class="summernote"></textarea>
                                                    </div>
                                                    <button class="btn btn-primary mt-3">Добавяне на коментар</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                  <button class="accordion-button text-dark fs-18 fw-600 collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Тематична област №3 - Отворени градове
                                  </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="accordion-body">

                                        <div class="custom-card p-3 mb-2">
                                            <div class="row ">
                                                <div class="document-info-body">
                                                    <p> <strong>АНГАЖИМЕНТ</strong>

                                                    </p>
                                                    <p> <strong>1. Българското правителство ще подобри достъпа и
                                                            качеството на публичните услуги чрез
                                                            внедряване на инструментите на електронно
                                                            управление</strong>

                                                    </p>
                                                    <p> <strong>1.1. МЕРКИ</strong>

                                                    </p>
                                                    <p> <strong>1.1.1. Усъвършенстване на съществуващите и въвеждане на
                                                            нови електронни услуги от
                                                            НАП</strong>

                                                    </p>
                                                    <p> <strong>Контекст:</strong>
                                                        В момента Националната агенция за приходите предлага най-много
                                                        услуги в
                                                        електронен вид в публичната администрация, но за някои услуги
                                                        все още е необходимо
                                                        посещение на място, което изисква ресурс от време и представлява
                                                        административна тежест за
                                                        данъкоплатците.
                                                        Основна цел: Разширяване
                                                    </p>
                                                    <p> <strong>Основна цел:</strong>
                                                        Разширяване на обхвата и качеството на предлаганите от НАП
                                                        електронни услуги
                                                        с оглед допълнително намаляване на бюрократичните пречки чрез
                                                        ориентирани към клиента
                                                        услуги и подобряване вътрешните бизнес процеси и процедури.
                                                    </p>
                                                    <p> <strong>Амбиция:</strong>
                                                        Опростяване на процедурите и подобряване на подходите,
                                                        ориентирани към нуждите
                                                        на данъкоплатците.
                                                    </p>
                                                    <p> <strong>Релевантност и съответствие с големите предизвикателства
                                                            на ПОУ:</strong>
                                                        Мярката
                                                        Мярката отговаря на голямото предизвикателство на ПОУ за
                                                        подобряване на ефективността на
                                                        публичните услуги.
                                                    </p>
                                                    <p> <strong>Отговорна институция:</strong>
                                                        Национална агенция за приходите
                                                    </p>
                                                    <p> <strong>Други ангажирани публични институции:</strong>
                                                        Министерство на финансите
                                                    </p>
                                                    <p> <strong>Очаквано въздействие:</strong>

                                                    </p>
                                                    <p> <strong>Улеснен и удобен достъп до услуги, предлагани от НАП;
                                                            намалена
                                                            административна тежест за гражданите и бизнеса; икономически
                                                            ползи от спестено време и
                                                            човешки ресурс за бизнеса и гражданите; подобрено управление
                                                            на процесите в НАП и по-
                                                            висока ефективност на приходната администрация.</strong>

                                                    </p>
                                                    <p> <strong>Срок на изпълнение:</strong>
                                                        постоянен
                                                    </p>
                                                    <p> <strong>1.1.2. Разработване на секторна Стратегия за
                                                            е-управление в МОСВ и Пътна карта към
                                                            нея за периода 2016-2020 в съответствие със Стратегията за
                                                            развитие на електронно
                                                            управление в Република България 2014 – 2020 г.</strong>

                                                    </p>

                                                    <p> <strong>Контекст:</strong>
                                                        В момента Националната агенция за приходите предлага най-много
                                                        услуги в
                                                        електронен вид в публичната администрация, но за някои услуги
                                                        все още е необходимо
                                                        посещение на място, което изисква ресурс от време и представлява
                                                        административна тежест за
                                                        данъкоплатците.
                                                        Основна цел: Разширяване
                                                    </p>
                                                    <p> <strong>Основна цел:</strong>
                                                        Разширяване на обхвата и качеството на предлаганите от НАП
                                                        електронни услуги
                                                        с оглед допълнително намаляване на бюрократичните пречки чрез
                                                        ориентирани към клиента
                                                        услуги и подобряване вътрешните бизнес процеси и процедури.
                                                    </p>
                                                    <p> <strong>Амбиция:</strong>
                                                        Опростяване на процедурите и подобряване на подходите,
                                                        ориентирани към нуждите
                                                        на данъкоплатците.
                                                    </p>
                                                    <p> <strong>Релевантност и съответствие с големите предизвикателства
                                                            на ПОУ:</strong>
                                                        Мярката
                                                        Мярката отговаря на голямото предизвикателство на ПОУ за
                                                        подобряване на ефективността на
                                                        публичните услуги.
                                                    </p>
                                                    <p> <strong>Отговорна институция:</strong>
                                                        Национална агенция за приходите
                                                    </p>
                                                    <p> <strong>Други ангажирани публични институции:</strong>
                                                        Министерство на финансите
                                                    </p>
                                                    <p> <strong>Очаквано въздействие:</strong>

                                                    </p>
                                                    <p> <strong>Улеснен и удобен достъп до услуги, предлагани от НАП;
                                                            намалена
                                                            административна тежест за гражданите и бизнеса; икономически
                                                            ползи от спестено време и
                                                            човешки ресурс за бизнеса и гражданите; подобрено управление
                                                            на процесите в НАП и по-
                                                            висока ефективност на приходната администрация.</strong>

                                                    </p>
                                                    <p> <strong>Срок на изпълнение:</strong>
                                                        постоянен
                                                    </p>

                                                    <button class="btn btn-primary  main-color mb-3">
                                                        <i class="fa-solid fa-download main-color me-2"></i>
                                                        Изтегляне
                                                    </button>
                                                </div>
                                            </div>

                                            <hr class="custom-hr">


                                            <div class="row mb-2 mt-3">
                                                <div class="col-md-12">
                                                    <h4 class="mb-3 fs-5">Секция с коментари</h4>
                                                    <div class="obj-comment comment-background p-2 rounded mb-3">
                                                        <div class="info">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Георги
                                                                Георгиев</span>
                                                            <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                                                            <a href="#">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                                            </a>
                                                        </div>
                                                        <div class="comment rounded py-2">
                                                            <p class="mb-2">Имате ли предложения за мерки, които ще допринесат за постигане целите на
                                                                инициативата? (за черпене на идеи за формулиране на предложенията може да се използва
                                                                Opening government: A guide to best practice in transparency, accountability and civic
                                                                engagement across the public sector)</p>
                                                            <div class="mb-0">
                                                                <a href="#" class="me-2 text-decoration-none">10<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                                                                <a href="#" class="text-decoration-none">1<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-12">
                                                    <div class="obj-comment comment-background p-2 rounded mb-3">
                                                        <div class="info">
                                                            <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Стоян Иванов </span>
                                                            <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                                                            <a href="#">
                                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                                            </a>
                                                        </div>
                                                        <div class="comment rounded py-2">
                                                            <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция "Митници" стартира анкета
                                                                дали е необходимо да съществува фигурата "митнически агент". Моля гласувайте
                                                                http://customs.bg/bg/poll/10/
                                                            </p>
                                                            <div class="mb-0">
                                                                <a href="#" class="me-2 text-decoration-none">4<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                                                                <a href="#" class="text-decoration-none">5<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="mb-3 fs-5">Добавете коментар</h4>
                                                    <div class="summernote-wrapper">
                                                        <textarea class="summernote"></textarea>
                                                    </div>
                                                    <button class="btn btn-primary mt-3">Добавяне на коментар</button>
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
</div>
</div>
@endsection
