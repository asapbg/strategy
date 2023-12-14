@extends('layouts.site', ['fullwidth' => true])


@section('pageTitle', 'Профил на Потребител')

@section('content')
<div class="row mt-5">

 {слайдер} <br> 
 {пътечки}
</div>
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Профил на {Потребител}
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                              <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Обща информация</a>
                              </li>
                                <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Абонаменти</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Законодателни инициативи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Оценки на въздействието</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Публикувани коментари</a>
                                </li>          
                            </ul>
                        </div>
                    </li>
                    <hr class="custom-hr">
                </ul>
            </div>
        </div>

    </div>


    <div class="col-lg-10 right-side-content py-5 ">
        <div class="col-md-12">
            <h2 class="mb-4">Управление на абонаменти</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="custom-card p-3 mb-4">
                  <div class="row subscribe-row pb-2 align-items-center">
                      <div class="col-md-9">
                          <h3 class="fs-4 mb-0 fw-normal">
                              Обществени консултации
                          </h3>
                      </div>
                      <div class="col-md-3 subscribe-action text-end">
                          <button type="button" class="btn btn-labeled  bgr-main rounded sm-btn-sm">
                              <i class="fa-solid fa-pen-to-square text-light"></i>
                          </button>
              
                          <button type="button" class="btn btn-labeled btn-warning">
                              <i class="fa-solid fa-ban text-light"></i>
                          </button>
              
                          <button type="button" class="btn btn-labeled danger-bgr">
                              <i class="fa fa-trash text-light"></i>
                          </button>
                      </div>
              
                      <div class="inner-row-subscribe mt-2">
                          <ul class="list-group subscribe-items">
                              <li class="list-group-item">
                                  <a href="#" class="text-decoration-none">Проект на Правилник за изменение и допълнение на
                                      Устройствения правилник на Центъра за развитие на човешките ресурси и регионални инициативи</a>
                                  <div class="subscribe-action text-end">
                                      <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                          <i class="fa-solid fa-pen-to-square text-light"></i>
                                      </button>
              
                                      <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                          <i class="fa-solid fa-ban text-light"></i>
                                      </button>
              
                                      <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                          <i class="fa fa-trash text-light"></i>
                                      </button>
                                  </div>
                              </li>
              
                              <li class="list-group-item">
                                  <a href="#" class="text-decoration-none">Проект на Закон за изменение и допълнение на Закона за българите, живеещи извън Република България
                                  </a>
                                  <div class="subscribe-action text-end">
                                      <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                          <i class="fa-solid fa-pen-to-square text-light"></i>
                                      </button>
              
                                      <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                          <i class="fa-solid fa-ban text-light"></i>
                                      </button>
              
                                      <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                          <i class="fa fa-trash text-light"></i>
                                      </button>
                                  </div>
                              </li>
              
                              <li class="list-group-item">
                                  <a href="#" class="text-decoration-none">Консултационен документ относно проект на нов Закон за
                                      виното и спиртните напитки</a>
                                  <div class="subscribe-action text-end">
                                      <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                          <i class="fa-solid fa-pen-to-square text-light"></i>
                                      </button>
              
                                      <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                          <i class="fa-solid fa-ban text-light"></i>
                                      </button>
              
                                      <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                          <i class="fa fa-trash text-light"></i>
                                      </button>
                                  </div>
                          </li></ul>
                      </div>
                  </div>
              </div>
            
              <div class="custom-card p-3 mb-4">
                <div class="row subscribe-row pb-2 align-items-center">
                    <div class="col-md-9">
                        <h3 class="fs-4 mb-0 fw-normal">
                           Стратегически документи
                        </h3>
                    </div>
                    <div class="col-md-3 subscribe-action text-end">
                        <button type="button" class="btn btn-labeled  bgr-main rounded sm-btn-sm">
                            <i class="fa-solid fa-pen-to-square text-light"></i>
                        </button>
            
                        <button type="button" class="btn btn-labeled btn-warning">
                            <i class="fa-solid fa-ban text-light"></i>
                        </button>
            
                        <button type="button" class="btn btn-labeled danger-bgr">
                            <i class="fa fa-trash text-light"></i>
                        </button>
                    </div>
            
                    <div class="inner-row-subscribe mt-2">
                        <ul class="list-group subscribe-items">
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Стратегически документи с филтър:<span class="fst-italic dark-text">(<span class="str-doc-type">Тема:</span> Култура) , (<span class="str-doc-type">Изработила институция:</span> Всички)</span></a>
                                <div class="subscribe-action text-end">
                                    <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                        <i class="fa-solid fa-pen-to-square text-light"></i>
                                    </button>
            
                                    <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                        <i class="fa-solid fa-ban text-light"></i>
                                    </button>
            
                                    <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                        <i class="fa fa-trash text-light"></i>
                                    </button>
                                </div>
                            </li>
            
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Национален план за действие за борба с антисемитизма (2023 - 2027 г.)</a>
                                <div class="subscribe-action text-end">
                                    <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                        <i class="fa-solid fa-pen-to-square text-light"></i>
                                    </button>
            
                                    <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                        <i class="fa-solid fa-ban text-light"></i>
                                    </button>
            
                                    <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                        <i class="fa fa-trash text-light"></i>
                                    </button>
                                </div>
                            </li>
            
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Програми за разширяване и подобряване на сградния фонд и материалната база в системата на образованието за периода 2024 - 2026 г.</a>
                                <div class="subscribe-action text-end">
                                    <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                        <i class="fa-solid fa-pen-to-square text-light"></i>
                                    </button>
            
                                    <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                        <i class="fa-solid fa-ban text-light"></i>
                                    </button>
            
                                    <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                        <i class="fa fa-trash text-light"></i>
                                    </button>
                                </div>
                        </li></ul>
                    </div>
                </div>
            </div>
            
            <div class="custom-card p-3 mb-4">
              <div class="row subscribe-row pb-2 align-items-center">
                  <div class="col-md-9">
                      <h3 class="fs-4 mb-0 fw-normal">
                        Партньорство за открито управление
                      </h3>
                  </div>
                  <div class="col-md-3 subscribe-action text-end">
                      <button type="button" class="btn btn-labeled  bgr-main rounded sm-btn-sm">
                          <i class="fa-solid fa-pen-to-square text-light"></i>
                      </button>
            
                      <button type="button" class="btn btn-labeled btn-warning">
                          <i class="fa-solid fa-ban text-light"></i>
                      </button>
            
                      <button type="button" class="btn btn-labeled danger-bgr">
                          <i class="fa fa-trash text-light"></i>
                      </button>
                  </div>
            
                  <div class="inner-row-subscribe mt-2">
                      <ul class="list-group subscribe-items">
                          <li class="list-group-item">
                              <a href="#" class="text-decoration-none">Национални планове за действие</a>
                              <div class="subscribe-action text-end">
                                  <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                      <i class="fa-solid fa-pen-to-square text-light"></i>
                                  </button>
            
                                  <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                      <i class="fa-solid fa-ban text-light"></i>
                                  </button>
            
                                  <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                      <i class="fa fa-trash text-light"></i>
                                  </button>
                              </div>
                          </li>
            
                          <li class="list-group-item">
                              <a href="#" class="text-decoration-none">Оценка за изпълнението на плановете за действие - мониторинг</a>
                              <div class="subscribe-action text-end">
                                  <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                      <i class="fa-solid fa-pen-to-square text-light"></i>
                                  </button>
            
                                  <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                      <i class="fa-solid fa-ban text-light"></i>
                                  </button>
            
                                  <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                      <i class="fa fa-trash text-light"></i>
                                  </button>
                              </div>
                          </li>
            
                          <li class="list-group-item">
                              <a href="#" class="text-decoration-none">Разработване на нов план за действие</a>
                              <div class="subscribe-action text-end">
                                  <button type="button" class="btn btn-labeled btn-sm  bgr-main rounded">
                                      <i class="fa-solid fa-pen-to-square text-light"></i>
                                  </button>
            
                                  <button type="button" class="btn btn-labeled btn-sm btn-warning">
                                      <i class="fa-solid fa-ban text-light"></i>
                                  </button>
            
                                  <button type="button" class="btn btn-labeled btn-sm danger-bgr">
                                      <i class="fa fa-trash text-light"></i>
                                  </button>
                              </div>
                      </li></ul>
                  </div>
              </div>
            </div>
              </div>
        </div>
    </div>

</div>
</body>


@endsection
