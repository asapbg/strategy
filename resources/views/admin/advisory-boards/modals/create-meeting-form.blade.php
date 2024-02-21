<div class="modal fade" id="modal-create-meeting" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.meetings', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="MEETING_FORM"
                      action=""
                      class="pull-left">
                    @csrf

                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="next_meeting">
                                    {{ __('validation.attributes.next_meeting') }}:
                                    <span class="required">*</span></label>
                                <input type="text" data-provide="datepicker" class="form-control form-control-sm datepicker"
                                       value="" id="next_meeting" name="next_meeting">
                            </div>
                            <div class="ajax-error text-danger mt-1 error_next_meeting"></div>
                        </div>
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMeeting::translationFieldsProperties(), 'field' => 'description', 'required' => true])
                    </div>
{{--                    <!-- Описание -->--}}
{{--                    <div class="row mb-2">--}}
{{--                        @foreach(config('available_languages') as $lang)--}}
{{--                            <div class="col-6">--}}
{{--                                <label for="description_{{ $lang['code'] }}">--}}
{{--                                    {{ __('validation.attributes.description') }}--}}
{{--                                    ({{ Str::upper($lang['code']) }})--}}
{{--                                </label>--}}

{{--                                <textarea class="form-control form-control-sm summernote"--}}
{{--                                          name="description_{{ $lang['code'] }}"--}}
{{--                                          id="description_{{ $lang['code'] }}"></textarea>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitMeetingAjax(this)">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        function submitMeetingAjax(element) {
            // change button state
            changeButtonState(element);
            clearErrorMessages();

            // Get the form element
            const form = document.querySelector('form[name=MEETING_FORM]');
            const formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                },
                url: "{{ route('admin.advisory-boards.meetings.store', $item) }}",
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
                            form.querySelector(search_class).textContent = errors[i][1][0];
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
