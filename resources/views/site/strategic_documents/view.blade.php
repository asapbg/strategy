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
        <div class="col-lg-10">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="mb-3">Информация</h2>
                </div>
                <div class="col-md-12 text-старт">
                    <button class="btn btn-primary  main-color">
                        <i class="fa-solid fa-download main-color me-2"></i>Експорт</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-square-rss text-warning me-2"></i>RSS Абониране</button>
                    <button class="btn rss-sub main-color">
                        <i class="fas fa-envelope me-2 main-color"></i>Абониране</button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Дата приемане
                    </h3>
                    <span class="obj-icon-info">
                    <i class="far fa-calendar me-2 main-color" title="Дата на откриване"></i> {{ $strategicDocument->document_date }} {{ trans_choice('custom.date_small', 1) }}
                </span>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Дата на валидност
                    </h3>
                    <span class="obj-icon-info">
                    <i class="far fa-calendar-check me-2 main-color" title="Дата на приключване"></i>{{ $strategicDocument->document_date }} {{ trans_choice('custom.date_small', 1) }} </span>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Категория</h3>
                    <a href="#" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2 main-color"
                           title="Сфера на действие"></i>Централно ниво</span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Област на политика</h3>
                    <a href="#" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="bi bi-mortarboard-fill me-2 main-color"
                           title="Номер на консултация "></i>Образование</span>
                    </a>
                </div>

            </div>

            <div class="row mb-4">
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Вид стратегически документ</h3>
                    <a href="#" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-bezier-curve me-2 main-color" title="Тип консултация"></i>Стратегия </span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Вид,с който е приет документа</h3>
                    <a href="#" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fas fa-solid fa-file-lines me-2 main-color" title="Вносител"></i>Решение
                    </span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Орган приел акта</h3>
                    <a href="#" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-school me-2 main-color" title="История"></i>Министерски съвет</span>
                    </a>
                </div>
                <div class="col-md-3 ">
                    <h3 class="mb-2 fs-18">Уникален номер на консултация</h3>
                    <a href="#" class="main-color text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <i class="fa-solid fa-hashtag me-2 main-color" title="История"></i>1515111</span>
                    </a>
                </div>
            </div>

            <div class="row mt-4 mb-4">
                <div class="col-md-12">
                    <h3 class="mb-3">Описание</h3>
                    <div class="str-doc-info">
                        {!! $strategicDocument->description !!}
                        <!--
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
                            -->
                    </div>
                </div>
            </div>

            <div class="row mb-4 mt-4">
                <h3 class="mb-3">Прилежащи документи</h3>
                <div class="col-md-12">
                    <ul class=" tab nav nav-tabs mb-3" id="myTab">
                        <li class="nav-item pb-0">
                            <a href="#table-view" class="nav-link tablinks active" data-bs-toggle="tab">Табличен изглед</a>
                        </li>
                        <li class="nav-item pb-0">
                            <a href="#tree-view" class="nav-link tablinks" data-bs-toggle="tab">Дървовиден изглед</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="table-view">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none">
                                        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение от Протокол №
                                        13 от
                                        заседанието на Министерския съвет на 24 февруари 2021 година (публ. 08.03.2021
                                        г.)</a>
                                </li>

                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none">
                                        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Стратегическа рамка за
                                        развитие
                                        на образованието, обучението и ученето в Република България (2021 - 2030) (публ.
                                        11.03.2021
                                        г.)</a>
                                </li>

                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none"><i
                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Извлечение от
                                        Протокол №
                                        13 от заседанието на Министерския съвет на 22 март 2023 година (публ. на
                                        29.03.2023
                                        г.)</a>
                                </li>


                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none"><i
                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>План за
                                        действие до
                                        2024 към Стратегическа рамка за развитие на образованието, обучението и
                                        ученето
                                        в Република България (2021 – 2030) (публ. на 29.03.2023 г.)</a>
                                </li>


                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="main-color text-decoration-none"><i
                                            class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>Национална стратегия на
                                        Република България за равенство, приобщаване и участие на ромите (2021 - 2030),
                                        приета с
                                        Решение № 278 от 5 май 2022 година (публ. 11.11.2022 г.) </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="tree-view">
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
                        </div>

                    </div>






                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="hori-timeline px-1" dir="ltr">
                <h3 class="mb-3">История</h3>
                <div class="timeline">
                    <ul class="timeline events">
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Включване на проекта</h5>
                            <p class="mb-2 fw-bold">12.05.2023</p>
                            <p> Tова събитие описва запис на акт в ЗП или ОП.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Начало на обществената консултация</h5>
                            <p class="mb-2 fw-bold">20.05.2023</p>
                            <p> Визуализира се „Начало на консултацията“.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Промяна на файл </h5>
                            <p class="mb-2 fw-bold">25.05.2023</p>
                            <p> Промяна на файл от консултацията.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Приключване на консултацията</h5>
                            <p class="text-muted mb-2 fw-bold ">01.06.2023</p>
                            <p class="text-muted">Край на консултацията</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Справка за получените предложения</h5>
                            <p class="text-muted mb-2 fw-bold">15.06.2023</p>
                            <p class="text-muted">Справка или съобщение.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Приемане на акта от Министерския съвет</h5>
                            <p class="text-muted mb-2 fw-bold text-muted">18.06.2023</p>
                            <p class="text-muted">Окончателен акт.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18"> Представяне на законопроекта</h5>
                            <p class="text-muted mb-2 fw-bold ">25.06.2023</p>
                            <p class="text-muted">Развито в обхвата на текущата поръчка.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
