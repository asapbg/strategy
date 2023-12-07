@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')

                <div class="col-lg-10 right-side-content py-5 ">

                    <div class="row">
                        <div class="col-md-8">
                            <p class="fs-18 fw-600 m-0">
                                Списъкът се изготвя в изпълнение на § 1 от Допълнителните разпоредби на Закона за нормативните актове.
                            </p>
                        </div>
                    </div>

                    <hr>
                    <form action="{{ url()->current() }}" METHOD="GET">
                        <div class="row filter-results mb-2">
                            <h2 class="mb-4">
                                Търсене
                            </h2>
                            <div class="col-md-3">
                                <div class="input-group ">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="executor_name" class="form-label">Изпълнител</label>
                                        <input type="text" id="executor_name" name="executor_name" class="form-control"
                                            value="{{ request()->offsetGet('executor_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group ">
                                    <div class="mb-3 d-flex flex-column  w-100">
                                        <label for="contract_subject" class="form-label">Предмет на договора</label>
                                        <input type="text" id="contract_subject" name="contract_subject" class="form-control"
                                               value="{{ request()->offsetGet('contract_subject') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group ">
                                    <div class="mb-3 d-flex flex-column  w-100">
                                        <label for="contractor_name" class="form-label">Възложител</label>
                                        <input type="text" id="contractor_name" name="contractor_name" class="form-control"
                                               value="{{ request()->offsetGet('contractor_name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group ">
                                    <div class="mb-3 d-flex flex-column  w-100">
                                        <label for="services_description" class="form-label">Кратко описание</label>
                                        <input type="text" id="services_description" name="services_description" class="form-control"
                                               value="{{ request()->offsetGet('services_description') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="contract_date_from" class="form-label">Начална дата:</label>
                                <div class="input-group">
                                    <input type="text" name="contract_date_from" autocomplete="off"
                                           id="contract_date_from" class="form-control datepicker"
                                           value="{{ request()->offsetGet('contract_date') }}">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="contract_date_till" class="form-label">Крайна дата:</label>
                                <div class="input-group">
                                    <input type="text" name="contract_date_till" autocomplete="off"
                                           id="contract_date_till" class="form-control datepicker"
                                           value="{{ request()->offsetGet('contract_date_till') }}">
                                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                @includeIf('partials.results-select', ['per_page_array' => [5,10,50,100,150,200]])
                            </div>
                            <div class="col-md-3">
                                <div class="input-group ">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <label for="exampleFormControlInput1" class="form-label">Цена</label>
                                        <span class="small">Над 1500лв.</span>
                                        <input type="range" class="form-range" min="0" max="1000" step="0" id="customRange3">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5 action-btn-wrapper">
                            <div class="col-md-3 col-sm-12">
                                <button class="btn rss-sub main-color" type="submit" id="searchBtn">
                                    <i class="fas fa-search main-color"></i> {{ __('custom.searching') }}
                                </button>
                                <a href="{{ url()->current() }}" class="btn rss-sub main-color">
                                    <i class="fas fa-eraser"></i> {{ __('custom.clearing') }}
                                </a>
                            </div>
                            <div class="col-md-9 text-end col-sm-12">
                                <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS</button>
                                <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                                @canany(['manage.*', 'manage.executors'])
                                    <a href="{{ route('admin.executors.create') }}" class="btn btn-success text-success" target="_blank">
                                        <i class="fas fa-circle-plus text-success me-1"></i>{{ trans_choice('custom.adding', 1) }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </form>

                    <div class="row pt-4 pb-2 px-2">
                        <div class="col-md-12">
                            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2">
                                <div class="col-md-2">
                                    <p class="mb-0 cursor-pointer ">
                                        <i class="fa-solid fa-sort"></i> Наименование на възложител
                                    </p>
                                </div>
                                <div class="col-md-2 cursor-pointer ">
                                    <p class="mb-0">
                                        <i class="fa-solid fa-sort"></i> Наименование на изпълнител
                                    </p>
                                </div>
                                <div class="col-md-1">
                                    <p class="mb-0 cursor-pointer ">
                                        <i class="fa-solid fa-sort"></i> ЕИК
                                    </p>
                                </div>
                                <div class="col-md-1">
                                    <p class="mb-0 cursor-pointer ">
                                        <i class="fa-solid fa-sort"></i> Дата на договора
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-0 cursor-pointer ">
                                        <i class="fa-solid fa-sort"></i> Предмет на договора
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-0 cursor-pointer ">
                                        <i class="fa-solid fa-sort"></i> Кратко описание на извършените услуги
                                    </p>
                                </div>
                                <div class="col-md-1">
                                    <p class="mb-0 cursor-pointer ">
                                        <i class="fa-solid fa-sort"></i> Цена на договора (в лв. с ДДС)
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row justify-content-end my-3">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                            <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой резултати:</label>
                            <select class="form-select w-auto" id="paginationResults">
                                <option value="5">5</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @foreach($executors as $executor)

                                <div class="custom-card pt-1 pb-4 px-3 mb-3">
                                    <div class="row m-0">
                                        <div class="col-md-12 text-end p-0">
                                            @canany(['manage.*', 'manage.executors'])
                                                <a href="javascript:;"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $executor->id }}"
                                                   data-resource-title="{{ $executor->title }}"
                                                   data-resource-delete-url="{{ route('admin.executors.destroy', $executor->id )}}"
                                                   data-toggle="tooltip"
                                                   title="{{ __('custom.deletion') }}">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2" role="button"></i>
                                                </a>
                                                <a href="#" title="Редактиране">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="row single-record">
                                        <div class="col-md-12 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('Name of contractor') }}</p>
                                            <p>
                                                <a href="#" class="main-color text-decoration-none">{{ $executor->contractor_name }}</a>
                                            </p>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('Name of executor') }}</p>
                                            <p>
                                                <a href="#" class="main-color text-decoration-none">{{ $executor->executor_name }}</a>
                                            </p>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('custom.eik') }}</p>
                                            <p>
                                                <a href="#" class="main-color text-decoration-none">{{ $executor->eik }}</a>
                                            </p>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('Contract date') }}</p>
                                            <p>{{ displayDate($executor->contract_date) }}</p>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('custom.price_with_vat') }}</p>
                                            <p>{{ $executor->price }}</p>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('custom.contract_subject') }}</p>
                                            <p>{!! $executor->contract_subject !!}</p>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <p class="fs-18 fw-600 mb-1">{{ __('custom.services_description') }}</p>
                                            <p>{!! $executor->services_description !!}</p>
                                        </div>

                                        <div class="col-md-8">
                                            <p class="mb-0">
                                                <strong>Информация за поръчката:</strong>
                                                <a href="#" class="text-decoration-none" title="ЦАИС">ЦАИС</a>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-0 text-end">
                                                <a href="#" title="ТЕСТ">
                                                    <i class="fas fa-arrow-right read-more text-end"></i><span class="d-none">Линк</span>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="card-footer mt-2">
                                @if(isset($executors) && $executors instanceof Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $executors->appends(request()->query())->links() }}
                                @endif
                            </div>
{{--                            <nav aria-label="Page navigation example">--}}
{{--                                <ul class="pagination m-0">--}}
{{--                                    <li class="page-item">--}}
{{--                                        <a class="page-link" href="#" aria-label="Previous">--}}
{{--                                            <span aria-hidden="true">«</span>--}}
{{--                                            <span class="sr-only">Previous</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">...</a></li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">57</a></li>--}}
{{--                                    <li class="page-item">--}}
{{--                                        <a class="page-link" href="#" aria-label="Next">--}}
{{--                                            <span aria-hidden="true">»</span>--}}
{{--                                            <span class="sr-only">Next</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </nav>--}}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    @includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection
