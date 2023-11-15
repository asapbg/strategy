@extends('layouts.site')

@section('pageTitle', 'Стратегически документи')

@section('content')
<section class="public-constultation py-5">
    <div class="container">
        <div class="row filter-results mb-2">
            <h2 class="mb-4">
                Търсене
            </h2>

            <div class="col-md-12">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Тема:</label>
                        <select class="form-select select2" multiple aria-label="Default select example" id="policySelect">
                        <option value="all">Всички</option>
                            @foreach ($policyAreas as $policyArea)
                                <option value="{{ $policyArea->id }}">{{ $policyArea->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Изработила институция:</label>
                        <select class="form-select select2" multiple aria-label="Default select example" id="preparedInstitutionSelect">
                            <option value="all">Всички</option>
                                @foreach ($preparedInstitutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Търсене в Заглавие/Съдържание</label>
                        <input type="text" class="form-control" id="searchInTitle">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Срок за приемане от:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Срок за приемане до:</label>
                        <div class="input-group">
                            <input type="text" name="fromDate" autocomplete="off" readonly="" value=""
                                class="form-control datepicker">
                            <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group ">
                    <div class="mb-3 d-flex flex-column  w-100">
                        <label for="exampleFormControlInput1" class="form-label">Брой резултати:</label>
                        <select class="form-select" id="paginationResults">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 action-btn-wrapper">
            <div class="col-md-3 col-sm-12">
                <button class="btn rss-sub main-color" id="searchBtn"><i class="fas fa-search main-color"></i>Търсене</button>
            </div>
            <div class="col-md-9 text-end col-sm-12">
                <button class="btn btn-primary  main-color"><i class="fas fa-square-rss text-warning me-1"></i>RSS
                    Абониране</button>
                <button class="btn btn-primary main-color"><i class="fas fa-envelope me-1"></i>Абониране</button>
                <button class="btn btn-success text-success"><i
                        class="fas fa-circle-plus text-success me-1"></i>Добавяне</button>
            </div>
        </div>

        <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i> Тема
                </p>
            </div>
            <div class="col-md-3 ">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Изработила институция
                </p>
            </div>


            <div class="col-md-3">
                <p class="mb-0 cursor-pointer">
                    <i class="fa-solid fa-sort me-2"></i>Заглавие
                </p>
            </div>
            <div class="col-md-3">
                <p class="mb-0 cursor-pointer ">
                    <i class="fa-solid fa-sort me-2"></i>Срок на приемане
                </p>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 mt-2">
                <div class="info-consul text-start">
                    <p class="fw-600">
                        Общо {{ $resultCount }} резултата
                    </p>
                </div>
            </div>
        </div>
        @foreach ($strategicDocuments as $document)
            @if (!$document->active)
                @continue
            @endif
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="consul-wrapper">
                        <div class="single-consultation d-flex">
                            <div class="consult-img-holder">
                                <i class="fa-solid fa-circle-nodes dark-blue"></i>
                            </div>
                            <div class="consult-body">
                                <div href="#" class="consul-item">
                                    <div class="consult-item-header d-flex justify-content-between">
                                        <div class="consult-item-header-link">
                                            <a href="#" class="text-decoration-none" title="{{ $document->title }}">
                                                <h3>{{ $document->title }}</h3>
                                            </a>
                                        </div>
                                        <div class="consult-item-header-edit">
                                            @can('delete', $document)
                                                <a href="{{ route( $deleteRouteName , [$document->id]) }}">
                                                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                       role="button" title="Изтриване"></i>
                                                </a>
                                            @endcan
                                            @can('update', $document)
                                                <a href="{{ route( $editRouteName , [$document->id]) }}">
                                                    <i class="fas fa-pen-to-square float-end main-color fs-4" role="button"
                                                       title="Редакция"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    {{ $document->category }}

                                    <a href="#" title="Образование" class="text-decoration-none mb-3">
                                        <i class="bi bi-mortarboard-fill me-1" title="Образование"></i>
                                        {{ $document->policyArea->name }}
                                    </a>

                                    <div class="meta-consul mt-2">
                                        <span class="text-secondary">
                                            {{ $document->document_date ? $document->document_date . ' г.' : 'Не е указан срок' }}
                                        </span>
                                        <a href="#" title="{{ $document->title }}">
                                            <i class="fas fa-arrow-right read-more"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{ $strategicDocuments->links() }}

</section>
<script>
    $(document).ready(function() {
        const policySelect = $('#policySelect');
        const preparedInstitutionSelect = $('#preparedInstitutionSelect');
        const searchInTitle = $('#searchInTitle');
        const paginationResults = $('#paginationResults');

        const updateUrlParameters = (params) => {
            const searchParams = new URLSearchParams(window.location.search);
            Object.keys(params).forEach(key => {
                searchParams.set(key, params[key]);
            });
            window.history.replaceState({}, '', `${window.location.pathname}?${searchParams}`);
        }
        const getUrlParameters = () => {
            const url = new URL(window.location.href);
            const params = {};
            url.searchParams.forEach((value, key) => {
                params[key] = value;
            });
            return params;
        }
        const urlParams = getUrlParameters();

        const policySelectValues = urlParams['policy-area'];
        const prepareInstitutionsSelectValues = urlParams['prepared-institution'];
        const searchInTitleValue = urlParams['title'];
        const paginationResultsValue = urlParams['pagination-results'];
        if (policySelectValues) {
            const policySelectArray = policySelectValues.split(',');
            policySelect.val(policySelectArray).trigger('change');
        }

        if (prepareInstitutionsSelectValues) {
            const prepareInstitutionsSelectArray = prepareInstitutionsSelectValues.split(',');
            preparedInstitutionSelect.val(prepareInstitutionsSelectArray).trigger('change');
        }

        if (searchInTitleValue) {
            searchInTitle.val(searchInTitleValue).trigger('change');
        }

        if (paginationResultsValue) {
            paginationResults.val(paginationResultsValue).trigger('change');
        }

        preparedInstitutionSelect.on('change', function() {
            const selectedValues = $(this).val();
            updateUrlParameters({ 'prepared-institution': selectedValues.join(',') });
        });

        policySelect.on('change', function() {
            const selectedValues = $(this).val();
            updateUrlParameters({ 'policy-area': selectedValues.join(',') });
        });

        paginationResults.on('change', function () {
            const paginationResultsValue = parseInt($(this).val(), 10);
            updateUrlParameters({ 'pagination-results':paginationResultsValue });
        });

        $('#searchBtn').on('click', function () {
            const policyAreaSelectedIds = policySelect.val();
            const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
            const titleValue = searchInTitle.val();
            const paginationSelectedResult = paginationResults.val();

            const url = '/strategy-documents/search?policy-area=' + encodeURIComponent(policyAreaSelectedIds) +
                '&prepared-institution=' + encodeURIComponent(preparedInstitutionSelectedIds) +
                '&pagination-results=' + paginationSelectedResult + '&title=' + encodeURIComponent(titleValue);
            window.location.href = url;
        });

        $('.pagination a').on('click', function (e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'), window.location.href);
            const params = url.searchParams;

            const policyAreaSelectedIds = policySelect.val();
            const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
            const titleValue = searchInTitle.val();
            const paginationResultsValue = paginationResults.val();

            params.set('policy-area', policyAreaSelectedIds);
            params.set('prepared-institution', preparedInstitutionSelectedIds);
            params.set('title', titleValue);
            params.set('pagination-results', paginationResultsValue);

            window.location.href = url.toString();
        });
    });
</script>
@endsection
