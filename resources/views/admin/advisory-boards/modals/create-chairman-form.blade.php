<div class="modal fade" id="modal-create-chairman" aria-hidden="true">
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
                <form method="POST" name="CHAIRMAN_FORM" action="{{ route('admin.advisory-boards.members.store') }}"
                      class="pull-left mr-4">
                    @csrf

                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="name_bg">{{ __('custom.first_name') }} (BG) <span
                                        class="required">*</span></label>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" id="name_bg" name="name_bg"
                                               class="form-control form-control-sm @error('name_bg'){{ 'is-invalid' }}@enderror"
                                               value="" autocomplete="off"/>

                                        <div class="text-danger mt-1 error_name_bg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="name_en">{{ __('custom.first_name') }} (EN) <span
                                        class="required">*</span></label>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" id="name_en" name="name_en"
                                               class="form-control form-control-sm @error('name_bg'){{ 'is-invalid' }}@enderror"
                                               value="" autocomplete="off"/>

                                        <div class="text-danger mt-1 error_name_en"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label" for="advisory_type_id">
                                    {{ trans_choice('custom.type', 1) }}
                                    <span class="required">*</span>
                                </label>

                                <select id="advisory_type_id" name="advisory_type_id"
                                        class="form-control form-control-sm select2-no-clear">
                                    <option value="">---</option>
                                    @foreach(\App\Enums\AdvisoryTypeEnum::cases() as $case)
                                        @php $selected = old('advisory_type_id', '') == $case->value ? 'selected' : '' @endphp

                                        <option value="{{ $case->value }}" {{ $selected }}>
                                            {{ trans_choice('custom.' . Str::lower($case->name), 1) }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="text-danger mt-1 error_advisory_type_id"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label" for="advisory_chairman_type_id">
                                    {{ __('forms.job') }}
                                    <span class="required">*</span>
                                </label>

                                <select id="advisory_chairman_type_id" name="advisory_chairman_type_id"
                                        class="form-control form-control-sm select2-no-clear">
                                    <option value="">---</option>
                                    @if(isset($advisory_chairman_types) && $advisory_chairman_types->count() > 0)
                                        @foreach($advisory_chairman_types as $type)
                                            @php $selected = old('advisory_chairman_type_id', '') == $type->id ? 'selected' : '' @endphp

                                            <option
                                                value="{{ $type->id }}" {{ $selected }}>{{ $type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="text-danger mt-1 error_advisory_chairman_type_id"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="job_bg">
                                            {{ __('forms.job') }} (BG)
                                        </label>

                                        <input type="text" id="job_bg"
                                               name="job_bg"
                                               class="form-control form-control-sm"
                                               value="{{ old('job_bg', '') }}"/>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="job_en">
                                            {{ __('forms.job') }} (EN)
                                        </label>

                                        <input type="text" id="job_en"
                                               name="job_en"
                                               class="form-control form-control-sm"
                                               value="{{ old('job_en', '') }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label" for="consultation_level_id">
                                    {{ trans_choice('custom.representatives_from', 1) }}
                                    <span class="required">*</span>
                                </label>

                                <select id="consultation_level_id" name="consultation_level_id"
                                        class="form-control form-control-sm select2-no-clear">
                                    <option value="">---</option>
                                    @if(isset($consultation_levels) && $consultation_levels->count() > 0)
                                        @foreach($consultation_levels as $level)
                                            @php $selected = old('consultation_level_id', '') == $level->id ? 'selected' : '' @endphp

                                            <option
                                                value="{{ $level->id }}" {{ $selected }}>{{ $level->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="text-danger mt-1 error_consultation_level_id"></div>
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
            const form = document.querySelector('form[name=CHAIRMAN_FORM]');
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
                    const modal = new bootstrap.Modal(document.getElementById('modal-create-chairman'));

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

        /**
         * Change button state for ajax forms.
         * State can be either 'loading' or 'finished'.
         *
         * @param element
         * @param state
         */
        function changeButtonState(element, state = 'loading') {
            const button_text_translation = state === 'loading' ? @json(__('custom.loading')) : @json(__('custom.add'));
            const loader = element.querySelector('.spinner-grow');

            element.querySelector('.text').innerHTML = button_text_translation;
            element.disabled = state === 'loading';

            if (state === 'loading') {
                loader.classList.remove('d-none');
                return;
            }

            loader.classList.add('d-none');
        }
    </script>
@endpush
