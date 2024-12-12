<div class="modal fade" id="modal-create-member" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >
                    {{ __('custom.add') . ' '}} <span id="member-title">{{ trans_choice('custom.chairmen', 1) }}</span>
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="MEMBER_FORM" action="{{ route('admin.advisory-boards.members.store') }}"
                      class="pull-left">
                    @csrf

                    <input type="hidden" name="advisory_type_id" id="advisory_type_id" value="0"/>
                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMember::translationFieldsProperties(), 'field' => 'member_name', 'required' => true])
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
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="institution_id">
                                    {{ trans_choice('custom.institution', 1) }}
                                </label>

                                <div class="col-md-12">
                                    <select id="institution_id" name="institution_id"
                                            class="form-control select2-no-clear col-sm-12">
                                        <option value="">---</option>
                                        @if(isset($institutions) && $institutions->count() > 0)
                                            @foreach($institutions as $institution)
                                                <option
                                                    value="{{ $institution->id }}">{{ $institution->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <div class="text-danger mt-1 error_institution_id"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="email">
                                    {{ trans_choice('custom.email', 1) }}
                                </label>

                                <div class="col-md-12">
                                    <input type="email" id="email" name="email" class="form-control col-sm-12"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMember::translationFieldsProperties(), 'field' => 'member_job'])
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMember::translationFieldsProperties(), 'field' => 'member_notes'])
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
        let memberNames = {
        <?php echo \App\Enums\AdvisoryTypeEnum::MEMBER->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::MEMBER->value, 1) ;?>",
        <?php echo \App\Enums\AdvisoryTypeEnum::CHAIRMAN->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::CHAIRMAN->value, 1) ;?>",
        <?php echo \App\Enums\AdvisoryTypeEnum::VICE_CHAIRMAN->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::VICE_CHAIRMAN->value, 1) ;?>",
        <?php echo \App\Enums\AdvisoryTypeEnum::SECRETARY->value; ?> : "<?php echo trans_choice('custom.adv_members.'.\App\Enums\AdvisoryTypeEnum::SECRETARY->value, 1) ;?>",
        };

        $('#modal-create-member').on('show.bs.modal', function(event) {
            $(this).find('#institution_id').select2();

            // get # from window location
            const tab = window.location.hash;
            const form = document.querySelector('form[name=MEMBER_FORM]');

            if (tab === '#secretary' && form.querySelector('input[name=is_advisory_board_member]') == null) {
                attachCheckboxToForm(form, 'is_advisory_board_member', @json(trans_choice('custom.member', 1) . ' ' . __('custom.of') . ' ' . Str::lower(__('validation.attributes.adv_board'))), false);
            }

            if (tab !== '#secretary' && form.querySelector('input[name=is_advisory_board_member]') != null) {
                removeCheckboxFromForm(form, 'is_advisory_board_member');
            }

            $('#modal-create-member #member-title').html(memberNames[$(event.relatedTarget).data('type')]);
            $('#modal-create-member #advisory_type_id').val($(event.relatedTarget).data('type'));
        });

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

        /**
         * Clear all errors from previous ajax.
         * By default it will clear all elements with class ajax-error
         *
         * @param className
         */
        function clearErrorMessages(className = 'ajax-error') {
            document.querySelectorAll('.' + className).forEach(function(el) {
                el.innerHTML = '';
            });
        }

        function submitMemberAjax(element) {
            // change button state
            changeButtonState(element);
            clearErrorMessages();

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
                    // const modal = new bootstrap.Modal(document.getElementById('modal-create-member'));

                    // Hide the modal
                    // modal.hide();
                    //
                    // window.location.reload();

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
