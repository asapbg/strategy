@php
    $form ??= '';
@endphp

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
                        <label for="" class="btn btn-outline-secondary"
                               onclick="{{ $form }}.querySelector('input[id=file_{{ $lang['code'] }}]').click()">{{ __('custom.select_file') }}</label>
                        <input name="file_{{ $lang['code'] }}" class="d-none" type="file" id="file_{{ $lang['code'] }}"
                               onchange="attachDocFileName(this)">
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

<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label class="col-sm-12 control-label"
                   for="resolution_council_ministers">{{ __('validation.attributes.resolution_council_matters') }}
            </label>

            <div class="row">
                <div class="col-12">
                    <input class="form-control form-control-sm"
                           id="resolution_council_ministers" type="text"
                           name="resolution_council_ministers"/>
                </div>
            </div>

            <div class="text-danger mt-1 error_resolution_council_ministers"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label class="col-sm-12 control-label"
                   for="state_newspaper">{{ __('validation.attributes.state_newspaper') }}
            </label>

            <div class="row">
                <div class="col-12">
                    <input class="form-control form-control-sm"
                           id="state_newspaper" type="text"
                           name="state_newspaper"/>
                </div>
            </div>

            <div class="text-danger mt-1 error_state_newspaper"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label class="col-sm-12 control-label"
                   for="effective_at">{{ __('validation.attributes.effective_at') }}
            </label>

            <input type="text" id="effective_at" name="effective_at"
                   class="datepicker form-control form-control-sm @error('effective_at'){{ 'is-invalid' }}@enderror"/>

            <div class="text-danger mt-1 error_effective_at"></div>
        </div>
    </div>
</div>
