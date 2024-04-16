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
                    <input type="hidden" name="formats" value="ALL_ALLOWED_FILE_EXTENSIONS">
                    <div class="row mb-2">
                        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\AdvisoryBoardCustom::translationFieldsProperties(), 'field' => 'title', 'required' => true])
                    </div>

                    <div class="row mb-2">
                        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\AdvisoryBoardCustom::translationFieldsProperties(), 'field' => 'body', 'required' => true])
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <button type="button" class="btn btn-success" onclick="addFilesRow(SECTION_FORM)">
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
            clearErrorMessages();

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
                    if(typeof result.errors != 'undefined') {
                        let errors = Object.entries(result.errors);
                        for (let i = 0; i < errors.length; i++) {
                            const search_class = '.error_' + errors[i][0];
                            if($('#modal-create-section ' + search_class).length) {
                                form.querySelector(search_class).textContent = errors[i][1][0];
                            }
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
    </script>
@endpush
