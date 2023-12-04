@push('scripts')
    <script>
        $(document).ready(function() {
            loadStrategyDocuments();
            let doExport = null;
            let documentReport = null;
            const pdfExport = $('#pdf_export');
            const excelExport =  $('#excel_export');
            const csvExport =  $('#csv_export');
            const documentsReport = $('#documents_report');
            const documentLevelSelect = $('#documentLevelSelect');
            const ekateAreasDivId = $('#ekate_areas_div_id');
            const ekateMunicipalitiesDivId = $('#ekate_municipalities_div_id');
            const prisAct = $('#pris_act_ids');
            ekateAreasDivId.hide();
            ekateMunicipalitiesDivId.hide();
            const ekateAreasId = $('#ekate_areas_id');
            const ekateMunicipalitiesId = $('#ekate_municipalities_id');
            ekateAreasId.select2();
            ekateMunicipalitiesId.select2();
            documentLevelSelect.on('change', function () {
                const selectedDocument = $(this).val();
                ekateAreasId.val('').trigger('change');
                ekateMunicipalitiesId.val('').trigger('change');
                if (selectedDocument == 2) {
                    ekateAreasDivId.show();
                    ekateMunicipalitiesDivId.hide();
                } else if (selectedDocument == 3) {
                    ekateAreasDivId.hide();
                    ekateMunicipalitiesDivId.show();
                } else {
                    ekateAreasDivId.hide();
                    ekateMunicipalitiesDivId.hide();
                }
            });

            documentsReport.on('click', function () {
                documentReport = 'download'
                window.location.href = buildUrl();
            });

            pdfExport.on('click', function() {
                doExport = 'pdf';
                //updateUrlParameters({ 'export': 'pdf' });
                window.location.href = buildUrl();
                //window.location.href = buildUrl();
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

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let target = $(e.target).attr("href");

                if (target === '#tree-view') {
                    view = 'tree-view';
                    updateUrlParameters({ 'view': 'tree-view' });
                    liveCycle.show();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    loadStrategyDocuments(1, buildUrl())
                } else {
                    view = 'table-view';
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
            //const prepareInstitutionsSelectValues = urlParams['prepared-institution'];
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
                ekateAreasId.val(ekatteAreaResultValue).trigger('change');
            }
            if (ekatteManipulicityResultValue) {
                ekateMunicipalitiesId.val(ekatteManipulicityResultValue).trigger('change');
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
                loadStrategyDocuments(1, buildUrl());
            });

            categorySelect.on('change', function() {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category': selectedValues.join(',') });
                loadStrategyDocuments(1, buildUrl());
            });
            searchInTitle.on('keyup', function() {
                updateUrlParameters({ 'title': searchInTitle.val() });
                loadStrategyDocuments(1, buildUrl());
            })

            policySelect.on('change', function() {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'policy-area': selectedValues.join(',') });
                loadStrategyDocuments(1, buildUrl());
            });

            categorySelect.on('change', function () {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category': selectedValues.join(',') });
                loadStrategyDocuments(1, buildUrl());
            });

            categorySelectLivecycleSelect.on('change', function () {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category-livecycle': selectedValues.join(',') });
                loadStrategyDocuments(1, buildUrl());
            });

            prisAct.on('change', function () {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'pris-act': selectedValues.join(',') });
                loadStrategyDocuments(1, buildUrl());
            });

            validFrom.on('change', function () {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'valid-from': selectedValue });
                loadStrategyDocuments(1, buildUrl());
            });

            validTo.on('change', function () {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'valid-to': selectedValue });
                loadStrategyDocuments(1, buildUrl());
            });

            ekateAreasId.on('change', function() {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'ekate-area': selectedValue });
                loadStrategyDocuments(1, buildUrl());
            });

            ekateMunicipalitiesId.on('change', function() {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'ekate-municipality': selectedValue });
                loadStrategyDocuments(1, buildUrl());
            });

            paginationResults.on('change', function () {
                const paginationResultsValue = parseInt($(this).val(), 10);
                updateUrlParameters({ 'pagination-results':paginationResultsValue });
                loadStrategyDocuments(1, buildUrl());
            });

            $('#searchBtn').on('click', function () {
                window.location.href = buildUrl();
            });

            $('.ajaxSort').on('click', function (e) {
                e.preventDefault();
                const url = $(this).data('url');
                const containerId = $(this).data('container');
                const container = containerId.startsWith('#') ? containerId.slice(1) : containerId;
                const iconElement = $(this).find('i.fa-solid');
                const currentIconClass = iconElement.attr('class');
                console.log(container);
                if (currentIconClass.includes('fa-sort-asc')) {
                    updateUrlParameters({ 'direction': 'desc', 'order_by': container });
                    iconElement.removeClass('fa-sort-asc').addClass('fa-sort-desc');

                } else if (currentIconClass.includes('fa-sort-desc')) {
                    updateUrlParameters({ 'direction': 'asc', 'order_by': container });
                    iconElement.removeClass('fa-sort-desc').addClass('fa-sort-asc');

                } else {
                    updateUrlParameters({ 'direction': 'asc', 'order_by': container });
                    iconElement.removeClass('fa-sort').addClass('fa-sort-asc');
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
                    '&view=' + encodeURIComponent(view) + '&export=' + doExport + '&document-report=' + documentReport
                    + '&ekate-area=' + ekateAreasId.val() + '&ekate-municipality=' + ekateMunicipalitiesId.val() + '&pris-acts=' + prisAct.val();//prisAct
                doExport = null;
                documentReport = null;
                return url;
            }

            $(document).on('click', '.pagination a',function(event) {
                event.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                const url = buildUrl();
                loadStrategyDocuments(page, url);
            });

            function loadStrategyDocuments(page = 1, search = '', view = '') {
                let targetContainer = view === 'tree-view' ? '#tree-view' : '#table-view';
                let otherContainer = view === 'tree-view' ? '#table-view' : '#tree-view';

                $('#spinner-container').show();
                $.ajax({
                    url: '{{ route("strategy-document.list") }}',
                    type: 'GET',
                    data: { page: page, search: search },
                    timeout: 5000,
                    success: function (response) {
                        $(targetContainer).empty();
                        $(targetContainer).html(response.strategic_documents);

                        setTimeout(function () {
                            console.log(targetContainer);
                            if (response.pagination && targetContainer == '#table-view') {
                                $('#table-view').append('<div class="row" id="pagination-container">' + response.pagination + '</div>');
                            }
                        }, 100);
                    },
                    error: function (error) {
                        console.error('Error loading strategy documents:', error);
                    },
                    complete: function () {
                        $('#spinner-container').hide();
                    }
                });
            }
        });

    </script>
@endpush
