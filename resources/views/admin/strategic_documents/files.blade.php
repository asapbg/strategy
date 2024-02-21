<form class="row" action="{{ route('admin.strategic_documents.upload.file.languages', ['object_id' => $item && $item->id ? $item->id : 0, 'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="formats" value="ALLOWED_FILE_STRATEGIC_DOC">
    @php($defaultLang = config('app.default_lang'))
    @foreach(config('available_languages') as $lang)
        <div class="col-md-6 mb-3">
            <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}
                @if($lang['code'] == $defaultLang)
                    <span class="required">*</span>
                @endif
            </label>
            <input value="{{ old('description_'.$lang['code'], '') }}" class="form-control form-control-sm @error('description_'.$lang['code']) is-invalid @enderror" id="description_{{ $lang['code'] }}" type="text" name="description_{{ $lang['code'] }}">
            @error('description_'.$lang['code'])
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
    @foreach(config('available_languages') as $lang)
        <div class="col-md-6 mb-3">
            <label for="file_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_'.$lang['code']) }}
                @if($lang['code'] == $defaultLang)
                    <span class="required">*</span>
                @endif
            </label>
            <input class="form-control form-control-sm @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
            @error('file_'.$lang['code'])
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
    <div class="col-md-4 d-none">
        <div class="form-group">
            <label for="textarea"><span class="d-none">*</span>
{{--                <span class="required">*</span>--}}
            </label>
            <div class="form-check">
                <input type="checkbox" name="is_visible_in_report" class="form-check-input" value="1" checked="" >
                <label class="form-check-label" for="is_visible_in_report">
                    Видим в репорти
                </label>
            </div>
        </div>
    </div>
    <div class="col-12"></div>
    <div class="col-md-4">
        <br>
        <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
    </div>
</form>
@if($item->files)
    <table class="table table-sm table-hover table-bordered mt-4">
        <tbody>
        <tr>
            <th>{{ __('custom.name') }}</th>
{{--            <th>Видим в репорти</th>--}}
            <th></th>
        </tr>
        @foreach($item->files as $f)
                    <tr>
                        <td>{{ $f->description }} ({{ strtoupper($f->locale) }})</td>
{{--                        <td><i class="fas @if($f->visible_in_report)  fa-check text-success @else fa-minus text-danger @endif"></i></td>--}}
                        <td>
{{--                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">--}}
                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('strategy-document.download-file', ['id' => $f->id]) }}">
                                <i class="fas fa-download me-1" role="button"
                                   data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                            </a>
                            <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f->id, 'disk' => 'public_uploads']).'?is_sd_file=1' }}">
                                <i class="fas fa-trash me-1" role="button"
                                   data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                            </a>
                        </td>
                    </tr>
        @endforeach
        </tbody>
    </table>
@endif
