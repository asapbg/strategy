@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2"><a href="#"
                                        class="link-dark text-decoration-none">Законодателни инициативи</a>
                                </li>
                                <hr class="custom-hr">
                                <li class="mb-2 mt-1"><a href="#" class="link-dark text-decoration-none">Отворено
                                        управление</a>
                                </li>
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                    <ul class="list-unstyled ps-3">
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Планове
                                            </a></li>
                                        <hr class="custom-hr">
                                        <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Отчети</a>
                                        </li>
                                        <hr class="custom-hr">
                                    </ul>
                                </ul>

                                <li class="mb-2 active-item-left p-1">
                                    <a href="#" class="link-dark text-decoration-none">Анкети</a>
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
        <h2 class="obj-title mb-4">
            Финанси и данъчна политика
        </h2>
        <div class="row">
            <div class="col-md-8">
                <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>12.7.2023 г.</span>
                </a>
                <a href="#" class="text-decoration-none">
                    <span class="obj-icon-info me-2">
                        <span>Статус: <span class="active-li">Активна</span></span>
                    </span>
        
                </a>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на анкета
                </button>
                <button class="btn btn-sm btn-danger">
                    <i class="fas fa-regular fa-trash-can me-2 text-danger"></i>Изтриване на анкета
                </button>
            </div>
        </div>
        <hr class="custom-hr my-4">
        <div class="row mb-0 mt-4">
            <div class="col-md-12">
              <div class="custom-card py-4 px-3">
                <h3 class="mb-3">Анкети</h3>
                <form class="row" action="">


                  <div class="col-md-6 mb-4">
                    <div class="comment-background p-2 rounded">
                      <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                          Отговор 1
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                        <label class="form-check-label" for="flexCheckDefault2">
                          Отговор 2
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 3
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 4
                        </label>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="comment-background p-2 rounded">
                      <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                          Отговор 1
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                        <label class="form-check-label" for="flexCheckDefault2">
                          Отговор 2
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 3
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 4
                        </label>
                      </div>

                    </div>

                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="comment-background p-2 rounded">
                      <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                          Отговор 1
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                        <label class="form-check-label" for="flexCheckDefault2">
                          Отговор 2
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 3
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 4
                        </label>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="comment-background p-2 rounded">
                      <p class="fw-bold fs-18 mb-2">Примерен въпрос?</p>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                          Отговор 1
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                        <label class="form-check-label" for="flexCheckDefault2">
                          Отговор 2
                        </label>
                      </div>


                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 3
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">
                          Отговор 4
                        </label>
                      </div>
                    </div>

                  </div>




                  <div class="col-md-12">
                    <button class="btn btn-primary">
                      Изпращане
                    </button>
                  </div>
                </form>
              </div>
            </div>



          </div>
    </div>
</div>
</body>


@endsection
