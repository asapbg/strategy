<div class="modal fade" id="modal-edit-secretary-council" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' ' . Str::lower(trans_choice('custom.secretary', 1)) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="SECRETARY_COUNCIL_FORM_EDIT" class="pull-left mr-4">
                    @csrf

                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>
                    <input type="hidden" name="advisory_board_secretary_council_id" value=""/>

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="name_{{ $lang['code'] }}">{{ __('custom.first_name') }} (BG) <span
                                            class="required">*</span></label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="text" id="name_{{ $lang['code'] }}"
                                                   name="name_{{ $lang['code'] }}"
                                                   class="form-control form-control-sm"
                                                   value="" autocomplete="off"/>

                                            <div class="text-danger mt-1 error_name_{{ $lang['code'] }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Длъжност -->
                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <label for="job_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.job') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <input type="text" id="job_{{ $lang['code'] }}"
                                       name="job_{{ $lang['code']}}"
                                       class="form-control form-control-sm"
                                       autocomplete="off">

                                <div class="text-danger mt-1 job_{{ $lang['code'] }}"></div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Бележки и кратка информация -->
                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <label for="notes_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.member_notes') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="notes_{{ $lang['code'] }}"
                                          id="notes_{{ $lang['code'] }}"></textarea>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.secretary-council.update', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
