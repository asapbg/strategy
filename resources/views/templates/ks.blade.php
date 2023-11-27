@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Консултативни съвети - Вътрешна страница')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Консултативни съвети
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2 "><a href="#" class="link-dark text-decoration-none">Контакти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Актуална информация
                                        и
                                        събития</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Новини</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>
    <div class="col-lg-10 py-5">
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
                <h2 class="mb-2">Информация</h2>
            </div>
        </div>
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Област на политика</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="#" class="main-color text-decoration-none fs-5">
                            <i class="fa-solid fa-hospital me-1 main-color" title="Номер на консултация "></i>
                            Здравеопазване
                        </a>
                    </li>
                </ul>
            </div>
          </div>
        </div>
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Наименование на консултативния съвет</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        Висш съвет по фармация
                    </li>
                </ul>
              </div>
            </div>
        </div>   

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Наличие на представител на НПО в състава на съвета </h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Представител/и на академичната общност;
                    </li>
                    <li class="list-group-item">Представител/и на местното самоуправление/на Националното сдружение на
                        общините в Република България;
                    </li>
                </ul>
              </div>
            </div>
        </div>
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Орган, кой който е създаден съветът</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="#" class="main-color text-decoration-none">
                            <i class="fa-solid fa-right-to-bracket me-1 main-color" title="Номер на консултация "></i>
                            Министерски съвет
                        </a>
                    </li>
                </ul>
            </div>
          </div>
        </div>
        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Председател/и</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Проф. Христо Хинков, министър на здравеопазването
                    </li>
                </ul>
            </div>
          </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Заместник-председател/и
                </h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Проф. Илко Гетов, заместник-министър
                    </li>
                </ul>
              </div>
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Членове на съвета</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">д-р Александър Златанов, <a href="#" class="text-decoration-none">МЗ</a>
                    </li>
                    <li class="list-group-item">Маг. фарм. Венда Зидарова, <a href="#"
                            class="text-decoration-none">МЗ</a> </li>
                    <li class="list-group-item">Христина Гетова,<a href="#" class="text-decoration-none">МЗ</a> </li>
                    <li class="list-group-item">Маг.фарм. Розалина Кулаксъзова, <a href="#"
                            class="text-decoration-none">ИАЛ</a>
                    </li>
                    <li class="list-group-item">маг.-фар. Любима Бургазлиева, <a href="#"
                            class="text-decoration-none">Български фармацевтичен съюз</a>
                    </li>
                    <li class="list-group-item">Проф. Асена Сербезова, <a href="#"
                            class="text-decoration-none">Български фармацевтичен съюз</a>
                    </li>
                </ul>
              </div>
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Секретар</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">маг. фарм. Таня Гергинова, държавен експерт в дирекция "Лекарствена
                        политика", отдел "Лекарствени продукти", <a href="#" class="text-decoration-none">Министерство
                            на здравеопазването.</a>
                    </li>
                </ul>
            </div>
          </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Секретариат</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Лице за контакт: </strong>: маг. фарм. Таня Гергинова, главен
                        експерт в дирекция "Лекарствена политика", отдел "Лекарствени продукти", <a href="#"
                            class="text-decoration-none">Министерство на здравеопазването.</a></li>
                    <li class="list-group-item"><strong>Телефон: </strong> <a href="tel:+3599301131"
                            class="text-decoration-none">02/93 01 131</a>
                    </li>
                    <li class="list-group-item"><strong>E-mail: </strong><a href="#"
                            class="text-decoration-none">tgerginova@mh.government.bg</a>
                    </li>
                </ul>
            </div>
          </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Правилник за вътрешната организация на дейността</h3>
                <div class="document-wrapper-ks mt-3">
                  <a href="#" class="main-color text-decoration-none fs-18"><i
                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                    <div class="document-info-field d-flex mt-3 pb-3">                         
                      <div class="doc-info-item">
                       <strong> Статус:</strong> <span class="active-li w-min-content">Активен</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ПМС №:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">150 обн.</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ДВ:</strong> 
                        <span>
                          <a href="#" class="text-decoration-none">№105</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> В сила от:</strong> 
                        <span>
                          10.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Дата на публикуване:</strong>
                        <span>
                          15.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Вид:</strong>
                        <span class="text-success">Действащ документ</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Версии:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                        </span>
                      </div>
                    </div>            
                </div> 
              </div>  
            </div>
        </div>

        <div class="row mb-5 ks-row">         
            <div class="col-md-12 ">
              <div class="custom-card p-3">
                <h3 class="mb-3 fs-4">Работна програма</h3>      
                    <p>Свободен текст на работна програма</p>
                    <p class="fw-600">Примерна таблица</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Heading</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                    <td>Cell</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                              
                    <div class="document-wrapper-ks mt-3">
                      <a href="#" class="main-color text-decoration-none fs-18"><i
                        class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                        <div class="document-info-field d-flex mt-3 pb-3">                         
                          <div class="doc-info-item">
                           <strong> Статус:</strong> <span class="active-li w-min-content">Активен</span>
                          </div>
                          <div class="doc-info-item">
                            <strong> ПМС №:</strong>
                            <span>
                              <a href="#" class="text-decoration-none">150 обн.</a>
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> ДВ:</strong> 
                            <span>
                              <a href="#" class="text-decoration-none">№105</a>
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> В сила от:</strong> 
                            <span>
                              10.10.2023г.
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> Дата на публикуване:</strong>
                            <span>
                              15.10.2023г.
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> Вид:</strong>
                            <span class="text-success">Действащ документ</span>
                          </div>
                          <div class="doc-info-item">
                            <strong> Версии:</strong>
                            <span>
                              <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                            </span>
                          </div>
                        </div>            
                    </div> 
                    <div class="document-wrapper-ks mt-3">
                      <a href="#" class="main-color text-decoration-none fs-18"><i
                        class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                        <div class="document-info-field d-flex mt-3 pb-3">                         
                          <div class="doc-info-item">
                           <strong> Статус:</strong> <span class="closed-li w-min-content">Нективен</span>
                          </div>
                          <div class="doc-info-item">
                            <strong> ПМС №:</strong>
                            <span>
                              <a href="#" class="text-decoration-none">150 обн.</a>
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> ДВ:</strong> 
                            <span>
                              <a href="#" class="text-decoration-none">№105</a>
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> В сила от:</strong> 
                            <span>
                              10.10.2023г.
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> Дата на публикуване:</strong>
                            <span>
                              15.10.2023г.
                            </span>
                          </div>
                          <div class="doc-info-item">
                            <strong> Вид:</strong>
                            <span class="text-danger">Недействащ документ</span>
                          </div>
                          <div class="doc-info-item">
                            <strong> Версии:</strong>
                            <span>
                              <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                            </span>
                          </div>
                        </div>            
                    </div> 
              </div>              
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Нормативна рамка</h3>
                <p>Свободен текст</p>
                <div class="document-wrapper-ks mt-3">
                  <a href="#" class="main-color text-decoration-none fs-18"><i
                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                    <div class="document-info-field d-flex mt-3 pb-3">                         
                      <div class="doc-info-item">
                       <strong> Статус:</strong> <span class="active-li w-min-content">Активен</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ПМС №:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">150 обн.</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ДВ:</strong> 
                        <span>
                          <a href="#" class="text-decoration-none">№105</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> В сила от:</strong> 
                        <span>
                          10.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Дата на публикуване:</strong>
                        <span>
                          15.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Вид:</strong>
                        <span class="text-success">Действащ документ</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Версии:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                        </span>
                      </div>
                    </div>            
                </div> 
                <div class="document-wrapper-ks mt-3">
                  <a href="#" class="main-color text-decoration-none fs-18"><i
                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                    <div class="document-info-field d-flex mt-3 pb-3">                         
                      <div class="doc-info-item">
                       <strong> Статус:</strong> <span class="active-li w-min-content">Активен</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ПМС №:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">150 обн.</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ДВ:</strong> 
                        <span>
                          <a href="#" class="text-decoration-none">№105</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> В сила от:</strong> 
                        <span>
                          10.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Дата на публикуване:</strong>
                        <span>
                          15.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Вид:</strong>
                        <span class="text-success">Действащ документ</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Версии:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                        </span>
                      </div>
                    </div>            
                </div> 
            </div>
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Заседания и решения</h3>
                <p>
                    Проведено бе заседание на ВСФ на 10.03.2017г . на което, чрез тайно гласуване бе избран
                    заместник-председател на ВСФ. Обсъдено бе текущото състояние на системата на лекарствоснабдяването и
                    актуалните проблеми през 2017г.
                </p>
                <div class="document-wrapper-ks mt-3">
                  <a href="#" class="main-color text-decoration-none fs-18"><i
                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                    <div class="document-info-field d-flex mt-3 pb-3">                         
                      <div class="doc-info-item">
                       <strong> Статус:</strong> <span class="active-li w-min-content">Активен</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ПМС №:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">150 обн.</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ДВ:</strong> 
                        <span>
                          <a href="#" class="text-decoration-none">№105</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> В сила от:</strong> 
                        <span>
                          10.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Дата на публикуване:</strong>
                        <span>
                          15.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Вид:</strong>
                        <span class="text-success">Действащ документ</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Версии:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                        </span>
                      </div>
                    </div>            
                </div> 
            </div>
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Инфорация за модератора „Консултативен съвет“ </h3>
                <p>Свободен текст</p>
                <div class="document-wrapper-ks mt-3">
                  <a href="#" class="main-color text-decoration-none fs-18"><i
                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                    <div class="document-info-field d-flex mt-3 pb-3">                         
                      <div class="doc-info-item">
                       <strong> Статус:</strong> <span class="closed-li w-min-content">Неактивен</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ПМС №:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">150 обн.</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ДВ:</strong> 
                        <span>
                          <a href="#" class="text-decoration-none">№105</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> В сила от:</strong> 
                        <span>
                          10.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Дата на публикуване:</strong>
                        <span>
                          15.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Вид:</strong>
                        <span class="text-danger">Недействащ документ</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Версии:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
                        </span>
                      </div>
                    </div>            
                </div> 
            </div>
            </div>
        </div>


        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Препратка към Интегрираната информационна система на държавната администрация
                    (ИИСДА), </h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="#" class="main-color text-decoration-none"><i
                                class=" fa-solid fa-link main-color me-2 fs-5"></i>Запис на описание на съвет - линк</a>
                    </li>
                </ul>
            </div>
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Функции</h3>
                <p>Висшият съвет по фармация обсъжда и дава становища по:</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">1. основните насоки и приоритети в областта на фармацията;</li>
                    <li class="list-group-item">2. етични проблеми на фармацията;
                    </li>
                    <li class="list-group-item">3. проекти на нормативни актове свързани с фармацията;
                    </li>
                    <li class="list-group-item">4. научни приоритети в областта на фармацията; </li>
                    <li class="list-group-item">5. програми за организиране на обществени образователни кампании в
                        областта
                        на лекарствените продукти.</li>
                </ul>
            </div>
            </div>
        </div>

        <div class="row mb-4 ks-row">
            <div class="col-md-12">
              <div class="custom-card p-3">
                <h3 class="mb-2 fs-4">Заповеди</h3>
                <div class="document-wrapper-ks mt-3">
                  <a href="#" class="main-color text-decoration-none fs-18"><i
                    class="fa-regular  fa-file-pdf main-color me-2 fs-5"></i>Примерен файл</a>
                    <div class="document-info-field d-flex mt-3 pb-3">                         
                      <div class="doc-info-item">
                       <strong> Статус:</strong> <span class="active-li w-min-content">Активен</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ПМС №:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">150 обн.</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> ДВ:</strong> 
                        <span>
                          <a href="#" class="text-decoration-none">№105</a>
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> В сила от:</strong> 
                        <span>
                          10.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Дата на публикуване:</strong>
                        <span>
                          15.10.2023г.
                        </span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Вид:</strong>
                        <span class="text-success">Действащ документ</span>
                      </div>
                      <div class="doc-info-item">
                        <strong> Версии:</strong>
                        <span>
                          <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
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



@endsection
