<div class="modal fade" id="modal-edit-member" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' '}} <span id="member-title">{{ trans_choice('custom.chairmen', 1) }}</span>
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="MEMBER_FORM_EDIT" class="pull-left">
                    @csrf

                    <input type="hidden" name="advisory_type_id" id="advisory_type_id" value="0"/>
                    <input type="hidden" name="advisory_board_id" value="{{ $item->id }}"/>
                    <input type="hidden" name="advisory_board_member_id" value=""/>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMember::translationFieldsProperties(), 'field' => 'member_name', 'required' => true])
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="member_institution">
                                            {{ trans_choice('custom.institution', 1) }}
                                        </label>

                                        <select id="member_institution" name="institution_id"
                                                class="form-control form-control-sm select2-no-clear">
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
                        </div>
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMember::translationFieldsProperties(), 'field' => 'member_job'])
                    </div>

                    <div class="row">
                        @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\AdvisoryBoardMember::translationFieldsProperties(), 'field' => 'member_notes'])
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
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.members.update', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.update') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            function controlMemberCheckbox(){
                if($('#advisory_type_secretary').is(':checked')) {
                    $('#member-checkbox').show();
                } else{
                    $('#member-checkbox input').prop('checked', false);
                    $('#member-checkbox').hide();
                }
            }

            $('#advisory_type_secretary').change(function (){
                controlMemberCheckbox();
            });

            controlMemberCheckbox();
        });
    </script>
@endpush
