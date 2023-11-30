@push('scripts')
    <script type="application/javascript">
        function loadMemberData(url) {
            const form = document.querySelector('form[name=CHAIRMAN_FORM_EDIT]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name=advisory_board_member_id]').value = data.id;
                    form.querySelector('#member_name_bg').value = data.translations[0].member_name;
                    form.querySelector('#member_name_en').value = data.translations[1].member_name;
                    form.querySelector('#advisory_type_id_change').value = data.advisory_type_id;
                    $('#advisory_type_id_change').trigger('change');
                    form.querySelector('#advisory_chairman_type_id_change').value = data.advisory_chairman_type_id;
                    $('#advisory_chairman_type_id_change').trigger('change');
                    form.querySelector('#member_job_bg').value = data.translations[0].member_job;
                    form.querySelector('#member_job_en').value = data.translations[1].member_job;
                    $(form.querySelector('#member_notes_bg')).summernote("code", data.translations[0].member_notes);
                    $(form.querySelector('#member_notes_en')).summernote("code", data.translations[1].member_notes);
                    form.querySelector('#email').value = data.email;
                    $('#consultation_level_id_change').trigger('change');
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function loadSecretaryCouncilData(url) {
            const form = document.querySelector('form[name=SECRETARY_COUNCIL_FORM_EDIT]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name=advisory_board_secretary_council_id]').value = data.id;
                    form.querySelector('#name_bg').value = data.translations[0].name;
                    form.querySelector('#name_en').value = data.translations[1].name;
                    form.querySelector('#job_bg').value = data.translations[0].job;
                    form.querySelector('#job_en').value = data.translations[1].job;
                    $(form.querySelector('#notes_bg')).summernote("code", data.translations[0].notes);
                    $(form.querySelector('#notes_en')).summernote("code", data.translations[1].notes);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function updateAjax(element, url) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = element.closest('.modal-content').querySelector('form');
            const formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                },
                url: url,
                data: formData,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (result) {
                    // Get a reference to the modal
                    const modal = new bootstrap.Modal(element.closest('.modal'));

                    // Hide the modal
                    modal.hide();

                    window.location.reload();
                },
                error: function (xhr) {
                    changeButtonState(element, 'finished');

                    // Handle error response
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        for (let i in errors) {
                            const search_class = '.error_' + i;
                            form.querySelector(search_class).textContent = errors[i][0];
                        }
                    }
                }
            });
        }

        function loadFunctionFileData(url, locale = 'bg') {
            const form = document.querySelector('form[name=FUNCTIONS_FILE_UPDATE]');

            const locale_spans = form.querySelectorAll('.locale');
            locale_spans.forEach(function (element) {
                element.innerHTML = locale.toUpperCase();
            });

            // Get all input elements with an ID that starts with "file"
            const fileInputs = form.querySelectorAll('input[name^="file"][class^="form-control"]');

            // Loop through the file input elements and add the "_bg" suffix to their names
            fileInputs.forEach(function (input) {
                input.setAttribute('name', input.getAttribute('id') + '_' + locale);
            });

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name="file_id"]').value = data.id;
                    form.querySelector('input[name="file_name_' + locale + '"]').value = data.custom_name;
                    form.querySelector('input[name="file_description_' + locale + '"]').value = data['description_' + locale];
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        /**
         * Change button state for ajax forms.
         * State can be either 'loading' or 'finished'.
         *
         * @param element
         * @param state
         */
        function changeButtonState(element, state = 'loading') {
            const button_text_translation = state === 'loading' ? @json(__('custom.loading')) : @json(__('custom.add'));
            const loader = element.querySelector('.spinner-grow');

            element.querySelector('.text').innerHTML = button_text_translation;
            element.disabled = state === 'loading';

            if (state === 'loading') {
                loader.classList.remove('d-none');
                return;
            }

            loader.classList.add('d-none');
        }

        /**
         * Display the name of the file next to the input element.
         *
         * @param input
         */
        function attachDocFileName(input) {
            if (typeof input.files[0] === 'object') {
                console.log($(input).siblings('.document-name'))
                $(input).siblings('.document-name').html(input.files[0].name);
            }
        }

        /**
         * Submit file through ajax request.
         *
         * @param element
         * @param url
         */
        function submitFileAjax(element, url) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = element.closest('.modal-content').querySelector('form');
            const formData = new FormData(form);

            // Get all file input elements with an ID that starts with "file"
            const fileInputs = document.querySelectorAll('[id^="file"][type=file]');

            // Loop through the file input elements and append their files to the form data
            for (let i = 0; i < fileInputs.length; i++) {
                const fileInput = fileInputs[i];
                formData.append('file' + i, fileInput.files[0]);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                },
                url: url,
                data: formData,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (result) {
                    window.location.reload();
                },
                error: function (xhr) {
                    changeButtonState(element, 'finished');

                    // Handle error response
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        for (let i in errors) {
                            const search_class = '.error_' + i;
                            form.querySelector(search_class).textContent = errors[i][0];
                        }
                    }
                }
            });
        }

        /**
         * Listen for toggle deleted button, to show deleted resources.
         *
         * @param element
         * @param current_tab
         */
        function toggleDeletedFiles(element, current_tab = '') {
            const url = window.location.pathname;
            const after_url = '#' + current_tab;

            if (element.checked) {
                window.location = url + '?show_deleted_' + current_tab + '_files=1' + after_url;
                return;
            }

            window.location = url + after_url;
        }
    </script>
@endpush
