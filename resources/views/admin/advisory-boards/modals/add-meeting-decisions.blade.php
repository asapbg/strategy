<div class="modal fade" id="modal-add-meeting-decisions" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add_information_for_meeting') }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="MEETING_DECISIONS_FORM" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="advisory_board_meeting_id" value=""/>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="date_of_meeting">
                                    {{ __('validation.attributes.date_of_meeting') }}:
                                    <span class="required">*</span></label>
                                <input type="text" class="form-control form-control-sm datepicker"
                                       value="" id="date_of_meeting" name="date_of_meeting">
                            </div>

                            <div class="text-danger mt-1 error_date_of_meeting"></div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="agenda">
                                    {{ __('validation.attributes.agenda') }}
                                </label>

                                <input type="text" class="form-control" id="agenda" name="agenda"/>
                            </div>

                            <div class="text-danger mt-1 error_agenda"></div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="protocol">
                                    {{ __('validation.attributes.protocol') }}
                                </label>

                                <input type="text" class="form-control" id="protocol" name="protocol"/>
                            </div>

                            <div class="text-danger mt-1 error_protocol"></div>
                        </div>
                    </div>

                    <!-- Решения -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-12">
                                <label for="decisions_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.decisions') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="decisions_{{ $lang['code'] }}"
                                          id="decisions_{{ $lang['code'] }}"></textarea>
                            </div>

                            <div class="text-danger mt-1 error_decisions_{{ $lang['code'] }}"></div>
                        @endforeach
                    </div>

                    <!-- Получени предложения -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-12">
                                <label for="suggestions_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.suggestions') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="suggestions_{{ $lang['code'] }}"
                                          id="suggestions_{{ $lang['code'] }}"></textarea>
                            </div>

                            <div class="text-danger mt-1 error_suggestions_{{ $lang['code'] }}"></div>
                        @endforeach
                    </div>

                    <!-- Други -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-12">
                                <label for="other_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.other') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="other_{{ $lang['code'] }}"
                                          id="other_{{ $lang['code'] }}"></textarea>
                            </div>

                            <div class="text-danger mt-1 error_other_{{ $lang['code'] }}"></div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitAjax(this, '{{ route('admin.advisory-boards.decisions.store', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
