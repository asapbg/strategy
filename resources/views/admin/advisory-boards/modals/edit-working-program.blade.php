<div class="modal fade" id="modal-edit-function" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' ' . Str::lower(trans_choice('custom.function', 1)) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="FUNCTION_UPDATE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="function_id" value=""/>

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="working_year">
                                    {{ __('validation.attributes.year') }}:
                                </label>
                                <input type="text" data-provide="datepicker" class="form-control form-control-sm datepicker-year"
                                       value="" id="working_year" name="working_year" data-date-format="yyyy">
                            </div>

                            <div class="text-danger mt-1 error_working_year"></div>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <label for="description_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.description') }}
                                    ({{ Str::upper($lang['code']) }})
                                    <span class="required">*</span>
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="description_{{ $lang['code'] }}"
                                          id="description_{{ $lang['code'] }}"></textarea>

                                <div class="text-danger mt-1 error_body_{{ $lang['code'] }}"></div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.function.update', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.update') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
