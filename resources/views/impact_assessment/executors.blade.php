@extends('layouts.site', ['fullwidth' => true])
@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush

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
                    <form action="{{ url()->current() }}" id="search-form" METHOD="GET">
                        <input type="hidden" name="search" value="true">
                        <input type="hidden" name="page" class="current_page" value="{{ $executors->currentPage() }}">
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
                                           value="{{ request()->offsetGet('contract_date_from') }}">
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
                                <div class="input-group ">
                                    <div class="mb-3 d-flex flex-column w-100">
                                        <div id="sort_by_price" class="block sort_by_price nopadding">
                                            <div class="options_header">
                                                <label for="exampleFormControlInput1" class="form-label">Цена</label>
                                                <div style="margin-top: -6px;margin-bottom: 3px;">
                                                    <label for="amount"></label>
                                                    <input type="text" id="amount" readonly style="border:0;">
                                                </div>
                                                <div id="slider-range"></div>
                                                <input type="hidden" id="price_range_min" name="p_min" value="{{ $p_min ?? $min_price }}">
                                                <input type="hidden" id="price_range_max" name="p_max" value="{{ $p_max ?? $max_price }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5 action-btn-wrapper">
                            <div class="col-md-3 col-sm-12">
                                <span id="searchBtn" class="btn rss-sub main-color search-btn">
                                    <i class="fas fa-search main-color"></i> {{ __('custom.searching') }}
                                </span>
                                <span class="btn rss-sub main-color search-btn clear">
                                    <i class="fas fa-eraser"></i> {{ __('custom.clearing') }}
                                </span>
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

                        <div class="row pt-4 pb-2 px-2">
                            <div class="col-md-12">
                                <input type="hidden" name="sort" class="sort" value="DESC">
                                <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2">
                                    <div class="col-md-2">
                                        <p class="mb-0 cursor-pointer sort_search">
                                            <input type="hidden" disabled class="order_by"  name="order_by" value="contractor_name">
                                            <i class="fa-solid fa-sort"></i> Наименование на възложител
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-0 cursor-pointer sort_search">
                                            <input type="hidden" disabled class="order_by" name="order_by" value="executor_name">
                                            <i class="fa-solid fa-sort"></i> Наименование на изпълнител
                                        </p>
                                    </div>
                                    <div class="col-md-1">
                                        <p class="mb-0 cursor-pointer sort_search ">
                                            <input type="hidden" disabled class="order_by" name="order_by" value="eik">
                                            <i class="fa-solid fa-sort"></i> ЕИК
                                        </p>
                                    </div>
                                    <div class="col-md-1">
                                        <p class="mb-0 cursor-pointer sort_search ">
                                            <input type="hidden" disabled class="order_by" name="order_by" value="contractor_date">
                                            <i class="fa-solid fa-sort"></i> Дата на договора
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-0 cursor-pointer sort_search ">
                                            <input type="hidden" disabled class="order_by" name="order_by" value="contract_subject">
                                            <i class="fa-solid fa-sort"></i> Предмет на договора
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-0 cursor-pointer sort_search ">
                                            <input type="hidden" disabled class="order_by" name="order_by" value="services_description">
                                            <i class="fa-solid fa-sort"></i> Кратко описание на извършените услуги
                                        </p>
                                    </div>
                                    <div class="col-md-1">
                                        <p class="mb-0 cursor-pointer sort_search ">
                                            <input type="hidden" disabled class="order_by" name="order_by" value="price">
                                            <i class="fa-solid fa-sort"></i> Цена на договора (в лв. с ДДС)
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @includeIf('partials.results-select', ['per_page_array' => [5,10,50,100,150,200]])

                    </form>

                    <div id="executors-results">
                        @includeIf('impact_assessment.executors-results')
                    </div>

                </div>
            </div>
        </div>
    </section>
    @includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui-slider.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $("#slider-range").slider({
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [ {{ $p_min ?? $min_price }}, {{ $p_max ?? $max_price }} ],
                slide: function( event, ui ) {
                    $("#amount").val(ui.values[0] + "лв. - " + ui.values[1] + "лв." );
                    $("#price_range_min").val(ui.values[0]);
                    $("#price_range_max").val(ui.values[1]);
                },
                change: function( event, ui ) {
                    //sortList();
                }
            });
            $("#amount").val($("#slider-range").slider("values", 0) + "лв. - " + $("#slider-range").slider("values", 1 ) + "лв." );
        });
    </script>
@endpush