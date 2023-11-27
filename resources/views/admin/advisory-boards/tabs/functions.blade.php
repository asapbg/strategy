<div class="tab-content">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.section', 1) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" onclick="ADVISORY_BOARD_FUNCTION.submit();">
                    {{ __('custom.save') }}
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
                                    $description = $function->translations->count() === 2 ?
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

        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <h3>{{ trans_choice('custom.files', 2) }}</h3>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-add-function-file">
                    <i class="fa fa-plus mr-3"></i>
                    {{ __('custom.add') }}
                </button>
            </div>
        </div>
    </div>
</div>
