<form class="row sd-form-files" id="fileform_{{ isset($item) ? $item->id : 0 }}" data-extension="{{ implode(',', \App\Models\File::ALLOWED_FILE_STRATEGIC_DOC) }}"
      action="{{
        isset($fileRecord)
        ? route('admin.update.file.languages', [
            'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN,
            'fileRecord' => $fileRecord
            ])
        : route('admin.upload.file.languages', [
            'object_id' => $item->id,
            'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN
            ])
        }}"
      method="post" name="form" id="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="formats" value="ALLOWED_FILE_STRATEGIC_DOC">
    @php($defaultLang = config('app.default_lang'))
    @foreach((isset($fileRecord) ? [['code' => $fileRecord->locale]] : config('available_languages')) as $lang)
        <div class="col-md-6 mb-3">
            <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}
                {{--                                            @if($lang['code'] == $defaultLang)--}}
                {{--                                                <span class="required">*</span>--}}
                {{--                                            @endif--}}
            </label>
            <input value="{{ old('description_'.$lang['code'], (isset($fileRecord) ? $fileRecord->{'description_'.$lang['code']} : '')) }}" class="form-control form-control-sm @error('description_'.$lang['code']) is-invalid @enderror" id="description_{{ $lang['code'] }}" type="text" name="description_{{ $lang['code'] }}">
            @error('description_'.$lang['code'])
            <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="ajax-error text-danger mt-1 error_{{ 'description_'.$lang['code'] }}"></div>
        </div>
    @endforeach
    @foreach((isset($fileRecord) ? [['code' => $fileRecord->locale]] : config('available_languages')) as $lang)
        <div class="col-md-6 mb-3">
            <label for="file_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_'.$lang['code']) }}
                {{--                                            @if($lang['code'] == $defaultLang)--}}
                {{--                                                <span class="required">*</span>--}}
                {{--                                            @endif--}}
            </label>
            <input class="form-control form-control-sm @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
            @error('file_'.$lang['code'])
            <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="ajax-error text-danger mt-1 error_{{ 'file_'.$lang['code'] }}"></div>

            @isset($fileRecord)
                <div class="row">
                    <div class="col-4">
                        <a class="btn" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $fileRecord->id]) }}">
                            <i class="fas fa-download me-1" role="button" title="{{ __('custom.download') }}"></i>
                            {{ __('custom.download') }} {{ l_trans('custom.file') }}
                        </a>
                    </div>
                </div>
            @endif

        </div>
    @endforeach
    <div class="col-md-4 d-none">
        <div class="form-group">
            <label for="textarea"><span class="d-none">*</span>
                {{--                <span class="required">*</span>--}}
            </label>
            <div class="form-check">
                <input type="checkbox" name="is_visible" class="form-check-input" value="1" checked="">
                <label class="form-check-label" for="is_visible">
                    Видим в репорти
                </label>
            </div>
        </div>
    </div>

    <div class="col-12"></div>
    <div class="col-md-4">
        <br>
        @isset($fileRecord)
            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
        @else
            <button id="save" type="button" class="btn btn-success sd-submit-files">{{ __('custom.save') }}</button>
        @endisset
    </div>
</form>
