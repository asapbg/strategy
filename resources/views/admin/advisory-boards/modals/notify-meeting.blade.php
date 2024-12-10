<div class="modal fade" id="modal-notify-meeting" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.prepare_notify') }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="NOTIFY_MEETING" class="pull-left">
                    @csrf

                    <input type="hidden" name="meeting_id" value=""/>

                    <div class="form-check">
                        <div class="col-md-12">
                            <input type="checkbox" name="include_files" id="include_files" class="form-check-input" value="1" checked/>

                            <label class="form-check-label" for="include_files">
                                {{ __('custom.include_files') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 mt-3">
                            <label class="control-label"
                                   for="additional_information_link">{{ __('custom.additional_information_link') }}</label>

                            <input type="text" id="additional_information_link" name="additional_information_link"
                                   class="form-control" value=""/>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.meetings.send-notify', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.send') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
