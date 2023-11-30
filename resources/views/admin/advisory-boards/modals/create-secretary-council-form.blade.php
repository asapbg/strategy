<div class="modal fade" id="modal-create-secretary-of-council" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.secretary', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="SECRETARY_OF_COUNCIL_FORM"
                      action=""
                      class="pull-left mr-4">
                    @csrf

                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>

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
                        onclick="submitSecretaryOfCouncilAjax(this)">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        function submitSecretaryOfCouncilAjax(element) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = document.querySelector('form[name=SECRETARY_OF_COUNCIL_FORM]');
            const formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.advisory-boards.secretary-council.store', $item) }}",
                data: formData,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (result) {
                    // Get a reference to the modal
                    const modal = new bootstrap.Modal(document.getElementById('modal-create-secretary-of-council'));

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
