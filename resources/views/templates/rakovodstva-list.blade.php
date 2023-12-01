@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
<div class="row">
    <div class="col-lg-12 py-5">
      <div class="row filter-results mb-2">
          <h2 class="mb-4">
              Търсене
          </h2>
          <div class="col-md-3">
            <div class="input-group ">
                <div class="mb-3 d-flex flex-column  w-100">
                    <label for="title" class="form-label">Заглавие</label>
                    <input type="text" class="form-control" id="title">
                </div>
            </div>
        </div>
          <div class="col-md-3">
              <div class="input-group ">
                  <div class="mb-3 d-flex flex-column  w-100">
                      <label for="keywords" class="form-label">Ключови думи</label>
                      <input type="text" class="form-control" id="keywords">
                  </div>
              </div>
          </div>
          <div class="col-md-3">
            <div class="col-md-12">
              <label for="exampleFormControlInput1" class="form-label">Дата</label>
              <div class="input-group">
                  <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker">
                  <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
              </div>
          </div>
          </div>

          <div class="col-md-3">
              <div class="input-group ">
                  <div class="mb-3 d-flex flex-column  w-100">
                      <label for="exampleFormControlInput1" class="form-label">Брой резултати:</label>
                      <select class="form-select">
                          <option value="1">10</option>
                          <option value="1">20</option>
                          <option value="1">30</option>
                          <option value="1">40</option>
                          <option value="1">50</option>
                      </select>
                  </div>
              </div>
          </div>
      </div>
      <div class="row mb-5">
          <div class="col-md-6">
              <button class="btn rss-sub main-color"><i class="fas fa-search main-color me-1"></i>Търсене</button>
          </div>
          <div class="col-md-6 text-end">
              <button class="btn rss-sub main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS</button>
              <button class="btn rss-sub main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
              <button class="btn btn-success text-success"><i class="fas fa-circle-plus text-success me-1"></i>Добави ръкодоство</button>
          </div>
      </div>
      <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
          <div class="col-md-4">
              <p class="mb-0 cursor-pointer ">
                  <i class="fa-solid fa-sort me-2"></i> Заглавие
              </p>
          </div>
          <div class="col-md-4 cursor-pointer ">
              <p class="mb-0">
                  <i class="fa-solid fa-sort me-2"></i>Ключова дума
              </p>
          </div>

          <div class="col-md-4">
              <p class="mb-0 cursor-pointer">
                  <i class="fa-solid fa-sort me-2"></i>Дата
              </p>
          </div>
      </div>
      <div class="row mb-4">
          <div class="col-12 mt-2">
              <div class="info-consul text-start">
                  <p class="fw-600">
                      Общо 98 резултата
                  </p>
              </div>
          </div>
      </div>
      <div class="row mb-4">
          <div class="col-md-12">
              <div class="consul-wrapper">
                  <div class="single-consultation d-flex">
                      <div class="consult-img-holder">
                          <i class="bi bi-file-earmark-text dark-blue"></i>
                      </div>
                      <div class="consult-body">
                          <div href="#" class="consul-item">
                              <div class="consult-item-header d-flex justify-content-between">
                                  <div class="consult-item-header-link">
                                      <a href="#" class="text-decoration-none" title="Промяна в нормативната уредба на търговията на дребно с лекарствени продукти">
                                          <h3>Ръководство за извършване на предварителна оценка на въздействието</h3>
                                      </a>
                                  </div>
                                  <div class="consult-item-header-edit">
                                      <a href="#">
                                          <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                      </a>
                                      <a href="#">
                                          <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                          </i>
                                      </a>
                                  </div>
                              </div>
                              <div class="meta-consul mt-2">
                                  <span class="text-secondary">
                                      <i class="far fa-calendar text-secondary me-1"></i> 12.09.2023 г
                                  </span>

                                  <a href="#" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                      <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
                                  </a>
                              </div>
                          </div>

                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="row mb-4">
          <div class="col-md-12">
              <div class="consul-wrapper">
                  <div class="single-consultation d-flex">
                      <div class="consult-img-holder">
                        <i class="bi bi-file-earmark-text dark-blue"></i>
                      </div>
                      <div class="consult-body">
                          <div href="#" class="consul-item">
                              <div class="consult-item-header d-flex justify-content-between">
                                  <div class="consult-item-header-link">
                                      <a href="#" class="text-decoration-none" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                          <h3>Ръководство за извършване на последваща оценка на въздействието</h3>
                                      </a>
                                  </div>
                                  <div class="consult-item-header-edit">
                                      <a href="#">
                                          <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                      </a>
                                      <a href="#">
                                          <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция">
                                          </i>
                                      </a>
                                  </div>
                              </div>

                              <div class="meta-consul mt-2">
                                  <span class="text-secondary">
                                      <i class="far fa-calendar text-secondary me-1"></i> 04.07.2023 г
                                  </span>

                                  <a href="#" title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                      <i class="fas fa-arrow-right read-more"><span class="d-none">Линк</span></i>
                                  </a>
                              </div>
                          </div>

                      </div>
                  </div>
              </div>
          </div>
      </div>





      <div class="row">
          <nav aria-label="Page navigation example">
              <ul class="pagination m-0">
                  <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous">
                          <span aria-hidden="true">«</span>
                          <span class="sr-only">Previous</span>
                      </a>
                  </li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">...</a></li>
                  <li class="page-item"><a class="page-link" href="#">57</a></li>
                  <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next">
                          <span aria-hidden="true">»</span>
                          <span class="sr-only">Next</span>
                      </a>
                  </li>
              </ul>
          </nav>
      </div>

  </div>

</div>
</body>


@endsection
