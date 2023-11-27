<div class="modal fade" id="modal-add-function-file" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.file', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="FUNCTIONS_FILE" enctype="multipart/form-data" class="pull-left mr-4">
                    @csrf

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="file_{{ $lang['code'] }}">{{ __('custom.file') }}
                                        ({{ Str::upper($lang['code']) }})
                                        <span class="required">*</span>
                                    </label>

                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control form-control-sm" id="file_{{ $lang['code'] }}"
                                                   type="file"
                                                   name="file_{{ $lang['code'] }}">
                                        </div>
                                    </div>

                                    <div class="text-danger mt-1 error_file_{{ $lang['code'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="file_name_{{ $lang['code'] }}">{{ __('custom.name') }}
                                        ({{ Str::upper($lang['code']) }})
                                        <span class="required">*</span>
                                    </label>

                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control form-control-sm"
                                                   id="file_name_{{ $lang['code'] }}" type="text"
                                                   name="file_name_{{ $lang['code'] }}">
                                        </div>
                                    </div>

                                    <div class="text-danger mt-1 error_file_name_{{ $lang['code'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="file_description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                        ({{ Str::upper($lang['code']) }})
                                    </label>

                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control form-control-sm"
                                                   id="file_description_{{ $lang['code'] }}"
                                                   type="text"
                                                   name="file_description_{{ $lang['code'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitFunctionFileAjax(this)">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        function submitFunctionFileAjax(element) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = document.querySelector('form[name=FUNCTIONS_FILE]');
            console.log(form);
            const formData = new FormData(form);
            console.log(formData.entries());

            // Get all file input elements with an ID that starts with "file"
            const fileInputs = document.querySelectorAll('[id^="file"][type=file]');

            // Loop through the file input elements and append their files to the form data
            for (let i = 0; i < fileInputs.length; i++) {
                const fileInput = fileInputs[i];
                console.log(fileInput);
                formData.append('file' + i, fileInput.files[0]);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.advisory-boards.function.file.store', $item) }}",
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
    </script>
@endpush
