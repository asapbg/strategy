<div class="modal fade" id="modal-add-secretariat-file" aria-hidden="true">
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
                <form method="POST" name="SECRETARIAT_FILE" enctype="multipart/form-data" class="pull-left">
                    @csrf

                    <input type="hidden" name="doc_type_id" value="{{ \App\Enums\DocTypesEnum::AB_SECRETARIAT->value }}"/>

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="file_{{ $lang['code'] }}">{{ __('custom.file') }}
                                        ({{ Str::upper($lang['code']) }})
                                        <span class="required">*</span>
                                    </label>

                                    <div class="row">
                                        <div class="col-12">
                                            <label for="" class="btn btn-outline-secondary" onclick="SECRETARIAT_FILE.querySelector('input[id=file_{{ $lang['code'] }}]').click()">{{ __('custom.select_file') }}</label>
                                            <input name="file_{{ $lang['code'] }}" class="d-none" type="file" id="file_{{ $lang['code'] }}" onchange="attachDocFileName(this)">
                                            <span class="document-name"></span>
                                        </div>
                                    </div>

                                    <div class="text-danger mt-1 error_file_{{ $lang['code'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="file_name_{{ $lang['code'] }}">{{ __('custom.name') }}
                                        ({{ Str::upper($lang['code']) }})
                                        <span class="required">*</span>
                                    </label>

                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control form-control-sm"
                                                   id="file_name_{{ $lang['code'] }}" type="text"
                                                   name="file_name_{{ $lang['code'] }}">
                                        </div>
                                    </div>

                                    <div class="text-danger mt-1 error_file_name_{{ $lang['code'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        @foreach(config('available_languages') as $lang)
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"
                                           for="file_description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                        ({{ Str::upper($lang['code']) }})
                                    </label>

                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control form-control-sm"
                                                   id="file_description_{{ $lang['code'] }}"
                                                   type="text"
                                                   name="file_description_{{ $lang['code'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
