<div class="modal fade" id="modal-create-member" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.chairmen', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="MEMBER_FORM" action="{{ route('admin.advisory-boards.members.store') }}"
                      class="pull-left">
                    @csrf

                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="member_name_bg">{{ __('custom.first_name') }} (BG) <span
                                            class="required">*</span></label>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" id="member_name_bg" name="member_name_bg"
                                               class="form-control form-control-sm"
                                               value="" autocomplete="off"/>

                                        <div class="text-danger mt-1 error_member_name_bg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="member_name_en">{{ __('custom.first_name') }} (EN) <span
                                            class="required">*</span></label>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" id="member_name_en" name="member_name_en"
                                               class="form-control form-control-sm"
                                               value="" autocomplete="off"/>

                                        <div class="text-danger mt-1 error_member_name_en"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-12 control-label"
                               for="member_name_en">{{ __('custom.type') }}</label>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-check pl-4">
                                        <input class="form-check-input" type="radio"
                                               value="{{ \App\Enums\AdvisoryTypeEnum::MEMBER->value }}"
                                               name="advisory_type_id"
                                               id="advisory_type_member" checked/>
                                        <label class="form-check-label" id="" for="advisory_type_member">
                                            {{ trans_choice('custom.member', 1) }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="form-check pl-4">
                                        <input class="form-check-input" type="radio"
                                               value="{{ \App\Enums\AdvisoryTypeEnum::CHAIRMAN->value }}"
                                               name="advisory_type_id"
                                               id="advisory_type_chairman"/>
                                        <label class="form-check-label" id="" for="advisory_type_chairman">
                                            {{ __('custom.chairman') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="form-check pl-4">
                                        <input class="form-check-input" type="radio"
                                               value="{{ \App\Enums\AdvisoryTypeEnum::VICE_CHAIRMAN->value }}"
                                               name="advisory_type_id"
                                               id="advisory_type_vice_chairman"/>
                                        <label class="form-check-label" id="" for="advisory_type_vice_chairman">
                                            {{ __('validation.attributes.vice_chairman') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="form-check pl-4">
                                        <input class="form-check-input" type="radio"
                                               value="{{ \App\Enums\AdvisoryTypeEnum::SECRETARY->value }}"
                                               name="advisory_type_id"
                                               id="advisory_type_secretary"/>
                                        <label class="form-check-label" id="" for="advisory_type_secretary">
                                            {{ trans_choice('custom.secretary', 1) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2" id="member-checkbox">
                        <div class="col-12">
                            <div class="form-check pl-4">
                                <input class="form-check-input" type="checkbox"
                                       value="{{ \App\Enums\AdvisoryTypeEnum::MEMBER->value }}"
                                       name="is_member"
                                />
                                <label class="form-check-label" id="" for="is_member">
                                    {{ trans_choice('custom.member', 1) }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="institution_id">
                                            {{ trans_choice('custom.institution', 1) }}
                                        </label>

                                        <select id="institution_id" name="institution_id"
                                                class="form-control form-control-sm select2-no-clear">
                                            <option value="">---</option>
                                            @if(isset($institutions) && $institutions->count() > 0)
                                                @foreach($institutions as $institution)
                                                    <option
                                                        value="{{ $institution->id }}">{{ $institution->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="member_job_bg">
                                            {{ __('forms.job') }} (BG)
                                        </label>

                                        <input type="text" id="member_job_bg"
                                               name="member_job_bg"
                                               class="form-control form-control-sm"/>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="member_job_en">
                                            {{ __('forms.job') }} (EN)
                                        </label>

                                        <input type="text" id="member_job_en"
                                               name="member_job_en"
                                               class="form-control form-control-sm"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-6">
                                <label for="member_notes_{{ $lang['code'] }}">
                                    {{ __('validation.attributes.member_notes') }}
                                    ({{ Str::upper($lang['code']) }})
                                </label>

                                <textarea class="form-control form-control-sm summernote"
                                          name="member_notes_{{ $lang['code'] }}"
                                          id="member_notes_{{ $lang['code'] }}"></textarea>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label" for="email">
                                    {{ trans_choice('custom.email', 1) }}
                                </label>

                                <input type="email" id="email"
                                       name="email"
                                       class="form-control form-control-sm"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitMemberAjax(this)">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        function submitMemberAjax(element) {
            // change button state
            changeButtonState(element);

            // Get the form element
            const form = document.querySelector('form[name=MEMBER_FORM]');
            const formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.advisory-boards.members.store') }}",
                data: formData,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (result) {
                    // Get a reference to the modal
                    const modal = new bootstrap.Modal(document.getElementById('modal-create-member'));

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
