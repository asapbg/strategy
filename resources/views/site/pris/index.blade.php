@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        <div class="col-lg-2 side-menu pt-5 mt-1" style="background:#f5f9fd;">
            <div class="left-nav-panel" style="background: #fff !important;">
                <div class="flex-shrink-0 p-2" >
                    <ul class="list-unstyled">
                        <div class="mb-1">
                            <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                <i class="fa-solid fa-bars me-2 mb-2"></i>Начало
                            </a>
                            <hr class="custom-hr">
                            <div class="collapse show mt-3" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                    <li class="mb-2  p-1"><a href="#" class="link-dark text-decoration-none">Планиране</a></li>
                                    <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                        <ul class="list-unstyled ps-3">
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Законодателна програма</a></li>
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Оперативна програма</a></li>
                                            <hr class="custom-hr">
                                        </ul>
                                    </ul>

                                    <li class="mb-2 active-item-left p-1"><a href="#" class="link-dark text-decoration-none">Актове на МС</a></li>
                                    <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">
                                        <ul class="list-unstyled ps-3">
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Постановления</a></li>
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Решения</a></li>
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Становища</a></li>
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Протоколи</a></li>
                                            <hr class="custom-hr">
                                        </ul>
                                    </ul>
                                </ul>
                                <li class="mb-2"><a href="#" class="link-dark  text-decoration-none">Архив</a></li>
                            </div>
                        </div>
                    </ul>
                </div>
                <hr class="custom-hr">
            </div>
        </div>


        <div class="col-lg-10  home-results home-results-two pris-list mt-5 mb-5" >
            <div class="row filter-results mb-2">
                <h2 class="mb-4">
                    Търсене
                </h2>
                <div class="col-md-12">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Категория:</label>
                            <select class="form-select select2" multiple aria-label="Default select example">
                                <option value="1">Всички</option>
                                <option value="1">Постановления</option>
                                <option value="1">Разпореждания</option>
                                <option value="1">Решения</option>
                                <option value="1">Стенограми</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Съдържание:</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Заглавие:</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Основание:</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Термини:</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Вносител:</label>
                            <select class="form-select select2" multiple aria-label="Default select example">
                                <option value="1">Институция 1</option>
                                <option value="1">Институция 2</option>
                                <option value="1">Институция 3</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="exampleFormControlInput1" class="form-label">Начална дата:</label>
                    <div class="input-group">
                        <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker" >
                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="exampleFormControlInput1" class="form-label">Крайна дата:</label>
                    <div class="input-group">
                        <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker" >
                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Номер:</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Държавен вестник (брой):</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Държавен вестник (година):</label>
                            <div class="input-group">
                                <input type="text" name="fromDate" autocomplete="off" readonly="" value="" class="form-control datepicker" >
                                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <div class="mb-3 d-flex flex-column  w-100">
                            <label for="exampleFormControlInput1" class="form-label">Промени:</label>
                            <input type="text" class="form-control">
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
                    <button class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn rss-sub main-color"><i class="fas fa-square-rss text-warning"></i>RSS</button>
                    <button class="btn rss-sub main-color"><i class="fas fa-envelope"></i>Абониране</button>
                </div>
            </div>

            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i> Категория
                    </p>
                </div>
                <div class="col-md-2 cursor-pointer ">
                    <p class="mb-0">
                        <i class="fa-solid fa-sort me-2"></i>Вносител
                    </p>
                </div>


                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer">
                        <i class="fa-solid fa-sort me-2"></i>Дата
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i>Номер
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i>Заглавие
                    </p>
                </div>
                <div class="col-md-2">
                    <p class="mb-0 cursor-pointer ">
                        <i class="fa-solid fa-sort me-2"></i>Основание
                    </p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 mt-2">
                    <div class="info-consul text-start">
                        <p class="fw-600">
                            Общо {{ $items->count() }} резултата
                        </p>
                    </div>
                </div>
            </div>

            @if($items->count())
                @foreach($items as $item)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-body">
                                        <a href="{{ route('pris.view', ['id' => $item->id]) }}" class="consul-item">
                                            <p>
                                                <i class="me-1 main-color fw-bold fst-normal" title="{{ __('custom.number') }}">
                                                    {{ $item->actType->name }} {{ __('custom.number_symbol') }}{{ $item->actType->doc_num }}
                                                </i>
                                                {{ __('custom.of') }} {{ $item->institution->name }} от {{ $item->docYear }} {{ __('site.year_short') }}

                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>
                                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}"></i>
                                            </p>

                                            <p>
                                                <i class="fas fa-sitemap me-2 main-color" title="Категория"><span class="fw-normal main-color"> {{ $item->actType->name }}</span></i>
                                                <i class="fas fa-university fa-link main-color" title="Протокол"></i><span class="fw-normal main-color"> {{ $item->institution->name }}</span>
                                            </p>
                                            <div class="anotation text-secondary mb-2">
                                                <span class="main-color me-2">{{ __('site.pris.about') }}:</span> {!! $item->about !!}
                                            </div>
                                            <div class="meta-consul">
                                                <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> {{ displayDate($item->doc_date) }} {{ __('site.year_short') }}</span>
                                                <i class="fas fa-arrow-right read-more"></i>
                                            </div>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

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
@endsection
