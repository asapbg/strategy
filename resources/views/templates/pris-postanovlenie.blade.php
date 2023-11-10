@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }
</style>

@section('pageTitle', 'Постановление 752 на Министерския съвет от 2023 г.')

@section('content')
    <section>
        <div class="container-fluid p-0">
            <div class="row breadcrumbs py-1">
                <nav style="--bs-breadcrumb-divider: '/';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Начало</a></li>
                        <li class="breadcrumb-item"><a href="#">Актове на МС</a></li>
                        <li class="breadcrumb-item"><a href="#">Постановления</a></li>
                        <li class="breadcrumb-item"><a href="#">Постановление 752 на Министерския съвет от 2023 г.</a>
                        </li>
                    </ol>
                    </ol>
                </nav>
            </div>
    </section>
    <div class="container-fluid">
        <div class="row edit-consultation m-0">
            <div class="col-md-12 text-end">
                <button class="btn btn-sm btn-primary main-color mt-2">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на постановление</button>
            </div>
        </div>
    </div>
    <section class="public-page">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">

                    <div class="left-nav-panel" style="background: #fff !important;">
                        <div class="flex-shrink-0 p-2">
                            <ul class="list-unstyled">
                                <li class="mb-1">
                                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                                        data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                        <i class="fa-solid fa-bars me-2 mb-2"></i>Начало
                                    </a>
                                    <hr class="custom-hr">
                                    <div class="collapse show mt-3" id="home-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                            <li class="mb-2  p-1"><a href="#"
                                                    class="link-dark text-decoration-none">Планиране</a></li>
                                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                                <ul class="list-unstyled ps-3">
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Законодателна
                                                            програма</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Оперативна програма</a>
                                                    </li>
                                                    <hr class="custom-hr">
                                                </ul>
                                            </ul>

                                            <li class="mb-2 active-item-left p-1"><a href="#"
                                                    class="link-dark text-decoration-none">Актове на МС</a></li>
                                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">
                                                <ul class="list-unstyled ps-3">
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="active-item-left p-1 text-white   text-decoration-none">Постановления</a>
                                                    </li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Решения</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Становища</a></li>
                                                    <hr class="custom-hr">
                                                    <li class="my-2"><a href="#"
                                                            class="link-dark  text-decoration-none">Протоколи</a></li>
                                                    <hr class="custom-hr">
                                                </ul>
                                            </ul>

                                </li>
                                <li class="mb-2"><a href="#" class="link-dark  text-decoration-none">Архив</a></li>
                            </ul>
                        </div>
                        </li>
                        <hr class="custom-hr">
                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5">


                <div class="col-md-12">
                    <h2 class="mb-2">Описание на документа</h2>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-thumbtack main-color me-1"></i>Категория
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#"><span class="pris-tag">Постановление</span></a>
                    </div>
                </div>
                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-hashtag main-color me-1"></i>Номер
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <span>
                            52
                        </span>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-brands fa-keycdn main-color me-1"></i>Уникален номер
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none">5204505</a>

                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-calendar-check main-color me-1"></i>Дата на издаване
                    </div>

                    <div class="col-md-9 pris-left-column">
                        30.10.2023
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-calendar-days main-color me-1"></i>Дата на публикуване
                    </div>

                    <div class="col-md-9 pris-left-column">
                        31.10.2023 09:28:12
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class=" fa-solid fa-arrow-up-right-from-square main-color me-1"></i>Заглавие/Относно
                    </div>

                    <div class="col-md-9 pris-left-column">
                        ЗА ВЪЗСТАНОВЯВАНЕ НА СРЕДСТВА ПО БЮДЖЕТА НА МИНИСТЕРСТВОТО НА ФИНАНСИТЕ ЗА 2023 Г.
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-university main-color me-1"></i>Институция
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none">МС </a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-right-to-bracket main-color me-1"></i>Вносител
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none">МС </a>
                    </div>
                </div>


                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-regular fa-file-lines main-color me-1"></i>Протокол
                    </div>

                    <div class="col-md-9 pris-left-column">
                        48.5
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-newspaper main-color me-1"></i>ДВ брой
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="text-decoration-none"> 140 от 2023г.</a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-gavel main-color me-1"></i>Правно основание
                    </div>

                    <div class="col-md-9 pris-left-column">
                        чл. 7, ал. 1 във връзка с чл. 15 от Закона за публичните финанси и с чл. 4, ал. 3 от Постановление №
                        496 на Министерския съвет от 2022 г. за одобряване на допълнителни разходи по бюджета на
                        Министерството на финансите за 2022 г., § 5 от Преходните и заключителните разпоредби на
                        Постановление № 10 на Министерския съвет от 2023 г. за изменение и допълнение на нормативни актове
                        (обн., ДВ, бр. 9 от 2023 г.; изм., бр. 25 от 2023 г.), чл. 1, ал. 3 от Постановление № 11 на
                        Министерския съвет от 2023 г. за прилагане на политиката по чл. 1, ал. 5, т. 13.4 от Закона за
                        държавния бюджет на Република България за 2022 г. (ДВ, бр. 9 от 2023 г.) и Решение № 269 на
                        Министерския съвет от 2023 г. за одобряване на финансиране на държавното обществено осигуряване за
                        2023 г.
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-tags main-color me-1"></i>Термини
                    </div>
                    <div class="col-md-9 pris-left-column"><a href="#"><span class="pris-tag">
                                М-во на финансите</span></a> <a href="#"><span
                                class="pris-tag">Възстановяване</span></a> <a href="#"><span
                                class="pris-tag">Финансови средства</span></a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-arrow-right-arrow-left main-color me-1"></i>Свързани документи
                    </div>

                    <div class="col-md-9 pris-left-column">
                        <a href="#" class="main-color"><a
                                href="{{ route('templates.view', ['slug' => 'pris-postanovlenie']) }}" target="_blank"
                                class="text-decoration-none">Постановление №496 на Министерския съвет от 2022 г.</a></a>
                    </div>
                </div>

                <div class="row pris-row pb-2 mb-2">
                    <div class="col-md-3 pris-left-column">
                        <i class="fa-solid fa-list-ol main-color me-1"></i>Версия
                    </div>
                    <div class="col-md-9 pris-left-column">
                        <p class="mb-0">
                            <a href="#" class="text-decoration-none">12.01.2023г. от потребител Георги Иванов</a>
                        </p>
                        <p class="mb-0">
                            <a href="#" class="text-decoration-none">15.02.2023г. от потребител Георги Иванов</a>
                        </p>
                        <p class="mb-0">
                            <a href="#" class="text-decoration-none">17.03.2023г. от потребител Георги Иванов</a>
                        </p>

                    </div>
                </div>


                <div class="row mb-0 mt-5">
                    <div class="mb-2">
                        <h2 class="mb-1">Прикачени файлове</h2>
                    </div>

                </div>
                <div class="row p-3">
                    <div class="custom-card py-4 px-3 mb-5">
                        <div class="col-md-12">
                            <h4 class="mb-2">Информация</h4>
                            <p>
                                С проекта на наредба се предлагат:

                                I. Промени, свързани с въвеждането в националното законодателство на Делегирана директива
                                (ЕС) 2022/2407 на Комисията от 20 септември 2022 година за изменение на приложенията към
                                Директива 2008/68/ЕО на Европейския парламент и на Съвета с оглед на адаптирането към
                                научно-техническия прогрес (ОВ L 317, 09/12/2022) (Делегирана директива (ЕС) 2022/2407).

                                Директива 2008/68/ЕО относно вътрешния превоз на опасни товари (2008/68/ЕО) е въведена в
                                националното законодателство с Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни
                                товари. Съгласно чл. 8, параграф 1 от Директива 2008/68/ЕО, Европейската комисия приема
                                делегирани актове за изменение на приложенията на директивата с цел отчитане на измененията
                                на ADR, RID и ADN, по-специално свързаните с научния и техническия прогрес, включително
                                използването на технологии за локализиране и проследяване. Приетите въз основа на цитирания
                                текст актове, въвеждащи изменения на приложенията към Директива 2008/68/ЕО, са въведени в
                                Наредба № 46, с изключение на Делегирана директива (ЕС) 2022/2407. Текстът, който следва да
                                се транспонира от посочената делегирана директива се съдържа в чл. 1, т. 2 от нея и гласи:
                                „в приложение II раздел II.1 се заменя със следното:

                                „II.1. RID
                            </p>

                            <p>
                                <strong>Приложението към RID, приложимо от 1 януари 2023 г., като се разбира, че където е
                                    уместно „RID договаряща държава" се заменя с „държава членка“.</strong>
                            </p>

                            <p>
                                Подобен текст вече е транспониран и се съдържа в чл. 2, ал. 1 от Наредба № 46. За да се
                                транспонира Делегирана директива (ЕС) 2022/2407 е необходимо да се добави заглавието й в
                                края на § 2 от Заключителните разпоредби на действащата Наредба № 46, което се предлага в
                                параграф единствен от проекта на наредба.

                                Предложеният проект на Наредба за изменение и допълнение на Наредба № 46 не оказва пряко/или
                                косвено въздействие върху държавния бюджет. Не са необходими финансови и други средства за
                                прилагането на новата уредба.

                                На основание чл. 26, ал. 2-4 от Закона за нормативните актове проектът на наредба, заедно с
                                доклада към него, е публикуван за обществено обсъждане на страницата на Министерството на
                                транспорта, информационните технологии и съобщенията и на Портала за обществени консултации
                                на Министерски съвет. На заинтересованите лица е предоставена възможност да се запознаят с
                                проекта на Наредба за изменение и допълнение на Наредба № 46 и да представят писмени
                                предложения или становища в 14-дневен срок от публикуването им.
                            </p>


                            <p>
                                <strong>
                                    Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е
                                    взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за
                                    нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект
                                    на наредба включва разпоредби, които имат технически характер и чрез тях се въвежда
                                    изискването на чл. 1, параграф 2 от Делегирана директива (ЕС) 2022/2407, който
                                    регламентира актуалната редакция на правилата към RID – приложима от 1 януари 2023 г.,
                                    която вече е в сила за Република България.
                                </strong>
                            </p>

                            <p>
                                В изпълнение на изискванията на чл. 3, ал. 4, т. 1 от Постановление № 85 на Министерския
                                съвет от 2007 г. за координация по въпросите на Европейския съюз (обн., ДВ, бр. 35 от 2007
                                г., изм., бр. 53 и 64 от 2008 г., бр. 34, 71, 78 и 83 от 2009 г., бр. 4, 5, 19 и 65 от 2010
                                г., попр., бр. 66 от 2010 г., изм., бр. 2 и 105 от 2011 г., доп., бр. 68 от 2012 г., изм.,
                                бр. 62, 65 и 80 от 2013 г., изм. и доп., бр. 53 от 2014 г., изм., бр. 76 от 2014 г., изм. и
                                доп., бр. 94 от 2014 г., изм., бр. 101 от 2014 г., изм. и доп., бр. 6 от 2015 г., изм., бр.
                                36 от 2016 г., изм. и доп., бр. 79 от 2016 г., изм., бр. 7 и 12 от 2017 г., изм. и доп., бр.
                                39 от 2017 г., бр. 3 от 2019 г., изм., бр. 41 от 2021 г.) е изготвена таблица за
                                съответствие с Делегирана директива (ЕС) 2022/2407. Проектът на наредба е съгласуван в
                                рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на
                                работната група.
                            </p>


                            <div class="row">
                                <div class="col-md-6">
                                    <a href="#" class="btn btn-primary">Изтегляне</a>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-end">
                                        <span class="text-end">
                                            <strong>Дата на създаване:</strong> 15.05.2023г.
                                        </span><Br>
                                        <span class="text-end">
                                            <strong>Дата на публикуване:</strong> 20.05.2023г.
                                        </span>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                </div>


            </div>

        </div>
    </section>

    </body>

@endsection
