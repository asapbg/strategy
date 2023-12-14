@push('scripts')
    <script>
        $(document).ready(function() {

            setTimeout(function() {
                const url = buildUrl();
                loadStrategyDocuments(1, url);
            }, 100);


            let doExport = null;
            let documentReport = null;
            const pdfExport = $('#pdf_export');
            const excelExport =  $('#excel_export');
            const csvExport =  $('#csv_export');
            const documentsReport = $('#documents_report');
            const documentLevelSelect = $('#documentLevelSelect');
            const administrationSelect = $('#administrationSelect');
            const processOfConsultation = $('#processOfConsultation');
            const ekateAreasId = $('#ekate_areas_id');
            const ekateMunicipalitiesId = $('#ekate_municipalities_id');
            documentLevelSelect.add(administrationSelect).add(ekateAreasId).add(ekateMunicipalitiesId).select2({
                multiple: true
            });
            processOfConsultation.select2();

            const prisAct = $('#pris_act_ids');
            const loadPrisOptions = () => {
                $.ajax({
                    success: function(data) {
                        prisAct.select2({
                            data: data.items,
                            placeholder: '--',
                            //minimumInputLength: 1,
                            ajax: {
                                url: '/strategy-document/load-pris-acts',
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        term: params.term,
                                        page: params.page
                                    };
                                },
                                processResults: function (ajaxData) {
                                    return {
                                        results: ajaxData.items,
                                        pagination: {
                                            more: ajaxData.more
                                        }
                                    };
                                },
                                cache: true
                            }
                        });

                        setTimeout(function() {
                            prisAct.trigger('query', {});
                            console.log('trigger query');
                        }, 250);
                    }
                });
            }
            loadPrisOptions();

            processOfConsultation.on('change', function () {
                setTimeout(function() {
                    const url = buildUrl();
                    loadStrategyDocuments(1, url);
                }, 100);
            })

            administrationSelect.val('').trigger('change');
            documentLevelSelect.val('').trigger('change');
            processOfConsultation.val('').trigger('change');
            const ekateAreasDivId = $('#ekate_areas_div_id');
            const ekateMunicipalitiesDivId = $('#ekate_municipalities_div_id');

            ekateAreasDivId.hide();
            ekateMunicipalitiesDivId.hide();

            ekateMunicipalitiesId.val('').trigger('change');
            documentLevelSelect.on('change', function () {
                const selectedDocuments = $(this).val();
                ekateAreasId.val('').trigger('change');
                ekateMunicipalitiesId.val('').trigger('change');

                if (selectedDocuments.includes('2')) {
                    ekateAreasDivId.show();
                } else {
                    ekateAreasDivId.hide();
                }

                if (selectedDocuments.includes('3')) {
                    ekateMunicipalitiesDivId.show();
                } else {
                    ekateMunicipalitiesDivId.hide();
                }
                if (!selectedDocuments.includes('2') && !selectedDocuments.includes('3')) {
                    ekateAreasDivId.hide();
                    ekateMunicipalitiesDivId.hide();
                }
                // AJAX request
                if (selectedDocuments.length > 0) {
                    $.ajax({
                        url: '/strategy-document-institution/' + selectedDocuments.join(','),
                        type: 'GET',
                        dataType: 'json',

                        success: function (data) {
                            administrationSelect.empty();
                            console.log(data);
                            $.each(data.institutions, function (index, item) {
                                administrationSelect.append($('<option>', {
                                    value: item.id,
                                    text: item.name
                                }));
                            });
                            administrationSelect.trigger('change');
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            documentsReport.on('click', function () {
                documentReport = 'download'
                window.location.href = buildUrl();
            });

            pdfExport.on('click', function() {
                doExport = 'pdf';
                window.location.href = buildUrl();
            });
            excelExport.on('click', function () {
                doExport = 'xlsx';
                window.location.href = buildUrl();
            });
            csvExport.on('click', function () {
                doExport = 'csv';
                window.location.href = buildUrl();
            });

            $('#strategicDocumentsExport').on('click', function () {
                doExport = 'export';
                window.location.href = buildUrl();
            });

            let liveCycle = $('#liveCycle');
            let view = '';
            liveCycle.hide();
            const searchDiv = $('#searchDiv');
            const searchButtons = $('#searchButtons');
            const sorting = $('#sorting');
            const paginationResultsDiv = $('#paginationResultsDiv');
            searchDiv.show();
            searchButtons.show();
            sorting.show();
            paginationResultsDiv.show();
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let target = $(e.target).attr("href");

                if (target === '#tree-view') {
                    view = 'tree-view';
                    hideSearch();
                    updateUrlParameters({ 'view': 'tree-view' });
                    liveCycle.show();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    loadStrategyDocuments(1, buildUrl())
                } else {
                    view = 'table-view';
                    searchDiv.show();
                    searchButtons.show();
                    sorting.show();
                    paginationResultsDiv.show();
                    updateUrlParameters({ 'view': 'table-view' });
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    liveCycle.hide();
                    loadStrategyDocuments(1, buildUrl())
                }
            });
            const policySelect = $('#policySelect');
            const preparedInstitutionSelect = $('#preparedInstitutionSelect');
            const searchInTitle = $('#searchInTitle');
            const paginationResults = $('#paginationResults');
            const categorySelect = $('#categorySelect');

            const validFrom = $('#valid_from');
            const validTo = $('#valid_to');
            const infiniteDate = $('#date_expiring_indefinite_checkbox');
            const categorySelectLivecycleSelect = $('#category_select_livecycle');

            let policyAreaSortOrder = 'asc';

            const hideSearch = () => {
                searchDiv.hide();
                searchButtons.hide();
                sorting.hide();
                paginationResultsDiv.hide();
            }

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
            const searchInTitleValue = urlParams['title'];
            const paginationResultsValue = urlParams['pagination-results'];
            const categoryResultsValue = urlParams['category'];
            const categorySelectLivecycleSelectResultValue = urlParams['category-lifecycle'];
            const documentLevelResultsValue = urlParams['document-level'];
            const validFromResultsValue = urlParams['valid-from'];
            const validToResultsValue = urlParams['valid-to'];
            const dateInfiniteValue = urlParams['date-infinite'];
            const orderBy = urlParams['order_by'];
            const sortDirection = urlParams['direction'];
            const ekatteAreaResultValue = urlParams['ekate-area'];
            const ekatteManipulicityResultValue = urlParams['ekate-municipality'];
            const prisActsResultValue = urlParams['pris-acts'];

            view = urlParams['view'];
            if (view &&  view == 'tree-view') {
                hideSearch();
                updateUrlParameters({ 'view': view });
            }

            const valuesToUpdate = {
                policySelect : policySelectValues,
                searchInTitle : searchInTitleValue,
                paginationResults : paginationResultsValue,
                categorySelect : categoryResultsValue,
                documentLevelSelect : documentLevelResultsValue,
                //categorySelectLivecycleSelect : categorySelectLivecycleSelectResultValue,
            };
            function setAndTrigger(element, value) {
                if (value !== undefined && value !== null) {
                    $(element).val(value.split(',')).trigger('change');
                }
            }


            $.each(valuesToUpdate, function (element, value) {
                setAndTrigger(window[element], value);
            });

            if (ekatteAreaResultValue) {
                ekateAreasId.val(ekatteAreaResultValue.split(',')).trigger('change');
            }
            if (ekatteManipulicityResultValue) {
                ekateMunicipalitiesId.val(ekatteManipulicityResultValue.split(',')).trigger('change');
            }

            if (prisActsResultValue) {
                prisAct.val(prisActsResultValue.split(',')).trigger('change');
            }

            if (categorySelectLivecycleSelectResultValue) {
                categorySelectLivecycleSelect.val(categorySelectLivecycleSelectResultValue.split(',')).trigger('change');
            }

            if (validFromResultsValue) {
                validFrom.val(validFromResultsValue).trigger('change');
            }

            if (validToResultsValue) {
                validTo.val(validToResultsValue).trigger('change');
            }

            if (dateInfiniteValue) {
                const isDateInfinite = dateInfiniteValue === "true";
                infiniteDate.prop('checked', isDateInfinite).trigger('change');
            }

            infiniteDate.on('change', function () {
                updateUrlParameters({ 'date-infinite': infiniteDate.prop('checked') });
            });

            categorySelect.on('change', function() {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category': selectedValues.join(',') });
            });

            searchInTitle.on('keyup', function() {
                updateUrlParameters({ 'title': searchInTitle.val() });
            })

            policySelect.on('change', function() {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'policy-area': selectedValues.join(',') });
            });

            categorySelect.on('change', function () {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category': selectedValues.join(',') });
            });

            categorySelectLivecycleSelect.on('change', function () {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category-livecycle': selectedValues.join(',') });
            });

            prisAct.on('change', function () {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'pris-act': selectedValues.join(',') });
            });

            administrationSelect.on('change', function() {
                const selectedValues = $(this).val();
                console.log(selectedValues);
                updateUrlParameters({ 'prepared-institution': selectedValues.join(',') });
            });

            validFrom.on('change', function () {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'valid-from': selectedValue });
            });

            validTo.on('change', function () {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'valid-to': selectedValue });
            });

            ekateAreasId.on('change', function() {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'ekate-area': selectedValue });
            });

            ekateMunicipalitiesId.on('change', function() {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'ekate-municipality': selectedValue });
            });

            paginationResults.on('change', function () {
                const paginationResultsValue = parseInt($(this).val(), 10);
                updateUrlParameters({ 'pagination-results':paginationResultsValue });
                loadStrategyDocuments(1, buildUrl());
            });


            $('#searchBtn').on('click', function (e) {
                e.preventDefault();
                const url = buildUrl();
                loadStrategyDocuments(1, url);
            });

            $('.ajaxSort').on('click', function (e) {
                e.preventDefault();
                const url = $(this).data('url');
                const containerId = $(this).data('container');
                const container = containerId.startsWith('#') ? containerId.slice(1) : containerId;
                const iconElement = $(this).find('i.fa-solid');
                const currentIconClass = iconElement.attr('class');
                const isSort = currentIconClass.includes('fa-sort');
                const sort_white = isSort ? '' : 'text-white';
                $('.ajaxSort i.fa-solid').not(iconElement).removeClass('fa-sort-desc fa-sort-asc').addClass('fa-sort').addClass(sort_white);

                if (currentIconClass.includes('fa-sort-asc')) {
                    updateUrlParameters({ 'direction': 'desc', 'order_by': container });
                    iconElement.removeClass('fa-sort-asc').addClass('fa-sort-desc').addClass(sort_white);

                } else if (currentIconClass.includes('fa-sort-desc')) {
                    updateUrlParameters({ 'direction': 'asc', 'order_by': container });
                    iconElement.removeClass('fa-sort-desc').addClass('fa-sort-asc').addClass(sort_white);

                } else {
                    updateUrlParameters({ 'direction': 'asc', 'order_by': container });
                    iconElement.removeClass('fa-sort').addClass('fa-sort-asc').addClass(sort_white);
                }

                loadStrategyDocuments(1, buildUrl());
            });

            const buildUrl = () => {
                const policyAreaSelectedIds = policySelect.val();
                //const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
                const titleValue = searchInTitle.val();
                const validFromValue = validFrom.val();
                const validToValue = validTo.val();
                const paginationSelectedResult = paginationResults.val();
                const params = getUrlParameters();
                const theOrderBy = params['order_by'];
                const theDirection = params['direction'];
                const documentType = params['document-type'] ?? null;

                const url =  '/strategy-documents/search?policy-area=' + encodeURIComponent(policyAreaSelectedIds) +
                    '&category=' + encodeURIComponent(categorySelect.val()) +
                    '&category-lifecycle=' + encodeURIComponent(categorySelectLivecycleSelect.val()) +
                    '&pagination-results=' + paginationSelectedResult +
                    '&title=' + encodeURIComponent(titleValue) +
                    '&valid-from=' + encodeURIComponent(validFromValue) +
                    '&valid-to=' + encodeURIComponent(validToValue) +
                    '&date-infinite=' + encodeURIComponent(infiniteDate.prop('checked')) +
                    '&document-level=' + encodeURIComponent(documentLevelSelect.val()) +
                    '&policy-area-sort-order=' + encodeURIComponent(policyAreaSortOrder) +
                    '&order_by=' + encodeURIComponent(theOrderBy) +
                    '&direction=' +  encodeURIComponent(theDirection) +
                    '&prepared-institution=' + encodeURIComponent(administrationSelect.val()) +
                    '&document-type=' + encodeURIComponent(documentType) +
                    '&in-process-of-consultation=' + encodeURIComponent(processOfConsultation.val()) +
                    '&view=' + encodeURIComponent(view) + '&export=' + doExport + '&document-report=' + documentReport
                    + '&ekate-area=' + ekateAreasId.val() + '&ekate-municipality=' + ekateMunicipalitiesId.val() + '&pris-acts=' + prisAct.val();//prisAct
                doExport = null;
                documentReport = null;
                return url;
            }

            const clearForm = $('#clearForm');
            clearForm.on('click', function () {
                const baseUrl = '/strategy-documents';

                window.history.replaceState({}, '', baseUrl);
                window.location.href = baseUrl;
                //loadStrategyDocuments(1, baseUrl);
            });

            $(document).on('click', '.pagination a',function(event) {
                event.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                const url = buildUrl();

                loadStrategyDocuments(page, url);
            });

            function loadStrategyDocuments(page = 1, search = '', view = '') {
                let targetContainer = view === 'tree-view' ? '#tree-view' : '#table-view';
                let otherContainer = view === 'tree-view' ? '#table-view' : '#tree-view';
                ShowLoadingSpinner();
                $.ajax({
                    url: '{{ route("strategy-document.list") }}',
                    type: 'GET',
                    data: { page: page, search: search },
                    success: function (response) {
                        $(targetContainer).empty();
                        $(targetContainer).html(response.strategic_documents);

                        setTimeout(function () {
                            if (response.pagination && targetContainer == '#table-view') {
                                $('#table-view').append('<div class="row" id="pagination-container">' + response.pagination + '</div>');
                            }
                        }, 100);
                    },
                    error: function (error) {
                        console.error('Error loading strategy documents:', error);
                    },
                    complete: function () {
                        HideLoadingSpinner();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $('#searchBtn').offset().top
                        }, 20);
                    }
                });
            }
        });

    </script>
@endpush
