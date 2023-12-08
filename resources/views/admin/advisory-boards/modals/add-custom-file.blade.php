<div class="modal fade" id="modal-add-custom-file" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{ __('custom.add') . ' ' . trans_choice('custom.file', 1) }}
                </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="CUSTOM_FILE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="object_id"/>
                    <input type="hidden" name="doc_type_id"
                           value="{{ \App\Enums\DocTypesEnum::AB_CUSTOM_SECTION->value }}"/>

                    @includeIf('admin.partial.file_fields', ['form' => 'CUSTOM_FILE'])
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('custom.cancel') }}</button>
                <button type="button" class="btn btn-success"
                        onclick="submitFileAjax(this, '{{ route('admin.advisory-boards.file.store', ['item' => $item]) }}')">
                    <span class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">{{ __('custom.add') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
