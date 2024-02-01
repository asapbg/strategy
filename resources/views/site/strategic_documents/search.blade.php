<div class="row filter-results mb-2">
<div class="col-md-6"></div>

    <div class="col-md-6 text-end">
        <!--
        И в двата вида визуализация (табличен и дървовиден),
        нека най-отгоре да има линк към основната справка
         „Справка с всички документи“, която да сваля PDF
         с дървото и всички прилежащи файлове за всеки документ
         като линкове, за които е дадено „видими в справката“.
         Това е подобно на сегашната функционалност:
        -->
        <div class="dropdown d-inline">
            <button class="btn btn-primary main-color dropdown-toggle mt-2 mb-3" type="button" data-toggle="dropdown"
                aria-expanded="false">
                <i class="fa-solid fa-download main-color me-2"></i>
                {{ __('custom.export') }}
            </button>

            <ul class="dropdown-menu">
                <li><a id="pdf_export" class="dropdown-item" href="#">{{ __('custom.export_as_pdf') }}</a></li>
                <li><a id="excel_export" class="dropdown-item" href="#">{{ __('custom.export_as_excel') }}</a></li>
                <li><a id="csv_export" class="dropdown-item" href="#">{{ __('custom.export_as_csv') }}</a></li>
            </ul>
        </div>
        <button id="documents_report" class="btn btn-primary main-color dropdown-toggle mt-2 mb-3" type="button"
            data-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-download main-color me-2"></i>
            {{ __('site.strategic_document.all_documents_report') }}
        </button>

    </div>
</div>

<div class="row filter-results" id="searchDiv">
    <div class="col-md-12">
        <h2 class="mb-4">
            {{ __('custom.search') }}
        </h2>
    </div>

{{--    Level--}}
    <div class="col-md-6">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ __('site.strategic_document.level') }}</label>
                <select class="form-select select2" multiple id="documentLevelSelect">
                    <!--
                        Важно - При второто и третото трябва да се появява или да става активно поле за избор на съответната област или община.
                    -->
{{--                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>--}}
                    @if(isset($docLevels) && $docLevels->count())
                        @foreach($docLevels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="col-12"></div>
    <div class="col-md-4" id="policy_div_id">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1"
                    class="form-label">{{ trans_choice('custom.policy_area', 1) }}</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="policySelect">
{{--                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>--}}
                    @foreach ($policyAreas as $policyArea)
                        <option value="{{ $policyArea->id }}">{{ $policyArea->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-4" id="ekate_areas_div_id">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1"
                       class="form-label">{{ trans_choice('site.strategic_document.areas', 1) }}</label>
                <select class="form-select select2" multiple id="ekate_areas_id">
{{--                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>--}}
{{--                        <option></option>--}}
                    @foreach ($ekateAreas as $ekateArea)
                        <option value="{{ $ekateArea->id }}">{{ $ekateArea->ime }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-4" id="ekate_municipalities_div_id">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1"
                       class="form-label">{{ trans_choice('custom.municipalities', 1) }}</label>
                <select class="form-select select2" multiple id="ekate_municipalities_id">
{{--                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>--}}
                    @foreach ($ekateMunicipalities as $ekateMunicipality)
                        <option value="{{ $ekateMunicipality->id }}">{{ $ekateMunicipality->ime }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-12"></div>
    <div class="col-md-6">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1"
                       class="form-label">{{ __('site.strategic_document.categories_based_on_livecycle') }}</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="categorySelect">
{{--                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>--}}
                    <option value="active">{{ trans_choice('custom.effective', 1) }}</option>
                    <option value="expired">{{ trans_choice('custom.expired', 1) }}</option>
                    <option value="public_consultation">{{ trans_choice('custom.in_process_of_consultation', 1) }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ __('site.strategic_document.search_in_title_content') }}</label>
                <input type="text" class="form-control" id="searchInTitle">
            </div>
        </div>
    </div>
</div>
<div class="row mb-5 action-btn-wrapper" id="searchButtons">
    <div class="col-md-3 col-sm-12">
        <button id="searchBtn" class="btn rss-sub main-color"><i
                class="fas fa-search main-color"></i>{{ __('custom.search') }}</button>
        <button class="btn btn-primary main-color clear" id="clearForm">
            <i class="fas fa-eraser"></i> {{ __('custom.clearing') }}
        </button>
    </div>

    <div class="col-md-9 text-end col-sm-12">
        <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>{{ __('custom.rss_subscribe') }}</button>
        <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>{{ __('custom.subscribe') }}</button>

        @can('create', auth()->user())
            <a href="{{ route($editRouteName) }}" class="btn btn-success text-success"><i
                    class="fas fa-circle-plus text-success me-1"></i>{{ trans_choice('custom.adding', 1) }}</a>
        @endcan
    </div>
</div>

<div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0" id="sorting">
    <div class="text-start col-md-1">
        <i class="fas fa-info-circle text-primary " style="font-size: 20px" title="{{ __('site.sort_info_strategic_documents') }}" data-html="true" data-bs-placement="top" data-bs-toggle="tooltip"><span class="d-none">.</span></i>
    </div>
    <div class="col-md-3">
        @php
            $translation = trans_choice('custom.policy_area', 1);
            $sort_by = 'policy-area';
            $translation = trans_choice('custom.policy_area', 1);
            $sort_by = 'policy-area';
            $ajax = true;
            $ajaxContainer = '#policy-area';
        @endphp
        @include('components.sortable-link', compact('sort_by', 'translation', 'ajax', 'ajaxContainer'))
    </div>
    <div class="col-md-2 ">
        @php
            $sort_by = 'title';
            $ajax = true;
            $ajaxContainer = '#title';
            $translation = trans_choice('custom.title', 1);
            $sort_by = 'title';
        @endphp
        @include('components.sortable-link', compact('sort_by', 'translation', 'ajax', 'ajaxContainer'))
    </div>


    <div class="col-md-3">
        @php
            $ajax = true;
            $ajaxContainer = '#valid-from';
            $translation = trans_choice('custom.valid_from', 1);
            $sort_by = 'valid-from';
        @endphp
        @include('components.sortable-link', compact('sort_by', 'translation', 'ajax', 'ajaxContainer'))
    </div>
    <div class="col-md-3">
        @php
            $ajax = true;
            $ajaxContainer = '#valid-to';
            $translation = trans_choice('custom.valid_to', 1);
            $sort_by = 'valid-to';
        @endphp
        @include('components.sortable-link', compact('sort_by', 'translation', 'ajax', 'ajaxContainer'))
    </div>
</div>

<div class="row justify-content-end my-3" id="paginationResultsDiv">
    <div class="col-md-4">

    </div>
    <div class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
        <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">{{ __('custom.filter_pagination') }}:</label>
        <select class="form-select w-auto" id="paginationResults">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
        </select>
    </div>
</div>
