@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Стратегически документи - вътрешна страница')


@section('content')
<div class="row edit-consultation m-0" style="top: 17.5%;">
    <div class="col-md-12 text-end">
        <button class="btn btn-sm btn-primary main-color mt-2">
            <i class="fas fa-pen me-2 main-color"></i>Редактиране на национален план</button>
    </div>
</div>

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
                                <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Национални планове
                                        за действие</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценка за
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


    <div class="col-lg-10 pt-5 pb-5">
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
            <div class="col-md-12 d-flex align-items-center">
                <h3 class="mb-2 fs-4">Област:</h3>
                <div class="mb-2 ms-2 fs-4">
                    <a href="#" class="main-color text-decoration-none">
                        <i class="bi bi-globe2 fa-link me-1 main-color"
                        title="Номер на консултация "></i>  Електронно управление
                    
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
                        <i class="fas fa-calendar-check me-2 main-color fs-18" title="Тип консултация"></i>2023 г.</span>
                </a>
            </div>
            <div class="col-md-4">
                <h3 class="mb-2 fs-5">Статус</h3>
                <a href="#" class="main-color text-decoration-none fs-18">
                    <span class="active-ks fs-16">Действащ</span>
                </a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <h3 class="mb-2 fs-5">Подниво мярка </h3>
                <p>
                    Актуализиране на съдържанието на Регистрите за оперативна съвместимост (Регистър на регистрите и данните – РРД, Регистър на електронните услуги – РЕУ и Регистър на информационните обекти – РИО, Регистър на стандартите, Списък на акредитираните лица и Списък на сертифицираните системи и продукти) на електронното правителство.
                </p>
            </div>
        </div>

        



        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                <h3 class="mb-3">
                    Тематична област №1 - Достъп до информация
                  </h3>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                      <thead class="table-light align-middle fw-600">
                        <tr>
                            <td>
                                №
                            </td>
                            <td>
                                Наименование на мярката
                            </td>
                            <td>
                                Очакван резултат
                            </td>
                            <td>
                                Отговорна институция
                            </td>
                            <td>
                                Срок
                            </td>
                            <td>
                                Финансиране
                            </td>
                            <td>
                                Индикатори
                            </td>
                        </tr>
                    </thead>

                    
                      <tbody>
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                Нова Стратегия за управление на държавния дълг за периода 2012- 2014 г.
                            </td>
                            <td>
                                Синтезирани общи насоки за политиката по управление на държавния дълг в тригодишен период. Осигуряване на условия за прозрачност и отчетност при управлението на държавния дълг.
                            </td>
                            <td>
                                Министерство на финансите
                            </td>
                            <td>
                                2012 г.
                            </td>
                            <td>
                                Бюджет на МФ
                            </td>
                            <td>
                                Одобрена нова Стратегия за управление на държавния дълг за периода 2012-2014 г.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                2
                            </td>
                            <td>
                                Разработване на цялостна Визия за създаване на механизъм за финансиране на гражданския сектор и на Стратегия за подкрепа на развитието на гражданските организации в Република България.
                            </td>
                            <td>
                                Определяне на възможности за финансиране на организациите, за които няма законово уредена действаща практика за партньорството с държавата и общините, постигане на повече прозрачност при използване на средствата от бюджета, предназначени за НПО.
                            </td>
                            <td>
                                Министър по управление на средствата от Европейския съюз;
                                Министерство на финансите                            
                            </td>
                            <td>
                                2012 г.
                            </td>
                            <td>
                                Бюджет на МФ
                            </td>
                            <td>
                                Разработена Визия и Стратегия за развитието на гражданските организации в Република България
                            </td>
                        </tr>
                        <tr>
                            <td>
                                3
                            </td>
                            <td>
                                Публикуване на финансовите отчети и на отчетите за изпълнение на постигнатите резултати на министерствата и ведомствата, вкл. и на техните второстепенни разпоредители с бюджетни кредити (ВРБК).                       
                            </td>
                            <td>
                                Подобрена отчетност и бюджетна прозрачност и по-ясна отговорност за изразходваните ресурси и постигнатите резултати.
                            </td>

                            <td>
                                Министерство на финансите и останалите първостепенни разпоредители с бюджетни кредити (ПРБК).
                            </td>
                            <td>
                                В съответствие със сроковете, заложени в нормативните актове – за периодичните финансови отчети и отчетите за степента на изпълнение на политиките и програмите
                                                   
                            <td>
                                Бюджет на ПРБК
                            </td>
                            <td>
                                Публикувани отчети
                            </td>
    
                        </tr>
                        <tr>
                            <td>
                                4
                            </td>
                            <td>
                                Нова Стратегия за управление на държавния дълг за периода 2012- 2014 г.
                            </td>
                            <td>
                                Синтезирани общи насоки за политиката по управление на държавния дълг в тригодишен период. Осигуряване на условия за прозрачност и отчетност при управлението на държавния дълг.
                            </td>
                            <td>
                                Министерство на финансите
                            </td>
                            <td>
                                2012 г.
                            </td>
                            <td>
                                Бюджет на МФ
                            </td>
                            <td>
                                Одобрена нова Стратегия за управление на държавния дълг за периода 2012-2014 г.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                5
                            </td>
                            <td>
                                Разработване на цялостна Визия за създаване на механизъм за финансиране на гражданския сектор и на Стратегия за подкрепа на развитието на гражданските организации в Република България.
                            </td>
                            <td>
                                Определяне на възможности за финансиране на организациите, за които няма законово уредена действаща практика за партньорството с държавата и общините, постигане на повече прозрачност при използване на средствата от бюджета, предназначени за НПО.
                            </td>
                            <td>
                                Министър по управление на средствата от Европейския съюз;
                                Министерство на финансите                            
                            </td>
                            <td>
                                2012 г.
                            </td>
                            <td>
                                Бюджет на МФ
                            </td>
                            <td>
                                Разработена Визия и Стратегия за развитието на гражданските организации в Република България
                            </td>
                        </tr>
                        <tr>
                            <td>
                               6
                            </td>
                            <td>
                                Публикуване на финансовите отчети и на отчетите за изпълнение на постигнатите резултати на министерствата и ведомствата, вкл. и на техните второстепенни разпоредители с бюджетни кредити (ВРБК).                       
                            </td>
                            <td>
                                Подобрена отчетност и бюджетна прозрачност и по-ясна отговорност за изразходваните ресурси и постигнатите резултати.
                            </td>

                            <td>
                                Министерство на финансите и останалите първостепенни разпоредители с бюджетни кредити (ПРБК).
                            </td>
                            <td>
                                В съответствие със сроковете, заложени в нормативните актове – за периодичните финансови отчети и отчетите за степента на изпълнение на политиките и програмите
                                                   
                            <td>
                                Бюджет на ПРБК
                            </td>
                            <td>
                                Публикувани отчети
                            </td>
    
                        </tr>
                      </tbody>

                    </table>
                  </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
