<div class="modal fade" id="modal-create-section" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.section', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="SECTION_FORM"
                      action=""
                      class="pull-left">
                    @csrf

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label"
                                       for="title">{{ __('validation.attributes.title') }}:</label>
                                <input type="text" class="form-control form-control-sm" id="title" name="title"/>
                                <div id="titleHelp" class="form-text">{{ __('custom.custom_section_title_help') }}.
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label" for="order">
                                    {{ trans_choice('custom.order', 1) }}
                                </label>

                                <select id="order" name="order"
                                        class="form-control form-control-sm select2-no-clear">
                                    <option value="" selected>{{ __('custom.custom_section_order_end') }}</option>

                                    @if(isset($sections) && $sections->count() >= 2)
                                        @for($i=2; $i<=$sections->count(); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    @endif

                                    <option value="9999">{{ __('custom.custom_section_order_start') }}</option>
                                </select>

                                <div class="text-danger mt-1 error_advisory_type_id"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <label for="body_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.description') }}
                                    ({{ Str::upper($lang['code']) }})
                                    <span class="required">*</span>
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="body_{{ $lang['code'] }}"
                                          id="body_{{ $lang['code'] }}"></textarea>

                                <div class="text-danger mt-1 error_body_{{ $lang['code'] }}"></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <button type="button" class="btn btn-success" onclick="addFilesRow()">
                                <i class="fa fa-plus mr-1"></i>
                                {{ __('custom.file') }}
                            </button>
                        </div>
                    </div>

                    <div class="row files"></div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitSectionAjax(this, '{{ route('admin.advisory-boards.sections.store', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        const __chose_file = @json(__('custom.select_file'));
        const __file = @json(__('custom.file'));
        const __file_name = @json(__('custom.name'));
        const __file_description = @json(__('custom.description'));
        const available_languages = @json(config('available_languages') ?? []);

        let generated_inputs = 0;

        /**
         * Submit new section.
         *
         * @param element
         * @param url
         */
        function submitSectionAjax(element, url) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = document.querySelector('form[name=SECTION_FORM]');
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
                    const modal = new bootstrap.Modal(document.getElementById('modal-create-section'));

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

        /**
         * Create new row with files.
         */
        function addFilesRow() {
            const container = document.querySelector('#modal-create-section .row.files');

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

            file_label.appendChild(required_label);

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
    </script>
@endpush
