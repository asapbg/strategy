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

                                <textarea class="form-control form-control-sm summernote"
                                          name="description_{{ $lang['code'] }}"
                                          id="description_{{ $lang['code'] }}">
                                {{ old('description_' . $lang['code'], '') }}
                                </textarea>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
