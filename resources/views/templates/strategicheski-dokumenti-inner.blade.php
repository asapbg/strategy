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
                    <i class="fas fa-square-rss text-warning me-2"></i>RSS Абониране</button>
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
                <h3 class="mb-2 fs-5">Документът е към</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color fs-18" title="Тип консултация"></i>Линк и заглавие на родителски стратегически документ</span>
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
                        <i class="fas fa-link me-2 main-color fs-18" title="Тип консултация"></i>Линк към ОК</span>
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

        <div class="row mb-4 mt-4">
            <!-- При клик върху основния документ, трябва да се визуализира в модал и да има изтегляне. 
            Пример: https://strategy.asapbg.com/templates/public_consultations_view от файловете Пакет основни документи -->
            <h3 class="mb-3">Основен документ</h3>
            <div class="col-md-12">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                            <a href="#" class="main-color text-decoration-none" type="button" data-toggle="modal" data-target="#exampleModal"> <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Основен документ</a>
                    </li>
                </ul>

                <!-- Модал -->

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog file-display" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Документ 1</h5>
                          <a type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                              <i class="fa-solid fa-xmark main-color fs-5"></i></span>
                          </a>
                        </div>
                        <div class="modal-body">
                          <div class="col-md-12">
                            <p> С проекта на наредба се предлагат: I. Промени, свързани с въвеждането в националното законодателство на Делегирана директива (ЕС) 2022/2407 на Комисията от 20 септември 2022 година за изменение на приложенията към Директива 2008/68/ЕО на Европейския парламент и на Съвета с оглед на адаптирането към научно-техническия прогрес (ОВ L 317, 09/12/2022) (Делегирана директива (ЕС) 2022/2407). Директива 2008/68/ЕО относно вътрешния превоз на опасни товари (2008/68/ЕО) е въведена в националното законодателство с Наредба № 46 от 30.11.2001 г. за железопътен превоз на опасни товари. Съгласно чл. 8, параграф 1 от Директива 2008/68/ЕО, Европейската комисия приема делегирани актове за изменение на приложенията на директивата с цел отчитане на измененията на ADR, RID и ADN, по-специално свързаните с научния и техническия прогрес, включително използването на технологии за локализиране и проследяване. Приетите въз основа на цитирания текст актове, въвеждащи изменения на приложенията към Директива 2008/68/ЕО, са въведени в Наредба № 46, с изключение на Делегирана директива (ЕС) 2022/2407. Текстът, който следва да се транспонира от посочената делегирана директива се съдържа в чл. 1, т. 2 от нея и гласи: „в приложение II раздел II.1 се заменя със следното: „II.1. RID </p>
                            <p>
                              <strong>Приложението към RID, приложимо от 1 януари 2023 г., като се разбира, че където е уместно „RID договаряща държава" се заменя с „държава членка“.</strong>
                            </p>
                            <p> Подобен текст вече е транспониран и се съдържа в чл. 2, ал. 1 от Наредба № 46. За да се транспонира Делегирана директива (ЕС) 2022/2407 е необходимо да се добави заглавието й в края на § 2 от Заключителните разпоредби на действащата Наредба № 46, което се предлага в параграф единствен от проекта на наредба. Предложеният проект на Наредба за изменение и допълнение на Наредба № 46 не оказва пряко/или косвено въздействие върху държавния бюджет. Не са необходими финансови и други средства за прилагането на новата уредба. На основание чл. 26, ал. 2-4 от Закона за нормативните актове проектът на наредба, заедно с доклада към него, е публикуван за обществено обсъждане на страницата на Министерството на транспорта, информационните технологии и съобщенията и на Портала за обществени консултации на Министерски съвет. На заинтересованите лица е предоставена възможност да се запознаят с проекта на Наредба за изменение и допълнение на Наредба № 46 и да представят писмени предложения или становища в 14-дневен срок от публикуването им. </p>
                            <p>
                              <strong> Решението за определяне на по-кратък срок за обществено обсъждане на проекта на акт е взето в съответствие с изискванията на чл. 26, ал. 4, изречение второ от Закона за нормативните актове, като е съобразено, че публикуваният за обществено обсъждане проект на наредба включва разпоредби, които имат технически характер и чрез тях се въвежда изискването на чл. 1, параграф 2 от Делегирана директива (ЕС) 2022/2407, който регламентира актуалната редакция на правилата към RID – приложима от 1 януари 2023 г., която вече е в сила за Република България. </strong>
                            </p>
                            <p> В изпълнение на изискванията на чл. 3, ал. 4, т. 1 от Постановление № 85 на Министерския съвет от 2007 г. за координация по въпросите на Европейския съюз (обн., ДВ, бр. 35 от 2007 г., изм., бр. 53 и 64 от 2008 г., бр. 34, 71, 78 и 83 от 2009 г., бр. 4, 5, 19 и 65 от 2010 г., попр., бр. 66 от 2010 г., изм., бр. 2 и 105 от 2011 г., доп., бр. 68 от 2012 г., изм., бр. 62, 65 и 80 от 2013 г., изм. и доп., бр. 53 от 2014 г., изм., бр. 76 от 2014 г., изм. и доп., бр. 94 от 2014 г., изм., бр. 101 от 2014 г., изм. и доп., бр. 6 от 2015 г., изм., бр. 36 от 2016 г., изм. и доп., бр. 79 от 2016 г., изм., бр. 7 и 12 от 2017 г., изм. и доп., бр. 39 от 2017 г., бр. 3 от 2019 г., изм., бр. 41 от 2021 г.) е изготвена таблица за съответствие с Делегирана директива (ЕС) 2022/2407. Проектът на наредба е съгласуван в рамките на Работна група 9 „Транспортна политика“, за което е приложено становище на работната група. </p>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary">Изтегляне</button>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>

        <div class="row mb-4 mt-4">
            <h3 class="mb-3">Документи</h3>
            <div class="col-md-12">
                <ul class=" tab nav nav-tabs mb-3" id="myTab">
                    <li class="nav-item pb-0">
                        <a href="#table-view" class="nav-link tablinks active" data-toggle="tab">Прилежащи
                            документи</a>
                    </li>
                    <li class="nav-item pb-0">
                        <a href="#tree-view" class="nav-link tablinks" data-toggle="tab">Отчети и доклади</a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="table-view">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="#" class="main-color text-decoration-none">
                                    <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение от Протокол №
                                    13 от
                                    заседанието на Министерския съвет на 24 февруари 2021 година
                                    <span class="fw-bold">&#123;</span>
                                    <span class="valid-date fw-bold"> Публикувано 10.11.2023</span>
                                    <span> /</span>
                                    <span class="valid-date fw-bold"> Валидност 10.11.2023 </span>
                                    <span> /</span>
                                    <span class="str-doc-type fw-bold"> Програма </span>
                                    <span class="fw-bold">&#125;</span>
                                </a>
                            </li>

                            <li class="list-group-item">  
                                    <a href="#" class="main-color text-decoration-none">
                                        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Стратегическа рамка за
                                        развитие на образованието, обучението и ученето в Република България
                                        <span class="fw-bold">&#123;</span>
                                        <span class="valid-date fw-bold"> Публикувано 21.11.2023</span>
                                        <span> /</span>
                                        <span class="valid-date fw-bold"> Валидност 04.02.2024 </span>
                                        <span> /</span>
                                        <span class="str-doc-type fw-bold"> Стратегия </span>
                                        <span class="fw-bold">&#125;</span>
                                    </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="tree-view">
                        <!--
                        <ul class="tree list-unstyled">
                            <li class="tree-item">
                                <a href="#" class="trigger text-decoration-none"><i
                                        class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение от Протокол №
                                    13 от заседанието на Министерския съвет на 24 февруари 2021 година (публ. 08.03.2021
                                    г.)</a>
                            </li>
                            <ul class="tree list-unstyled">
                                <li class="tree-item">
                                    <a href="#" class="trigger text-decoration-none"><i
                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Стратегическа рамка
                                        за развитие на образованието, обучението и ученето в Република България (2021 -
                                        2030) (публ. 11.03.2021 г.)</a>

                                    <ul class="tree-parent open list-unstyled">
                                        <li class="tree-item view">
                                            <a href="#" class="text-decoration-none"> <i
                                                    class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение
                                                от Протокол № 13 от заседанието на Министерския съвет на 22 март 2023
                                                година (публ. на 29.03.2023 г.)</a>
                                            <ul class="tree-parent open list-unstyled">
                                                <li class="tree-item view">
                                                    <a href="#" class="text-decoration-none"> <i
                                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>План
                                                        за действие до 2024 към Стратегическа рамка за развитие на
                                                        образованието, обучението и ученето в Република България (2021 –
                                                        2030) (публ. на 29.03.2023 г.)</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="tree-item controllers">
                                    <a href="#" class="trigger text-decoration-none"><i
                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Национална стратегия
                                        на Република България за равенство, приобщаване и участие на ромите (2021 -
                                        2030), приета с Решение № 278 от 5 май 2022 година (публ. 11.11.2022 г.)</a>
                                </li>
                            </ul>
                        </ul>
                    -->
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="#" class="main-color text-decoration-none">
                                    <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Отчет 1 
                                    <span class="fw-bold">&#123;</span>
                                    <span class="valid-date fw-bold"> Публикувано 10.07.2023</span>
                                    <span> /</span>
                                    <span class="valid-date fw-bold"> Валидност 19.11.2023 </span>
                                    <span> /</span>
                                    <span class="str-doc-type fw-bold"> Програма </span>
                                    <span class="fw-bold">&#125;</span>
                                </a>
                            </li>

                            <li class="list-group-item">  
                                    <a href="#" class="main-color text-decoration-none">
                                        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Доклад 2
                                        <span class="fw-bold">&#123;</span>
                                        <span class="valid-date fw-bold"> Публикувано 20.08.2023</span>
                                        <span> /</span>
                                        <span class="valid-date fw-bold"> Валидност 04.02.2028 </span>
                                        <span> /</span>
                                        <span class="str-doc-type fw-bold"> Стратегия </span>
                                        <span class="fw-bold">&#125;</span>
                                    </a>
                            </li>
                        </ul>
                    </div>

                </div>






            </div>
        </div>
    </div>
</div>
</section>
@endsection
