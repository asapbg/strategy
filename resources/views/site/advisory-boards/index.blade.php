@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', trans_choice('custom.advisory_boards', 2))

@section('content')
    <div class="row">
        <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
            <div class="left-nav-panel" style="background: #fff !important;">
                <div class="flex-shrink-0 p-2">
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                               data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                                <i class="fa-solid fa-bars me-2 mb-2"></i>{{ trans_choice('custom.advisory_boards', 2) }}
                            </a>

                            <hr class="custom-hr">

                            <div class="collapse show mt-3" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                                    <li class="mb-2">
                                        <a href="#" class="link-dark text-decoration-none">
                                            {{ trans_choice('custom.contacts', 2) }}
                                        </a>
                                    </li>

                                    <li class="mb-2">
                                        <a href="#" class="link-dark text-decoration-none">
                                            {{ __('custom.up_to_date_information') }}
                                        </a>
                                    </li>

                                    <li class="mb-2">
                                        <a href="#" class="link-dark text-decoration-none">
                                            {{ trans_choice('custom.news', 2) }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <hr class="custom-hr">
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-10 right-side-content py-5">
            @if(isset($pageTopContent) && !empty($pageTopContent->value))
                <div class="col-12 mb-5">
                    {!! $pageTopContent->value !!}
                </div>
            @endif

            <form action="{{ url()->current() }}" id="search-form" METHOD="GET">
                <input type="hidden" name="search" value="true">
                <input type="hidden" name="page" class="current_page" value="{{ $advisory_boards->currentPage() }}">
                <input type="hidden" name="sort" class="sort" value="DESC">

                <div class="row filter-results mb-2">
                    <h2 class="mb-4">
                        {{ __('custom.searching') }}
                    </h2>

                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="filter_field_of_action"
                                       class="form-label">{{ trans_choice('custom.field_of_actions', 1) }}</label>
                                <select class="form-select select2" multiple name="filter_field_of_action"
                                        id="filter_field_of_action">
                                    <option value="">{{ __('custom.all') }}</option>

                                    @foreach($field_of_actions as $field)
                                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="filter_authority"
                                       class="form-label">{{ __('custom.type_of_governing') }}</label>
                                <select class="form-select select2" multiple name="filter_authority"
                                        id="filter_authority">
                                    <option value="">{{ __('custom.all') }}</option>

                                    @foreach($authority_advisory_boards as $authority)
                                        <option value="{{ $authority->id }}">{{ $authority->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="filter_act_of_creation"
                                       class="form-label">{{ __('validation.attributes.act_of_creation') }}</label>
                                <select class="form-select select2" multiple name="filter_act_of_creation"
                                        id="filter_act_of_creation">
                                    <option value="">{{ __('custom.all') }}</option>

                                    @foreach($advisory_act_types as $act)
                                        <option value="{{ $act->id }}">{{ $act->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="filter_chairman_type"
                                       class="form-label">{{ trans_choice('custom.advisory_chairman_type', 1) }}</label>
                                <select class="form-select select2" multiple name="filter_chairman_type"
                                        id="filter_chairman_type">
                                    <option value="">{{ __('custom.all') }}</option>

                                    @foreach($advisory_chairman_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1"
                                       class="form-label">{{ __('custom.presence_npo_representative') }}</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="">{{ __('custom.all') }}</option>
                                    <option value="1">{{ __('custom.yes') }}</option>
                                    <option value="0">{{ __('custom.no') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="exampleFormControlInput1"
                                       class="form-label">{{ __('custom.status') }}</label>
                                <select class="form-select select2" multiple aria-label="Default select example">
                                    <option value="">{{ __('custom.all') }}</option>
                                    <option value="1" selected>{{ __('custom.active') }}</option>
                                    <option value="2">{{ __('custom.inactive') }}</option>
                                </select>
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
                    <div class="col-md-8 text-end">
                        <button class="btn btn-primary main-color">
                            <i class="fas fa-square-rss text-warning me-1"></i>
                            {{ __('custom.rss_subscribe') }}
                        </button>
                        <button class="btn btn-primary main-color"><i
                                class="fas fa-envelope me-1"></i>{{ __('custom.subscribe') }}</button>
                        @can('create', \App\Models\AdvisoryBoard::class)
                            <button class="btn btn-success text-success"><i
                                    class="fas fa-circle-plus text-success me-1"></i>{{ __('custom.adding') }}
                            </button>
                        @endcan
                    </div>
                </div>

                <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer d-flex align-items-center">
                            <i class="fa-solid fa-sort me-2"></i>
                            <select class="form-select">
                                <option value="1">Област на политика</option>
                                <option value="1">Финанси и данъчна политика</option>
                                <option value="1">Партньорство за открито управление</option>
                                <option value="1">Енергетика</option>
                                <option value="1">Защита на потребителите</option>
                            </select>
                        </p>
                    </div>
                    <div class="col-md-2 cursor-pointer ">
                        <p class="mb-0 cursor-pointer d-flex align-items-center">
                            <i class="fa-solid fa-sort me-2"></i>
                            <select class="form-select">
                                <option value="1">Вид орган</option>
                                <option value="1">Министерски съвет</option>
                                <option value="1">Министър-председател</option>
                                <option value="1">Министър</option>
                                <option value="1">Държавна агенция</option>
                                <option value="1">Друг централен орган</option>
                            </select>
                        </p>
                    </div>


                    <div class="col-md-2">
                        <p class="mb-0 cursor-pointer d-flex align-items-center">
                            <i class="fa-solid fa-sort me-2"></i>
                            <select class="form-select">
                                <option value="1">Акт на създаване</option>
                                <option value="2">Закон</option>
                                <option value="3">Постановление на Министерския съвет (на основание чл. 21 от Закона за
                                    администрацията)
                                </option>
                                <option value="4">Заповед на председател на държавна агенция (на основание, чл. 47, ал.
                                    8 от Закона за администрацията)
                                </option>
                                <option value="5">Акт на друг централен орган</option>
                            </select>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-0 cursor-pointer d-flex align-items-center">
                            <i class="fa-solid fa-sort me-2"></i>
                            <select class="form-select">
                                <option value="1">Председател на съвета</option>
                                <option value="2">Министър-председател</option>
                                <option value="3">Заместник министър-председател</option>
                                <option value="4">Министър</option>
                                <option value="5">Председател на държавна агенция</option>
                                <option value="6">Друго свободно добавяне в номенклатурата</option>
                            </select>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p class="mb-0 cursor-pointer d-flex align-items-center">
                            <i class="fa-solid fa-sort me-2"></i>
                            <select class="form-select">
                                <option value="1">Представител</option>
                                <option value="1">Представител/и на национално представителните организации на
                                    работодателите
                                </option>
                                <option value="2">Представител/и на национално представителните организации на
                                    работниците и
                                    служителите;
                                </option>
                                <option value="3">Представител/и на академичната общност</option>
                                <option value="4">Представител/и на местното самоуправление/на Националното сдружение на
                                    общините в
                                    Република България
                                </option>
                                <option value="5">Представител/и на бизнеса</option>
                                <option value="6">Представител/и на държавни органи</option>
                                <option value="6">Други</option>
                            </select>
                        </p>

                    </div>
                </div>
                <div class="row justify-content-end my-3">
                    <div class="col-md-4">
                        <!-- Бутонът експорт да е видим само след зареждане на резултати
                    <button class="btn btn-primary main-color"><i
                      class="fa-solid fa-download main-color me-1"></i>Експорт</button>
                       -->
                    </div>
                    <div
                        class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
                        <label for="paginate"
                               class="form-label fw-bold mb-0 me-3">{{ __('custom.count') . ' ' . trans_choice('custom.results', 2) . ':' }}</label>
                        <select class="form-select w-auto" name="paginate" id="paginate">
                            <option value="1">10</option>
                            <option value="1">20</option>
                            <option value="1">30</option>
                            <option value="1">40</option>
                            <option value="1">50</option>
                            <option value="1">100</option>
                        </select>
                    </div>
                </div>
            </form>

            <div id="executors-results">
                @includeIf('site.advisory-boards.ajax-results')
            </div>
        </div>
    </div>
@endsection
