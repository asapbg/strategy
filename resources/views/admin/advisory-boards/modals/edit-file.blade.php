<div class="modal fade" id="modal-edit-function-file" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' ' . Str::lower(trans_choice('custom.file', 1)) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="FILE_UPDATE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="file_id" value=""/>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label p-0"
                                       for="file">{{ __('custom.file') }}
                                    (<span class="locale"></span>)
                                </label>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="file"
                                               class="btn btn-outline-secondary">{{ __('custom.select_file') }}</label>
                                        <input name="file" class="d-none form-control" type="file" id="file"
                                               onchange="attachDocFileName(this)">
                                        <span class="document-name"></span>
                                        <div id="fileUpdateHelp" class="form-text">{{ __('custom.file_update_help') }}.
                                        </div>
                                    </div>

                                    <div class="text-danger mt-1 error_file"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label"
                                       for="file_name">{{ __('custom.name') }}
                                    (<span class="locale"></span>)
                                </label>

                                <div class="row">
                                    <div class="col-12">
                                        <input class="form-control form-control-sm"
                                               id="file_name" type="text"
                                               name="file_name">
                                    </div>
                                </div>

                                <div class="text-danger mt-1 error_file_name"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="file_description">{{ __('custom.description') }}
                                    (<span class="locale"></span>)
                                </label>

                                <div class="row">
                                    <div class="col-12">
                                        <input class="form-control form-control-sm"
                                               id="file_description"
                                               type="text"
                                               name="file_description">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="resolution_council_ministers">{{ __('validation.attributes.resolution_council_matters') }}
                                </label>

                                <div class="row">
                                    <div class="col-12">
                                        <input class="form-control form-control-sm"
                                               id="resolution_council_ministers" type="text"
                                               name="resolution_council_ministers"/>
                                    </div>
                                </div>

                                <div class="text-danger mt-1 error_resolution_council_ministers"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="state_newspaper">{{ __('validation.attributes.state_newspaper') }}
                                </label>

                                <div class="row">
                                    <div class="col-12">
                                        <input class="form-control form-control-sm"
                                               id="state_newspaper" type="text"
                                               name="state_newspaper"/>
                                    </div>
                                </div>

                                <div class="text-danger mt-1 error_state_newspaper"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="effective_at">{{ __('validation.attributes.effective_at') }}
                                </label>

                                <input type="text" id="effective_at" name="effective_at"
                                       class="datepicker form-control form-control-sm"/>

                                <div class="text-danger mt-1 error_effective_at"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateFileAjax(this)">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.update') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        function updateFileAjax(element) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = document.querySelector('form[name=FILE_UPDATE]');
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
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.advisory-boards.file.update', $item) }}",
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
    </script>
@endpush
