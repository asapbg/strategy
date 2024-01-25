@push('scripts')
    <script type="application/javascript">
        /**
         * Redirect to the archive, based on the category id.
         *
         * @param id
         */
        function goToArchive(id) {
            const archive_url = @json(route('admin.advisory-boards.edit', $item) . '?archive_category=:id#archive');
            let url = archive_url.replace(':id', id);
            window.location.href = url;

            setTimeout(() => {
                window.location.reload();
            }, 100)
        }

        /**
         * Create new row with files.
         */
        function addFilesRow(form) {
            const container = form.querySelector('.row.files');

            const row = document.createElement('div');
            row.classList.add('row');

            const card_header = document.createElement('div');
            card_header.classList.add('card-header');

            const card_header_close_btn = document.createElement('button');
            card_header_close_btn.classList.add('btn-close', 'float-right');
            card_header_close_btn.type = 'button';
            card_header_close_btn.onclick = () => card_header_close_btn.closest('.card').remove();
            card_header.appendChild(card_header_close_btn);

            const card_body = document.createElement('div');
            card_body.classList.add('card-body');

            const card = document.createElement('div');
            card.classList.add('card');
            card.appendChild(card_header);

            // Generate input for every available language
            for (let i in available_languages) {
                const column = generateFileInput(available_languages[i].code, SECTION_FORM, 1 + generated_inputs);

                row.appendChild(column);

                generated_inputs++;
            }

            card_body.appendChild(row);
            card.appendChild(card_body)

            const container_col = document.createElement('div');
            container_col.classList.add('col-12', 'mt-2');

            container_col.appendChild(card);
            container.appendChild(container_col);
        }

        /**
         * Generate column with file, name and description.
         *
         * @param language
         * @param form
         * @param identifier
         * @returns {HTMLDivElement}
         */
        function generateFileInput(language, form, identifier) {
            const container = document.createElement('div');
            container.classList.add('col-6');

            const file_label = document.createElement('label');
            file_label.classList.add('col-md-6', 'col-12', 'control-label');
            file_label.htmlFor = `file_${language}_${identifier}`;
            file_label.textContent = __file + ' (' + language.toUpperCase() + ')';

            const required_label = document.createElement('span');
            required_label.classList.add('required', 'ml-1')
            required_label.textContent = '*';

            if(language == 'bg'){
                file_label.appendChild(required_label);
            }

            const file_button = document.createElement('label');
            file_button.classList.add('col-12', 'btn', 'btn-outline-secondary');
            file_button.textContent = __chose_file;
            file_button.onclick = () => form.querySelector(`input[id=file_${language}_${identifier}]`).click();

            const file_input = document.createElement('input');
            file_input.classList.add('d-none');
            file_input.type = 'file';
            file_input.id = `file_${language}_${identifier}`;
            file_input.name = `file_${language}[]`;
            file_input.onchange = () => attachDocFileName(file_input);

            const file_document_name = document.createElement('span');
            file_document_name.classList.add('document-name', 'd-block');
            file_document_name.style.height = '21px';

            const file_error = document.createElement('div');
            file_error.classList.add('text-danger', 't-1', `error_file_${language}_${identifier}`);

            const file_name_input_label = document.createElement('label');
            file_name_input_label.classList.add('col-md-6', 'col-12', 'control-label');
            file_name_input_label.htmlFor = `file_name_${language}_${identifier}`;
            file_name_input_label.textContent = __file_name + ' (' + language.toUpperCase() + ')';

            const file_name_input = document.createElement('input');
            file_name_input.classList.add('form-control', 'form-control-sm');
            file_name_input.id = `file_name_${language}_${identifier}`;
            file_name_input.type = 'text';
            file_name_input.name = `file_name_${language}[]`;
            file_name_input.autocomplete = 'off';

            const file_description_label = document.createElement('label');
            file_description_label.classList.add('col-md-6', 'col-12', 'control-label');
            file_description_label.htmlFor = `file_description_${language}_${identifier}`;
            file_description_label.textContent = __file_description + ' (' + language.toUpperCase() + ')';

            const file_description_input = document.createElement('input');
            file_description_input.classList.add('form-control', 'form-control-sm');
            file_description_input.id = `file_description_${language}_${identifier}`;
            file_description_input.type = 'text';
            file_description_input.name = `file_description_${language}[]`;
            file_description_input.autocomplete = 'off';

            container.appendChild(file_label);
            container.appendChild(file_button);
            container.appendChild(file_input);
            container.appendChild(file_document_name);
            container.appendChild(file_error);
            container.appendChild(file_name_input_label);
            container.appendChild(file_name_input);
            container.appendChild(file_description_label);
            container.appendChild(file_description_input);

            return container;
        }

        function loadFunctionData(url) {
            const form = document.querySelector('form[name=FUNCTION_UPDATE]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name=function_id]').value = data.id;

                    if (data.working_year !== null) {
                        form.querySelector('input[name=working_year]').value = new Date(data.working_year).toLocaleDateString('en-GB', {
                            year: 'numeric'
                        });
                    }

                    for(let i = 0; i < data.translations.length; i++){
                        if(data.translations[i].locale == 'bg'){
                            $(form.querySelector('#description_bg')).summernote("code", data.translations[i].description);
                        }
                        if(data.translations[i].locale == 'en'){
                            $(form.querySelector('#description_en')).summernote("code", data.translations[i].description);
                        }
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function loadMemberData(url) {
            let memberNames = {
                <?php echo \App\Enums\AdvisoryTypeEnum::MEMBER->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::MEMBER->value, 1) ;?>",
                <?php echo \App\Enums\AdvisoryTypeEnum::CHAIRMAN->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::CHAIRMAN->value, 1) ;?>",
                <?php echo \App\Enums\AdvisoryTypeEnum::VICE_CHAIRMAN->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::VICE_CHAIRMAN->value, 1) ;?>",
                <?php echo \App\Enums\AdvisoryTypeEnum::SECRETARY->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::SECRETARY->value, 1) ;?>",
            };

            const form = document.querySelector('form[name=MEMBER_FORM_EDIT]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#modal-edit-member #member-title').html(memberNames[data.advisory_type_id]);
                    $('#modal-edit-member #advisory_type_id').val(data.advisory_type_id);

                    form.querySelector('input[name=advisory_board_member_id]').value = data.id;
                    form.querySelectorAll('input[name=advisory_type_id]').forEach(input => input.value === data.advisory_type_id ? input.checked = true : null);
                    for(let i = 0; i < data.translations.length; i++){
                        if(data.translations[i].locale == 'bg'){
                            form.querySelector('#member_name_bg').value = data.translations[i].member_name;
                            form.querySelector('#member_job_bg').value = data.translations[i].member_job;
                            $(form.querySelector('#member_notes_bg')).summernote("code", data.translations[i].member_notes);

                        }
                        if(data.translations[i].locale == 'en'){
                            iform.querySelector('#member_name_en').value = data.translations[i].member_name;
                            form.querySelector('#member_job_en').value = data.translations[i].member_job;
                            $(form.querySelector('#member_notes_en')).summernote("code", data.translations[i].member_notes);

                        }
                    }

                    form.querySelector('#email').value = data.email;
                    $('#consultation_level_id_change').trigger('change');
                    form.querySelector('#member_institution').value = data.institution_id;
                    $('#member_institution').trigger('change');
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function loadMeetingData(url) {
            const form = document.querySelector('form[name=MEETING_UPDATE]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name=meeting_id]').value = data.id;
                    form.querySelector('#next_meeting').value = new Date(data.next_meeting).toLocaleDateString();
                    for(let i = 0; i < data.translations.length; i++){
                        if(data.translations[i].locale == 'bg'){
                            $(form.querySelector('#description_bg')).summernote("code", data.translations[i].description);
                        }
                        if(data.translations[i].locale == 'en'){
                            $(form.querySelector('#description_en')).summernote("code", data.translations[i].description);
                        }
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function loadSectionData(url) {
            const form = document.querySelector('form[name=SECTION_UPDATE]');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    form.querySelector('input[name=section_id]').value = data.id;
                    if(form.querySelector('#order')) {
                        form.querySelector('#order').value = data.order !== 1 ? data.order : 9999;
                        if (form.querySelector('#order').options.length - 1 === data.order) {
                            form.querySelector('#order').value = '';
                        }

                        $(form.querySelector('#order')).trigger('change');
                    }

                    for(let i = 0; i < data.translations.length; i++){
                        if(data.translations[i].locale == 'bg'){
                            form.querySelector('input[name=title_bg]').value = data.translations[i].title;
                            $(form.querySelector('#body_bg')).summernote("code", data.translations[i].body);
                        }
                        if(data.translations[i].locale == 'en'){
                            form.querySelector('input[name=title_en]').value = data.translations[i].title;
                            $(form.querySelector('#body_en')).summernote("code", data.translations[i].body);
                        }
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function loadFileData(url, locale = 'bg') {
            const form = document.querySelector('form[name=FILE_UPDATE]');

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
                    form.querySelector('input[name="resolution_council_ministers"]').value = data.resolution_council_ministers;
                    form.querySelector('input[name="state_newspaper"]').value = data.state_newspaper;
                    form.querySelector('input[name="effective_at"]').value = new Date(data.effective_at).toLocaleDateString('de-DE', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function updateAjax(element, url) {
            // change button state
            changeButtonState(element);
            clearErrorMessages();

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
                    if(typeof result.errors != 'undefined') {
                        let errors = Object.entries(result.errors);
                        for (let i = 0; i < errors.length; i++) {
                            const search_class = '.error_' + errors[i][0];
                            form.querySelector(search_class).textContent = errors[i][1][0];
                        }
                        changeButtonState(element, 'finished');
                    } else{
                        window.location.reload();
                    }
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
         * Clear all errors from previous ajax.
         * By default it will clear all elements with class ajax-error
         *
         * @param className
         */
        function clearErrorMessages(className = 'ajax-error') {
            document.querySelectorAll('.' + className).forEach(function(el) {
                el.innerHTML = '';
            });
        }

        /**
         * Display the name of the file next to the input element.
         *
         * @param input
         */
        function attachDocFileName(input) {
            typeof input.files[0] === 'object' ? $(input).siblings('.document-name').html(input.files[0].name) : null;
        }

        /**
         * Submit basic ajax form.
         * You can pass the submit button and the url for storing data.
         *
         * @param element
         * @param url
         */
        function submitAjax(element, url) {
            // change button state
            changeButtonState(element);
            clearErrorMessages();

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
                    if(typeof result.errors != 'undefined') {
                        let errors = Object.entries(result.errors);
                        for (let i = 0; i < errors.length; i++) {
                            const search_class = '.error_' + errors[i][0];
                            form.querySelector(search_class).textContent = errors[i][1][0];
                        }
                        changeButtonState(element, 'finished');
                    } else{
                        window.location.reload();
                    }
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
         * Submit file through ajax request.
         *
         * @param element
         * @param url
         */
        function submitFileAjax(element, url) {
            // change button state
            changeButtonState(element);
            clearErrorMessages();

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
                    if(typeof result.errors != 'undefined') {
                        let errors = Object.entries(result.errors);
                        for (let i = 0; i < errors.length; i++) {
                            const search_class = '.error_' + errors[i][0];
                            form.querySelector(search_class).textContent = errors[i][1][0];
                        }
                        changeButtonState(element, 'finished');
                    } else{
                        window.location.reload();
                    }
                    // window.location.reload();
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
            const url_query = window.location.search;

            if (element.checked && url_query.length === 0) {
                window.location = url + '?show_deleted_' + current_tab + '_files=1' + after_url;
                return;
            }

            if (element.checked && url_query.length > 0) {
                window.location = url + url_query + '&show_deleted_' + current_tab + '_files=1' + after_url;
                return;
            }

            let param = 'show_deleted_' + current_tab + '_files';
            window.location = removeParams(param) + after_url;
        }

        function removeParams(sParam)
        {
            var url = window.location.href.split('?')[0]+'?';
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] != sParam) {
                    url = url + sParameterName[0] + '=' + sParameterName[1] + '&'
                }
            }
            return url.substring(0,url.length-1);
        }

        /**
         * Send url query parameter for deleted resource and keep the current tab.
         * Used for sections.
         *
         * @param element
         * @param section
         * @param url_param
         */
        function toggleDeleted(element, section, url_param) {
            const url = window.location.pathname;
            const after_url = '#' + section;

            if (element.checked) {
                window.location = url + `?${url_param}=1${after_url}`
                return;
            }

            window.location = url + after_url;
        }

        function prepareMeetingId(id, form) {
            form.querySelector('input[name=advisory_board_meeting_id]').value = id;
        }

        function setMeetingFileObjectId(id) {
            MEETING_FILE.querySelector('input[name=object_id]').value = id;
        }
    </script>
@endpush
