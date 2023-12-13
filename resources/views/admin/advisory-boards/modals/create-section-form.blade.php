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
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="title_{{ $lang['code'] }}">{{ __('validation.attributes.title') }}
                                        ({{ Str::upper($lang['code']) }})
                                        :
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-sm"
                                           id="title_{{ $lang['code'] }}" name="title_{{ $lang['code'] }}"/>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mb-2">
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
    </script>
@endpush
