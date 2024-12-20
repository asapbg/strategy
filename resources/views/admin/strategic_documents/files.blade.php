<form class="row" action="{{
    $strategicFile
        ? route('admin.strategic_documents.update.file.languages',
            ['object_id' => $item && $item->id ? $item->id : 0, 'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT, 'strategicFile' => $strategicFile])
        : route('admin.strategic_documents.upload.file.languages',
            ['object_id' => $item && $item->id ? $item->id : 0, 'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT, 'strategicFile' => null]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="formats" value="ALLOWED_FILE_STRATEGIC_DOC">
    @php
        $defaultLang = config('app.default_lang');

        $langs = config('available_languages');
        if ($strategicFile) {
            $langs = $strategicFile->locale == 'bg' ? [$langs[1]] : [$langs[2]];
        }
    @endphp
    <div class="row">
        @foreach($langs as $lang)
            <div class="col-md-6 mb-3">
                <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}
                    @if($lang['code'] == $defaultLang)
                        <span class="required">*</span>
                    @endif
                </label>
                <input value="{{ old('description_'.$lang['code'], $strategicFile->description ?? '') }}"
                       class="form-control form-control-sm @error('description_'.$lang['code']) is-invalid @enderror"
                       id="description_{{ $lang['code'] }}"
                       type="text"
                       name="description_{{ $lang['code'] }}">
                @error('description_'.$lang['code'])
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="row">
        @foreach($langs as $lang)
            <div class="col-md-6 mb-3">
                <label for="file_info_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_info_'.$lang['code']) }}
                </label>
                <textarea class="summernote"
                    name="file_info_{{ $lang['code'] }}" id="file_info_{{ $lang['code'] }}">
                    {!! old('file_info_'.$lang['code'], $strategicFile->file_info ?? '') !!}
                </textarea>
                @error('file_info_'.$lang['code'])
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="row">
        @foreach($langs as $lang)
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="file_{{ $lang['code'] }}" class="form-label col-sm-12">{{ __('validation.attributes.file_'.$lang['code']) }}
                        @if($lang['code'] == $defaultLang)
                            <span class="required">*</span>
                        @endif
                    </label>
                    <div class="col-12">
                        <input class="form-control form-control-sm col-sm-12 @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
                    </div>
                    @error('file_'.$lang['code'])
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>

    @if($strategicFile)
        <div class="row">
            <div class="col-4">
                <a class="btn" type="button" target="_blank" href="{{ route('strategy-document.download-file', ['id' => $strategicFile->id]) }}">
                    <i class="fas fa-download me-1" role="button" title="{{ __('custom.download') }}"></i>
                    {{ __('custom.download') }} {{ l_trans('custom.file') }}
                </a>
            </div>
        </div>
    @endif

    <div class="row">
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label class="col-sm-12 control-label"--}}
{{--                       for="strategic_document_type_id">{{ trans_choice('custom.strategic_document_type', 1) }}--}}
{{--                    <span class="required">*</span></label>--}}
{{--                <div class="col-12">--}}
{{--                    <select id="strategic_document_type_id" name="strategic_document_type_id"--}}
{{--                            class="form-control form-control-sm select2 @error('strategic_document_type_id'){{ 'is-invalid' }}@enderror">--}}
{{--                        @if(!$item->id)--}}
{{--                            <option value=""--}}
{{--                                    @if(old('strategic_document_type_id', '') == '') selected @endif>--}}
{{--                                -----}}
{{--                            </option>--}}
{{--                        @endif--}}
{{--                        @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())--}}
{{--                            @foreach($strategicDocumentTypes as $row)--}}
{{--                                @if($row->active || ($item && $item->strategic_document_type_id == $row->id))--}}
{{--                                    <option value="{{ $row->id }}"--}}
{{--                                            @if(old('strategic_document_type_id', '') == $row->id) selected--}}
{{--                                            @endif data-id="{{ $row->id }}">{{ $row->name }}</option>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    </select>--}}
{{--                    @error('strategic_document_type_id')--}}
{{--                    <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--                    @enderror--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label class="col-sm-12 control-label"--}}
{{--                       for="document_date_pris">{{ __('custom.valid_at') }}</label>--}}
{{--                <div class="col-12">--}}
{{--                    <input type="text" id="valid_at" name="valid_at"--}}
{{--                           class="form-control form-control-sm datepicker @error('valid_at'){{ 'is-invalid' }}@enderror"--}}
{{--                           value="{{ old('valid_at', '') }}">--}}
{{--                    @error('valid_at')--}}
{{--                    <div class="text-danger mt-1">{{ $message }}</div>--}}
{{--                    @enderror--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

    <div class="row ml-2">
{{--        <div class="col-md-4">--}}
{{--            <div class="form-group">--}}
{{--                <label for="textarea"><span class="d-none">*</span>--}}
{{--    --}}{{--                <span class="required">*</span>--}}
{{--                </label>--}}
{{--                <div class="form-check">--}}
{{--                    <input type="checkbox" name="is_visible_in_report" class="form-check-input" value="1" checked="" >--}}
{{--                    <label class="form-check-label" for="is_visible_in_report">--}}
{{--                        Видим в репорти--}}
{{--                    </label>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <div class="col-12"></div>
    <div class="row ml-1">
        <div class="col-md-4">
            <br>
            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
        </div>
    </div>
</form>
@if($item->files && !$strategicFile)
    <table class="table table-sm table-hover table-bordered mt-4">
        <tbody>
        <tr>
            <th>{{ __('custom.name') }}</th>
{{--            <th>Видим в репорти</th>--}}
            <th></th>
        </tr>
        @foreach($item->files as $f)
                    <tr>
                        <td>{{ $f->description ?? $f->filename }} ({{ strtoupper($f->locale) }})</td>
{{--                        <td><i class="fas @if($f->visible_in_report)  fa-check text-success @else fa-minus text-danger @endif"></i></td>--}}
                        <td>
{{--                            <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">--}}
                            <a class="btn btn-sm btn-success"
                                href="{{ route('admin.strategic_documents.edit', ['id' => $item->id, 'section' => \App\Http\Controllers\Admin\StrategicDocumentsController::SECTION_FILES, 'strategicFile' => $f]) }}">
                                <i class="fas fa-edit me-1"></i>
                            </a>
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
