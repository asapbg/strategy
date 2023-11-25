@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">

        @include('site.pris.side_menu')

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
                                        <div class="consul-item">
                                            <div class="consult-item-header d-flex justify-content-between">
                                                <div class="consult-item-header-link">
                                                    <a href="{{ route('pris.view', ['id' => $item->id]) }}" class="text-decoration-none" title="{{ $item->actType->name }} {{ __('custom.number_symbol') }}{{ $item->doc_num }}">
                                                        <h3>{{ $item->actType->name }} {{ __('custom.number_symbol') }}{{ $item->doc_num }} {{ __('custom.of') }} {{ $item->institution->name }} от {{ $item->docYear }} {{ __('site.year_short') }}</h3>
                                                    </a>
                                                </div>
                                                <div class="consult-item-header-edit">
{{--                                                    @can('delete', $item)--}}
                                                        <a href="{{ route('admin.pris.edit', ['item' => $item->id]) }}">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>
                                                        </a>
{{--                                                    @endcan--}}
                                                    @can('update', $item)
                                                        <a href="{{ route('admin.pris.edit', ['item' => $item->id]) }}" target="_blank">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}">
                                                            </i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="meta-consul mb-2 d-inline-block">
                                                <a href="#" title="{{ __('custom.category') }}" class="text-decoration-none mb-3 me-2">
                                                    <i class="fas fa-university fa-link main-color" title="{{ $item->actType->name }}"></i>
                                                    {{ $item->actType->name }}
                                                </a>
                                                <a href="#" title="{{ trans_choice('custom.institutions', 1) }}" class="text-decoration-none mb-3">
                                                    <i class="fas fa-university fa-link main-color" title="{{ $item->institution->name }}"></i>
                                                    {{ $item->institution->name }}
                                                </a>
                                            </div>
                                            <div class="meta-consul">
                                                <div class="anotation text-secondary">
                                                    <span class="main-color me-2">{{ __('site.pris.about') }}:</span> {!! $item->about !!}
                                                </div>
                                            </div>
                                            <div class="meta-consul">
                                                <span class="text-secondary"><i class="far fa-calendar text-secondary"></i> {{ displayDate($item->doc_date) }} {{ __('site.year_short') }}</span>
                                                <a href="{{ route('pris.view', ['id' => $item->id]) }}"><i class="fas fa-arrow-right read-more"></i></a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif


            <div class="row">
                @if(isset($items) && $items->count() > 0)
                    {{ $items->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
