<div class="modal fade" id="modal-edit-meeting-decisions" aria-hidden="true">
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
                <form method="POST" name="EDIT_MEETING_DECISIONS_FORM" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="advisory_board_meeting_decision_id" value=""/>

                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="date_of_meeting">
                                    {{ __('validation.attributes.date_of_meeting') }}:
                                    <span class="required">*</span></label>
                                <input type="text" class="form-control form-control-sm datepicker"
                                       value="" id="date_of_meeting" name="date_of_meeting"/>
                            </div>

                            <div class="ajax-error text-danger mt-1 error_date_of_meeting"></div>
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

                            <div class="ajax-error text-danger mt-1 error_agenda"></div>
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

                            <div class="ajax-error text-danger mt-1 error_protocol"></div>
                        </div>
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMeetingDecision::translationFieldsProperties(), 'field' => 'decisions'])
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMeetingDecision::translationFieldsProperties(), 'field' => 'suggestions'])
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMeetingDecision::translationFieldsProperties(), 'field' => 'other'])
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitAjax(this, '{{ route('admin.advisory-boards.decisions.store', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.update') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
