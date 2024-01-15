<div class="modal fade" id="modal-edit-meeting" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' ' . Str::lower(trans_choice('custom.meetings', 1)) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="MEETING_UPDATE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="meeting_id" value=""/>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="next_meeting">
                                    {{ __('validation.attributes.next_meeting') }}:
                                    <span class="required">*</span></label>
                                <input type="text" class="form-control form-control-sm datepicker"
                                       value="" id="next_meeting" name="next_meeting">
                            </div>

                            <div class="text-danger mt-1 error_next_meeting"></div>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-12">
                                <label for="description_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.description') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="description_{{ $lang['code'] }}"
                                          id="description_{{ $lang['code'] }}"></textarea>
                            </div>

                            <div class="text-danger mt-1 error_description_{{ $lang['code'] }}"></div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.meetings.update', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
