<div class="modal fade" id="modal-edit-section" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' ' . Str::lower(trans_choice('custom.section', 1)) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="SECTION_UPDATE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="section_id" value=""/>

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
                                    <option value="">{{ __('custom.custom_section_order_end') }}</option>

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
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.sections.update', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.update') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
