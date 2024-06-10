@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<div class="row">
    <div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
        <div class="left-nav-panel" style="background: #fff !important;">
            <div class="flex-shrink-0 p-2">
                <ul class="list-unstyled">
                    <li class="mb-1">
                        <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                            <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                        </a>
                        <hr class="custom-hr">
                        <div class="collapse show mt-3" id="home-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                <li class="mb-2  active-item-left p-1"><a href="#"
                                        class="link-dark text-decoration-none">Законодателни инициативи</a>
                                </li>
                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
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

                                <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Анкети</a>
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
      <div class="row">
        <div class="col-lg-10">
          <h2 class="obj-title mb-4">Участие на гражданите в обсъжданията на законопроектите на Народното събрание.</h2>
        </div>

        <div class="col-lg-2">
          <div class="col-md-12 d-flex col-md-12 d-flex justify-content-end">
            <a href="#" class="text-decoration-none d-flex align-items-center">
                <i class="fa fa-regular fa-thumbs-up main-color" style="font-size:34px;"></i>
            </a>
            <a href="#" class="text-decoration-none support-count-li d-flex align-items-center ms-3">
                235
            </a>
        </div>
        </div>
      </div>


        <div class="row">
            <div class="col-md-8">
                <a href="#" class="text-decoration-none"><span class="obj-icon-info me-2"><i class="far fa-calendar me-1 dark-blue" title="Дата на публикуване"></i>04.07.2023 г.</span>
                </a>
                <a href="#" class="text-decoration-none">
                    <span class="obj-icon-info me-2"><i class="fas fa-sitemap me-1 dark-blue" title="Област на политика"></i>Партньорство за открито управление</span>
                </a>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary main-color">
                    <i class="fas fa-pen me-2 main-color"></i>Редактиране на инициатива
                </button>
            </div>
        </div>
        <hr class="custom-hr my-4">
        <div class="row">
            <div class="edit-li">
                <p class="mb-4">
                    УВАЖАЕМИ КОЛЕГИ, УВАЖАЕМИ ДАМИ И ГОСПОДА, Предлагам за обсъждане Участието на гражданите в обсъжданията на законопроектите на Народното събрание. Така по този начин може да се гарантира истинско гражданско участие във вземането на реални решения за България. Възможности има нека чуем мнението на някой хора.
                </p>
                <hr class="custom-hr">
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-12">
              <div class="custom-card py-4 px-3">
                <h3 class="mb-3">Коментари</h3>
                <div class="obj-comment comment-background p-2 rounded mb-3">
                  <div class="info">
                    <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                      <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Георги Георгиев</span>
                    <span class="obj-icon-info me-2 text-muted">12.09.2023 19:05</span>
                    <a href="#">
                      <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                     </a>
                  </div>
                  <div class="comment rounded py-2">
                   <p class="mb-2">Имате ли предложения за мерки, които ще допринесат за постигане целите на инициативата? (за черпене на идеи за формулиране на предложенията може да се използва Opening government: A guide to best practice in transparency, accountability and civic engagement across the public sector)</p>
                   <div class="mb-0">
                    <a href="#" class="me-2 text-decoration-none">10<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                    <a href="#" class="text-decoration-none">1<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                </div>
                </div>
                </div>
                <div class="obj-comment comment-background p-2 rounded mb-3">
                  <div class="info">
                    <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                      <i class="fa fa-solid fa-circle-user me-2 main-color" title="Автор"></i>Петър Петров</span>
                    <span class="obj-icon-info me-2 text-muted">13.09.2023 19:05</span>
                    <a href="#">
                      <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                  </a>
                  </div>
                  <div class="comment rounded py-2">
                    <p class="mb-2">До всички заинтересовани На портала strategy.bg е публикуван доклада по независимия механизъм за оценка България във връзка с инициативата "Партньорство за открито управление". Този доклад е основа и препоръка към България в подготовка на Втория план за действие по инициативата 2014-2016. Всички заинтересовани имат възможност да коментират тук или директно да изпращат своите предложения и препоръки до 10.04.2014 г. на vpopova@mrrb.government.bg. Лице за контакт: Владима Попова, дирекция „Европейска координация и международно сътрудничество“, Министерство на регионалното развитие, тел. 940 5 447.
                    <div class="mb-0">
                        <a href="#" class="me-2 text-decoration-none">3<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                        <a href="#" class="text-decoration-none">1<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                    </div>

                  </div>
                </div>
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
                      <p class="mb-2">Митнически агент. Необходим ли ни е такъв? Агенция "Митници" стартира анкета дали е необходимо да съществува фигурата "митнически агент". Моля гласувайте http://customs.bg/bg/poll/10/
                      </p>
                      <div class="mb-0">
                        <a href="#" class="me-2 text-decoration-none">4<i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i></a>
                        <a href="#" class="text-decoration-none">5<i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i></a>
                    </div>
                    </div>
                  </div>
                <div class="col-md-12 mt-4">
                  <div>
                    <textarea class="form-control mb-3 rounded" id="exampleFormControlTextarea1" rows="2" placeholder="Въведете коментар"></textarea>
                    <button class=" cstm-btn btn btn-primary login m-0">Добавяне на коментар</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
</div>
</body>


@endsection
