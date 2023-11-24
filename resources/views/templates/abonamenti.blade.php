@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Абонаменти')

@section('content')
<div class="row filter-results mb-2 pt-5">
    <h2 class="mb-4">
        Управление на абонаменти
    </h2>
</div>
<div class="row pb-5">
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
              </ul>
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
                    <a href="#" class="text-decoration-none">Стратегически документи с филтър:<span class="fst-italic dark-text">&#40;<span class="str-doc-type">Тема:</span> Култура&#41; , &#40;<span class="str-doc-type">Изработила институция:</span> Всички&#41;</span></a>
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
            </ul>
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
          </ul>
      </div>
  </div>
</div>
  </div>
</div>
@endsection
