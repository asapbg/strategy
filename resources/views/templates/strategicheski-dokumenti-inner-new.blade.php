@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Стратегически документи - вътрешна страница')


@section('content')
    <div class="row edit-consultation m-0" style="top: 17.5%;">
        <div class="col-md-12 text-end">
            <button class="btn btn-sm btn-primary main-color mt-2">
                <i class="fas fa-pen me-2 main-color"></i>Редактиране на документ</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
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
                    <h3 class="mb-2 fs-4">Област на политика:</h3>
                    <div class="mb-2 ms-2 fs-4">
                        <a href="#" class="main-color text-decoration-none">
                            <i class="bi bi-mortarboard-fill me-1 main-color"
                               title="Номер на консултация "></i>  Образование
                        </a>
                    </div>

                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">Вид стратегически документ</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>Стратегия </span>
                    </a>
                </div>
                <div class="col-md-4">
                    <!--
                    "Документът е към“, в което да се попълва линк и заглавие на родителски стратегически документ, ако такъв е зададен.
                    -->
                    <h3 class="mb-2 fs-5">Свързан със</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>Областна стратегия за развитие на Област Варна 2005 - 2015 г.</span>
                    </a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">Дата приемане</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar main-color me-2 fs-18" title="Тип консултация"></i>24.02.2021г.</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">Дата на валидност</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-calendar-check me-2 main-color fs-18" title="Тип консултация"></i>2023 г.</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <!-- От заглавната част актът, с който е приет документът,
         да се визуализира отделно и да се сглоби от компонентите си,
         подобно на в ПРИС – „Решение No XXXX/YY.ZZ.WWWW на Министерския съвет“
         и да е линк, който се взима от полето „Връзка към акта“. (Номер на решение не е задължителен, ако органът е НС). -->
                    <h3 class="mb-2 fs-5">Акт на приемане</h3>
                    <div class="mb-2 fs-18">
                        <span>Решение</span>
                        <a href="#" class="main-color text-decoration-none">
                            №: XXXX/YY.ZZ.WWWW
                        </a>
                        <span>на</span>
                        <a href="#" class="main-color text-decoration-none">
                            Министерски съвет
                        </a>
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <!--
                    Трети ред: категория, линк към обществена консултация (ако има), линк към Мониторстат (ако има).
                -->
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">Категория</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-arrow-right-to-bracket main-color me-2 fs-18" title="Тип консултация"></i>Централно ниво</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">Линк към обществена консултация</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>Проект на Закон за изменение и допълнение на Закона за съдебната власт</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-2 fs-5">Линк към Мониторстат</h3>
                    <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>Линк към Мониторстат</span>
                    </a>
                </div>
            </div>


            <div class="row mt-4 mb-4">
                <div class="col-md-12">
                    <h3 class="mb-3">Описание</h3>
                    <div class="str-doc-info">
                        <p><strong>Стратегическата рамка за развитие на образованието, обучението и ученето в Република
                                България (2021 – 2030)</></strong> (Стратегическа рамка) е изготвена от Министерството на
                            образованието и науката в сътрудничество със заинтересовани страни. Настоящият стратегически
                            документ и Стратегията за развитие на висшето образование в Република България в периода от 2020
                            година до 2030 година очертават общата рамка за развитие на образованието, обучението и ученето
                            в Република България до 2030 година.&nbsp;</p>
                        <p>С <strong>Протоколно решение № 13.1 на Министерския съвет от 22.03.2023 г. е приет План за
                                действие до 2024 година към Стратегическата рамка за развитие на образованието, обучението и
                                ученето в Република България (2021-2030)</strong>. С приемането на този документ се
                            изпълнява и ангажиментът на страната по Компонент 1. „Образование и умения“ от Националния план
                            за възстановяване и устойчивост.</p>
                        <p>Планът за действие трябва да доведе до подобряване на качеството и приложимостта на
                            образованието, на комплексната образователна реформа, до засилване и изграждането на уменията и
                            ключовите компетентности сред децата и учениците. Освен това се предвижда да се повишат
                            интересът и мотивацията за учене, ангажираността на всички в образователния процес, нивото на
                            придобитите умения, адаптивността към околната среда и пазара на труда, както и да се ускори
                            приобщаване на всяко дете и ученик в образователния процес.</p>
                        <p>В плана са заложени дейности по различните приоритетни области на Стратегическата рамка, срокове
                            за изпълнението им, предвидени са източници на финансиране, както и индикатори за проследяване и
                            анализ на изпълнението. Тези дейности ще реализират заложените в Стратегическата рамка цели и
                            мерки, които обхващат всички значими предизвикателства в системата на образованието и обучението
                            като: ранното детско развитие, насърчаването на компетентности и таланти, квалификацията на
                            мотивирани и креативни учители, израждането на сплотени училищни общности и системната работа с
                            родителите, ефективното включване, трайното приобщаване и образователната интеграция,
                            въвеждането на образователни иновации, дигиталната трансформация и устойчивото развитие,
                            реализацията в професиите на настоящето и бъдещето, ученето през целия живот, ефикасното
                            управление.</p>
                    </div>
                </div>
            </div>

            {{--        <div class="row mb-4 mt-4">--}}
            {{--            <!-- При клик върху основния документ, трябва да се визуализира в модал и да има изтегляне.--}}
            {{--            Пример: https://strategy.asapbg.com/templates/public_consultations_view от файловете Пакет основни документи -->--}}
            {{--            <h3 class="mb-3">Основен документ</h3>--}}
            {{--            <div class="col-md-12">--}}
            {{--                <ul class="list-group list-group-flush">--}}
            {{--                    <li class="list-group-item">--}}
            {{--                            <a href="#" class="main-color text-decoration-none" type="button" data-toggle="modal" data-target="#exampleModal"> <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Основен документ</a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}

            {{--                <!-- Модал -->--}}

            {{--                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
            {{--                    <div class="modal-dialog file-display" role="document">--}}
            {{--                      <div class="modal-content">--}}
            {{--                        <div class="modal-header">--}}
            {{--                          <h5 class="modal-title" id="exampleModalLabel">Документ 1</h5>--}}
            {{--                          <a type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
            {{--                            <span aria-hidden="true">--}}
            {{--                              <i class="fa-solid fa-xmark main-color fs-5"></i></span>--}}
            {{--                          </a>--}}
            {{--                        </div>--}}
            {{--                        <div class="modal-body">--}}
            {{--                          <div class="col-md-12">--}}
            {{--                            <p> С проекта на наредба се предлагат: I. Промени, свързани с въвеждането в националното законодателство на Делегирана директива (ЕС) 2022/2407 на Комисията от 20 септември 2022 година за изменение на приложенията към Директива 2008/68/ЕО на Европейския парламент и на Съвета с оглед на адаптирането към научно-техническия прогрес (ОВ L 317, 09/12/2022) (Делегирана директива (ЕС) 2022/2407). Директива 2008/68/ЕО относно вътрешния превоз на опасни товари (2008/68/ЕО) е въведена в националното законодателство с Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни товари. Съгласно чл. 8, параграф 1 от Директива 2008/68/ЕО, Европейската комисия приема делегирани актове за изменение на приложенията на директивата с цел отчитане на измененията на ADR, RID и ADN, по-специално свързаните с научния и техническия прогрес, включително използването на технологии за локализиране и проследяване. Приетите въз основа на цитирания текст актове, въвеждащи изменения на приложенията към Директива 2008/68/ЕО, са въведени в Наредба № 46, с изключение на Делегирана директива (ЕС) 2022/2407. Текстът, който следва да се транспонира от посочената делегирана директива се съдържа в чл. 1, т. 2 от нея и гласи: „в приложение II раздел II.1 се заменя със следното: „II.1. RID </p>--}}
            {{--                            <p>--}}
            {{--                              <strong>Приложението към RID, приложимо от 1 януари 2023 г., като се разбира, че където е уместно „RID договаряща държава" се заменя с „държава членка“.</strong>--}}
            {{--                            </p>--}}
            {{--                            <p> Подобен текст вече е транспониран и се съдържа в чл. 2, ал. 1 от Наредба № 46. За да се транспонира Делегирана директива (ЕС) 2022/2407 е необходимо да се добави заглавието й в края на § 2 от Заключителните разпоредби на действащата Наредба № 46, което се предлага в параграф единствен от проекта на наредба. Предложеният проект на Наредба за изменение и допълнение на Наредба № 46 не оказва пряко/или косвено въздействие върху държавния бюджет. Не са необходими финансови и други средства за прилагането на новата уредба. На основание чл. 26, ал. 2-4 от Закона за нормативните актове проектът на наредба, заедно с доклада към него, е публикуван за обществено обсъждане на страницата на Министерството на транспорта, информационните технологии и съобщенията и на Портала за обществени консултации на Министерски съвет. На заинтересованите лица е предоставена възможност да се запознаят с проекта на Наредба за изменение и допълнение на Наредба № 46 и да представят писмени предложения или становища в 14-дневен срок от публикуването им. </p>--}}
            {{--                            <p>--}}
            {{--                              <strong> Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект на наредба включва разпоредби, които имат технически характер и чрез тях се въвежда изискването на чл. 1, параграф 2 от Делегирана директива (ЕС) 2022/2407, който регламентира актуалната редакция на правилата към RID – приложима от 1 януари 2023 г., която вече е в сила за Република България. </strong>--}}
            {{--                            </p>--}}
            {{--                            <p> В изпълнение на изискванията на чл. 3, ал. 4, т. 1 от Постановление № 85 на Министерския съвет от 2007 г. за координация по въпросите на Европейския съюз (обн., ДВ, бр. 35 от 2007 г., изм., бр. 53 и 64 от 2008 г., бр. 34, 71, 78 и 83 от 2009 г., бр. 4, 5, 19 и 65 от 2010 г., попр., бр. 66 от 2010 г., изм., бр. 2 и 105 от 2011 г., доп., бр. 68 от 2012 г., изм., бр. 62, 65 и 80 от 2013 г., изм. и доп., бр. 53 от 2014 г., изм., бр. 76 от 2014 г., изм. и доп., бр. 94 от 2014 г., изм., бр. 101 от 2014 г., изм. и доп., бр. 6 от 2015 г., изм., бр. 36 от 2016 г., изм. и доп., бр. 79 от 2016 г., изм., бр. 7 и 12 от 2017 г., изм. и доп., бр. 39 от 2017 г., бр. 3 от 2019 г., изм., бр. 41 от 2021 г.) е изготвена таблица за съответствие с Делегирана директива (ЕС) 2022/2407. Проектът на наредба е съгласуван в рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на работната група. </p>--}}
            {{--                          </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="modal-footer">--}}
            {{--                          <button type="button" class="btn btn-primary">Изтегляне</button>--}}
            {{--                        </div>--}}
            {{--                      </div>--}}
            {{--                    </div>--}}
            {{--                  </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}

            <div class="row mb-4 mt-4">
                <div class="row table-light">
                    <div class="col-12 mb-2">
                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">Файлове</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027990" data-url="https://strategy.asapbg.com/file-preview-modal/1027990">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 3 | 18.12.2023
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mb-0 mt-5">
                <div class="mb-2">
                    <h2 class="mb-1">Дъщерни документи</h2>
                </div>
            </div>
            <div class="row p-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5029">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5029" aria-expanded="false" aria-controls="collapse5029">
                                    <i class="me-1 bi bi-pin-map-fill main-color fs-18"></i>
                                    Програма за закрила на детето 2020 – 2022 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5029" class="collapse" aria-labelledby="heading5029" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">Файлове</p>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ps-4 pe-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5030">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5030" aria-expanded="false" aria-controls="collapse5030">
                                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                                    План за закрила на детето за 2020 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5030" class="collapse" aria-labelledby="heading5030" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">Файлове</p>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ps-4 pe-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5041">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5041" aria-expanded="false" aria-controls="collapse5041">
                                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                                    План за закрила на детето за 2021 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5041" class="collapse" aria-labelledby="heading5041" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">Файлове</p>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ps-4 pe-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5042">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5042" aria-expanded="false" aria-controls="collapse5042">
                                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                                    План за закрила на детето за 2022 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5042" class="collapse" aria-labelledby="heading5042" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">Файлове</p>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5031">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start  collapsed " type="button" data-toggle="collapse" data-target="#collapse5031" aria-expanded="false " aria-controls="collapse5031">
                                    <i class="me-1 bi bi-pin-map-fill main-color fs-18"></i>
                                    Програма за закрила на детето 2023 – 2025 г.
                                </button>
                            </h2>
                        </div>

                        <div id="collapse5031" class="collapse " aria-labelledby="heading5031" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="fs-18 fw-600 main-color-light-bgr p-2 rounded mb-2">Файлове</p>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row ps-4 pe-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5050">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5050" aria-expanded="false" aria-controls="collapse5050">
                                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                                    План за закрила на детето за 2023 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5050" class="collapse" aria-labelledby="heading5050" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                </div>
                                <h4 class="mb-3">Файлове</h4>
                                <div class="row table-light">
                                    <div class="col-12 mb-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ps-4 pe-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5051">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5051" aria-expanded="false" aria-controls="collapse5051">
                                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                                    План за закрила на детето за 2024 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5051" class="collapse" aria-labelledby="heading5051" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                </div>
                                <h4 class="mb-3">Файлове</h4>
                                <div class="row table-light">
                                    <div class="col-12 mb-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ps-4 pe-1 mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="card custom-card">
                        <div class="card-header" id="heading5052">
                            <h2 class="mb-0">
                                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#collapse5052" aria-expanded="false" aria-controls="collapse5052">
                                    <i class="me-1 fa-solid fa-arrow-right-to-bracket  main-color fs-18"></i>
                                    План за закрила на детето за 2025 г.
                                </button>
                            </h2>
                        </div>
                        <div id="collapse5052" class="collapse" aria-labelledby="heading5052" data-parent="#accordionExample" style="">
                            <div class="card-body">
                                <div class="row">
                                    Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, но е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. Популяризиран е през 60те години на 20ти век със издаването на Letraset листи, съдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни издания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.
                                </div>
                                <h4 class="mb-3">Файлове</h4>
                                <div class="row table-light">
                                    <div class="col-12 mb-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1041547" data-url="https://strategy.asapbg.com/file-preview-modal/1041547">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 1 | 19.12.2023
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="Прегледай" data-file="1027988" data-url="https://strategy.asapbg.com/file-preview-modal/1027988">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i> Файл 2 | 18.12.2023
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
