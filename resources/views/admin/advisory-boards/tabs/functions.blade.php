<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.function', 1) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" onclick="ADVISORY_BOARD_FUNCTION.submit();">
                    {{ __('custom.save') . ' ' . trans_choice('custom.section', 1) }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <form name="ADVISORY_BOARD_FUNCTION" action="{{ route('admin.advisory-boards.function.store', $item) }}"
                      method="post">
                    @csrf

                    @foreach(config('available_languages') as $lang)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description_{{ $lang['code'] }}">{{ __('custom.description') }}
                                    ({{ Str::upper($lang['code']) }})</label>

                                @php
                                    $description = $function?->translations->count() === 2 ?
                                        $function->translations->first(fn($row) => $row->locale == $lang['code'])->description :
                                        old('description_' . $lang['code'], '');
                                @endphp

                                <textarea class="form-control form-control-sm summernote"
                                          name="description_{{ $lang['code'] }}"
                                          id="description_{{ $lang['code'] }}">
                                    {{ $description }}
                                </textarea>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <hr/>
            </div>
        </div>

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h3>{{ trans_choice('custom.files', 2) }}</h3>
                    </div>

                    <div class="col-auto">
                        <div class="custom-control custom-switch">
                            @php $checked = request()->get('show_deleted_functions_files', '0') == '1' ? 'checked' : '' @endphp
                            <input type="checkbox" class="custom-control-input"
                                   id="show-deleted-function-files" {{ $checked }} onchange="toggleDeletedFiles(this, 'functions')">
                            <label class="custom-control-label"
                                   for="show-deleted-function-files">{{ __('custom.show') . ' ' . mb_strtolower(__('custom.all_deleted')) }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal-add-function-file">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') . ' ' . __('custom.file') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @include('admin.partial.files_table', ['files' => $function_files, 'item' => $item])
            </div>
        </div>
    </div>
</div>
