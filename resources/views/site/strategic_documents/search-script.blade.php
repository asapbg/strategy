@push('scripts')
    <script>
        $(document).ready(function() {
            let processOfConsultation = $('#processOfConsultation');
            let view = '';
            processOfConsultation.hide();
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                let target = $(e.target).attr("href");
                if (target === '#tree-view') {
                    view = 'tree-view';
                    processOfConsultation.show();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    view = 'table-view';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    processOfConsultation.hide();
                }
            });
            const policySelect = $('#policySelect');
            const preparedInstitutionSelect = $('#preparedInstitutionSelect');
            const searchInTitle = $('#searchInTitle');
            const paginationResults = $('#paginationResults');
            const categorySelect = $('#categorySelect');
            const documentLevelSelect = $('#documentLevelSelect');
            const validFrom = $('#valid_from');
            const validTo = $('#valid_to');
            const infiniteDate = $('#date_expiring_indefinite_checkbox');
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
            const policySelectValues = urlParams['policy-area'];
            //const prepareInstitutionsSelectValues = urlParams['prepared-institution'];
            const searchInTitleValue = urlParams['title'];
            const paginationResultsValue = urlParams['pagination-results'];
            const categoryResultsValue = urlParams['category'];
            const documentLevelResultsValue = urlParams['document-level'];
            const validFromResultsValue = urlParams['valid-from'];
            const validToResultsValue = urlParams['valid-to'];
            const dateInfiniteValue = urlParams['date-infinite'];
            if (policySelectValues) {
                const policySelectArray = policySelectValues.split(',');
                policySelect.val(policySelectArray).trigger('change');
            }

            if (searchInTitleValue) {
                searchInTitle.val(searchInTitleValue).trigger('change');
            }

            if (paginationResultsValue) {
                paginationResults.val(paginationResultsValue).trigger('change');
            }

            if (categoryResultsValue) {
                const categorySelectArray = categoryResultsValue.split(',');
                categorySelect.val(categorySelectArray).trigger('change');
            }

            if (validFromResultsValue) {
                validFrom.val(validFromResultsValue).trigger('change');
            }

            if (validToResultsValue) {
                validTo.val(validToResultsValue).trigger('change');
            }
            if (documentLevelResultsValue) {
                documentLevelSelect.val(documentLevelResultsValue).trigger('change');
            }
            if (documentLevelResultsValue) {
                documentLevelSelect.val(documentLevelResultsValue).trigger('change');
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

            validFrom.on('change', function () {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'valid-from': selectedValue });
            });

            validTo.on('change', function () {
                const selectedValue = $(this).val();
                updateUrlParameters({ 'valid-to': selectedValue });
            });

            paginationResults.on('change', function () {
                const paginationResultsValue = parseInt($(this).val(), 10);
                updateUrlParameters({ 'pagination-results':paginationResultsValue });
            });

            $('#searchBtn').on('click', function () {
                /*
                const policyAreaSelectedIds = policySelect.val();
                const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
                const titleValue = searchInTitle.val();
                const validFromValue = validFrom.val();
                const validToValue = validTo.val();
                const paginationSelectedResult = paginationResults.val();

                 */
                /*
                const url = '/strategy-documents/search?policy-area=' + encodeURIComponent(policyAreaSelectedIds) +
                    '&category=' + encodeURIComponent(categorySelect.val()) +
                    '&pagination-results=' + paginationSelectedResult +
                    '&title=' + encodeURIComponent(titleValue) +
                    '&valid-from=' + encodeURIComponent(validFromValue) +
                    '&valid-to=' + encodeURIComponent(validToValue) +
                    '&date-infinite=' + encodeURIComponent(infiniteDate.prop('checked')) +
                    '&document-level=' + encodeURIComponent(documentLevelSelect.val()) +
                    '&view=' + encodeURIComponent(view);

                 */
                window.location.href = buildUrl();
            });

            const buildUrl = () => {
                const policyAreaSelectedIds = policySelect.val();
                const preparedInstitutionSelectedIds = preparedInstitutionSelect.val();
                const titleValue = searchInTitle.val();
                const validFromValue = validFrom.val();
                const validToValue = validTo.val();
                const paginationSelectedResult = paginationResults.val();
                return  '/strategy-documents/search?policy-area=' + encodeURIComponent(policyAreaSelectedIds) +
                    '&category=' + encodeURIComponent(categorySelect.val()) +
                    '&pagination-results=' + paginationSelectedResult +
                    '&title=' + encodeURIComponent(titleValue) +
                    '&valid-from=' + encodeURIComponent(validFromValue) +
                    '&valid-to=' + encodeURIComponent(validToValue) +
                    '&date-infinite=' + encodeURIComponent(infiniteDate.prop('checked')) +
                    '&document-level=' + encodeURIComponent(documentLevelSelect.val()) +
                    '&policy-area-sort-order=' + encodeURIComponent(policyAreaSortOrder) +
                    '&view=' + encodeURIComponent(view);
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
                params.set('policy-area', policyAreaSelectedIds);
                params.set('prepared-institution', preparedInstitutionSelectedIds);
                params.set('title', titleValue);
                params.set('pagination-results', paginationResultsValue);
                params.set('category', categoryResultsValues);
                params.set('valid-from', validFrom.val());
                params.set('valid-to', validTo.val());
                params.set('document-level', documentLevelSelect.val());
                params.set('pagination-results', paginationResultsValue);

                window.location.href = url.toString();
            });
            let policyAreaSort = $('#policyAreaSort');

            policyAreaSort.on('click', function() {
                policyAreaSortOrder = policyAreaSortOrder === 'asc' ? 'desc' : 'asc';
                policyAreaSort.val('asc');
                window.location.href = buildUrl();
            });
        });
    </script>
@endpush
