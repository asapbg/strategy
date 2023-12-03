@push('scripts')
    <script>
        $(document).ready(function() {

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

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let target = $(e.target).attr("href");

                if (target === '#tree-view') {
                    view = 'tree-view';
                    console.log(view);
                    updateUrlParameters({ 'view': 'tree-view' });
                    liveCycle.show();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    view = 'table-view';
                    updateUrlParameters({ 'view': 'table-view' });
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    liveCycle.hide();
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

            const viewParam = urlParams.view;
            if (viewParam === 'tree-view') {
                $('#myTab a[href="#tree-view"]').tab('show');
            }
            if (viewParam === 'table-view') {
                $('#myTab a[href="#table-view"]').tab('show');
            }
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

            categorySelect.on('change', function() {
                const selectedValues = $(this).val();
                updateUrlParameters({ 'category': selectedValues.join(',') });
            });
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
            });

            $('#searchBtn').on('click', function () {
                window.location.href = buildUrl();
            });

            const buildUrl = () => {
                const policyAreaSelectedIds = policySelect.val();
                //const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
                const titleValue = searchInTitle.val();
                const validFromValue = validFrom.val();
                const validToValue = validTo.val();
                const paginationSelectedResult = paginationResults.val();

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
                    '&view=' + encodeURIComponent(view) + '&export=' + doExport + '&document-report=' + documentReport
                    + '&ekate-area=' + ekateAreasId.val() + '&ekate-municipality=' + ekateMunicipalitiesId.val() + '&pris-acts=' + prisAct.val();//prisAct
                doExport = null;
                documentReport = null;
                return url;
            }

            $('.pagination a').on('click', function (e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'), window.location.href);
                const params = url.searchParams;
                const policyAreaSelectedIds = policySelect.val();
                const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
                const titleValue = searchInTitle.val();
                const paginationResultsValue = paginationResults.val();
                const categoryResultsValues = categorySelect.val();

                const paramsToUpdate = {
                    'policy-area' : policyAreaSelectedIds,
                    'prepared-institution' : preparedInstitutionSelectedIds,
                    'title' : titleValue,
                    'pagination-results' : paginationResultsValue,
                    'category' : categoryResultsValues,
                    'valid-from' : validFrom.val(),
                    'valid-to' : validTo.val(),
                    'document-level' : documentLevelSelect.val(),
                    'category-livecycle' : categorySelectLivecycleSelect.val(),
                }

                Object.keys(paramsToUpdate).forEach(function (key) {
                    const value = paramsToUpdate[key];
                    params.set(key, value);
                });

                if (orderBy) {
                    params.set('order_by', orderBy);
                }
                if (sortDirection) {
                    params.set('direction', orderBy);
                }
                window.location.href = url.toString();
            });
        });
    </script>
@endpush
