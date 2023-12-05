<div class="row filter-results mb-2">
    <div class="col-md-6">
        <h2 class="mb-4">
            Търсене
        </h2>
    </div>

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
            <button class="btn btn-primary main-color dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-download main-color me-2"></i>
                Експорт
            </button>

            <ul class="dropdown-menu">
                <li><a id="pdf_export" class="dropdown-item" href="#">Експорт като Pdf</a></li>
                <li><a id="excel_export" class="dropdown-item" href="#">Експорт като Excel</a></li>
                <li><a id="csv_export" class="dropdown-item" href="#">Експорт като Csv</a></li>
            </ul>
        </div>
        <button id="documents_report" class="btn btn-primary main-color dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-download main-color me-2"></i>
            Справка с всички документи
        </button>
        <!--
        <button id="strategicDocumentsExport" class="btn btn-primary main-color">
            <i
                class="fas fa-download main-color me-1"></i>
            Справка с всички документи </button>
        -->
    </div>


    <div class="col-md-12">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ trans_choice('custom.policy_area', 1) }}</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="policySelect">
                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>
                    @foreach ($policyAreas as $policyArea)
                        <option value="{{ $policyArea->id }}">{{ $policyArea->name }}</option>
                    @endforeach
                </select>
                <!--
                <select class="form-select select2" multiple aria-label="Default select example">
                    <option value="1">Всички</option>
                    <option value="1">Регионална политика</option>
                    <option value="1">Образование</option>
                    <option value="1">Външна политика, сигурност и отбрана</option>
                </select>
                -->
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ trans_choice('custom.category', 1) }}</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="categorySelect">
                    <option value="all">{{ trans_choice('custom.all', 1) }}</option>
                    <option value="active">{{ trans_choice('custom.effective', 1) }}</option>
                    <option value="expired">{{ trans_choice('custom.expired', 1) }}</option>
                    <option value="public_consultation">{{ trans_choice('custom.in_process_of_consultation', 1) }}</option>
                </select>
            </div>
        </div>
    </div>
    <!-- Pris -->
    <div class="col-md-12">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ trans_choice('custom.pris', 1) }}</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="pris_act_ids">
                    <option value="">--</option>
                    @foreach($prisActs as $prisAct)
                        <option value="{{ $prisAct->id }}">{{ $prisAct->regNum }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- End Pris -->
    <div class="col-md-12">
        <div class="input-group" id="liveCycle">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ trans_choice('custom.categories_based_on_livecycle', 1) }}</label>
                <select class="form-select select2" multiple aria-label="Default select example" id="category_select_livecycle">
                    <option value="active">{{ trans_choice('custom.effective', 1) }}</option> <!-- default -->
                    <option value="expired">{{ trans_choice('custom.expired', 1) }}</option>
                    <option value="public_consultation">{{ trans_choice('custom.in_process_of_consultation', 1) }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Търсене в
                    Заглавие/Съдържание</label>
                <input type="text" class="form-control" id="searchInTitle">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Валидна от:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                           class="form-control datepicker" id="valid_from">
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">Валидна до:</label>
                <div class="input-group">
                    <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                           class="form-control datepicker" id="valid_to">
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <div class="mb-3 d-flex flex-column w-100">
                <label for="date_expiring_indefinite_checkbox" class="form-label">Безсрочна:</label>
                <div class="input-group">
                    <div class="mb-3 d-flex flex-column w-100">
                        <div class="form-check form-switch">
                            <input type="hidden" name="date_expiring_indefinite" value="0">
                            <input type="checkbox" id="date_expiring_indefinite_checkbox" name="date_expiring_indefinite"
                                   class="form-check-input" value="1">
                            <label class="form-check-label" for="date_expiring_indefinite_checkbox">Безсрочна</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column w-100">
                <label for="exampleFormControlInput1" class="form-label">Ниво</label>
                <select class="form-select" id="documentLevelSelect">
                    <!--
                        Важно - При второто и третото трябва да се появява или да става активно поле за избор на съответната област или община.
                    -->
                    <option value="all">Всички</option>
                    <option value="1">Централно</option>
                    <option value="2">Областно</option>
                    <option value="3">Общинско</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3" id="ekate_areas_div_id">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ trans_choice('custom.areas', 1) }}</label>
                <select class="form-select" id="ekate_areas_id">
                    <option value="">--</option>
                    @foreach ($ekateAreas as $ekateArea)
                        <option value="{{ $ekateArea->id }}">{{ $ekateArea->ime }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3" id="ekate_municipalities_div_id">
        <div class="input-group ">
            <div class="mb-3 d-flex flex-column  w-100">
                <label for="exampleFormControlInput1" class="form-label">{{ trans_choice('custom.municipalities', 1) }}</label>
                <select class="form-select" id="ekate_municipalities_id">
                    <option value="">--</option>
                    @foreach ($ekateMunicipalities as $ekateMunicipality)
                        <option value="{{ $ekateMunicipality->id }}">{{ $ekateMunicipality->ime }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5 action-btn-wrapper">
    <div class="col-md-3 col-sm-12">
        <button id="searchBtn" class="btn rss-sub main-color"><i class="fas fa-search main-color"></i>Търсене</button>
    </div>

    <div class="col-md-9 text-end col-sm-12">
        <button class="btn btn-primary  main-color"><i
                class="fas fa-square-rss text-warning me-1"></i>RSS
            Абониране</button>
        <button class="btn btn-primary main-color"><i
                class="fas fa-envelope me-1"></i>Абониране</button>

        @can('create', auth()->user())
            <a href="{{ route( $editRouteName) }}" class="btn btn-success text-success"><i
                class="fas fa-circle-plus text-success me-1"></i>{{ trans_choice('custom.adding', 1) }}</a>
        @endcan
    </div>
</div>

<div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
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
    <div class="col-md-3 ">
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

<div class="row justify-content-end my-3">
    <div class="col-md-4">

    </div>
    <div
        class="col-md-8 text-end col-sm-12 d-flex align-items-center justify-content-end flex-direction-row">
        <label for="exampleFormControlInput1" class="form-label fw-bold mb-0 me-3">Брой
            резултати:</label>
        <select class="form-select w-auto" id="paginationResults">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
</div>
