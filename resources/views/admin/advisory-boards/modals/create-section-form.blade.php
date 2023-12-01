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
        const available_languages_count = @json(config('available_languages') ?? []);

        let generated_inputs = 0;

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

        function addFilesRow() {
            const container = document.querySelector('#modal-create-section .row.files');

            const file_card = document.createElement('div');
            file_card.classList.add('border-bottom');

            const row = document.createElement('div');
            row.classList.add('row');

            // Generate input for every available language
            for (let i in available_languages_count) {
                const is_even = i % 2 === 0;
                const file_col = generateFileInput(available_languages_count[i].code, 1 + generated_inputs, is_even);
                row.appendChild(file_col);

                if (is_even) {
                    row.appendChild(generateDeleteButton());
                }

                generated_inputs++;
            }

            file_card.appendChild(row);

            const container_col = document.createElement('div');
            container_col.classList.add('col-12', 'mt-2');

            container_col.appendChild(file_card);
            container.appendChild(container_col);
        }

        function generateFileInput(language, identifier, should_have_delete_button) {
            // Create the HTML elements
            const file_label = document.createElement('label');
            file_label.classList.add('d-block', 'control-label');
            file_label.htmlFor = `file_${language}`;
            file_label.textContent = __file + ' (' + language.toUpperCase() + ')';

            const file_label_span = document.createElement('span');
            file_label_span.classList.add('required');
            file_label_span.textContent = '*';
            file_label.appendChild(file_label_span);

            const file_col = document.createElement('div');
            const col_class = should_have_delete_button ? 'col-5' : 'col-6';
            file_col.classList.add(col_class);

            const file_input = document.createElement('input');
            file_input.name = `file_${language}[]`;
            file_input.classList.add('d-none');
            file_input.type = 'file';
            file_input.id = `file_${language}_${identifier}`;
            file_input.onchange = () => attachDocFileName(file_input);

            const file_button = document.createElement('label');
            file_button.classList.add('btn', 'btn-outline-secondary');
            file_button.textContent = __chose_file;
            file_button.onclick = () => file_input.click();

            const file_name_span = document.createElement('span');
            file_name_span.classList.add('document-name');

            const file_error = document.createElement('div');
            file_error.classList.add('text-danger', 't-1', `error_file_${language}`);

            // Append the HTML elements to the DOM
            file_col.appendChild(file_label);
            file_col.appendChild(file_input);
            file_col.appendChild(file_button);
            file_col.appendChild(file_name_span);
            file_col.appendChild(file_error);

            return file_col;
        }

        function generateDeleteButton() {
            const delete_col = document.createElement('div');
            delete_col.classList.add('col-auto', 'align-self-center');

            const delete_button = document.createElement('a');
            delete_button.href = 'javascript:;';
            delete_button.classList.add('btn', 'btn-sm', 'btn-danger');
            delete_button.title = 'Изтрий';
            delete_button.innerHTML = '<i class="fa fa-trash"></i>';
            delete_button.onclick = () => removeFileRow(delete_button);

            delete_col.appendChild(delete_button);
            return delete_col;
        }

        function removeFileRow(row) {
            console.log(row);
            row.closest('.col-12').remove()
        }
    </script>
@endpush
