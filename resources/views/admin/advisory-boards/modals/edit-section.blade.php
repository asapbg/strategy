<div class="modal fade" id="modal-edit-section" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.edit_of') . ' ' . Str::lower(trans_choice('custom.section', 1)) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="SECTION_UPDATE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="section_id" value=""/>

                    <div class="row mb-2">
                        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\AdvisoryBoardCustom::translationFieldsProperties(), 'field' => 'title', 'required' => true])
                    </div>

                    <div class="row mb-2">
                        @include('admin.partial.edit_field_translate', ['item' => null, 'translatableFields' => \App\Models\AdvisoryBoardCustom::translationFieldsProperties(), 'field' => 'body', 'required' => false])
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="updateAjax(this, '{{ route('admin.advisory-boards.sections.update', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.update') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
