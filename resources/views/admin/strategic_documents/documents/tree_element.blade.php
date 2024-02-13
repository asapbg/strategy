@php($dItem = \App\Models\StrategicDocumentChildren::find($doc->id))
@php($translation = json_decode($doc->translations, true))
@php($defaultTranslation = array_filter($translation, function ($el){ return $el['locale'] == config('app.default_lang'); }))
@php($docFiles = json_decode($doc->files, true))

<div class="col-12" @if(isset($doc->level) && $doc->level) style="padding-left: {{ ($doc->level * 3).'0px' }};" @endif>
    <div class="card custom-card">
        <div class="card-header" id="heading-doc{{ $doc->id }}">
            <h2 class="mb-0">
                <button class="px-0 btn text-decoration-none fs-18 btn-link btn-block text-start collapsed" type="button" data-toggle="collapse" data-target="#doc{{ $doc->id }}" aria-expanded="false" aria-controls="doc{{ $doc->id }}">
                    @if(isset($doc->level) && $doc->level)
                        <i class="me-1 fas fa-sign-in-alt main-color fs-18"></i>
                    @else
                        <i class="me-1 fas fa-grip-lines-vertical main-color fs-18"></i>
                    @endif
                    {{ $defaultTranslation[0]['title'] }}
                </button>
            </h2>
        </div>
        <div class="card-body collapse" id="doc{{ $doc->id }}" aria-labelledby="heading-doc{{ $doc->id }}" data-parent="#accordionExample">
            <button class="btn btn-sm btn-success add_sd_document d-inline-block mb-3" data-url="{{ route('admin.strategic_documents.document.popup', [$doc->sd_id, $doc->id]) }}">+ Дъщерен документ</button>
            <form class="row mt-2" data-url="{{ route('admin.strategic_documents.document.update') }}" >
                <meta name="csrf-token" content="{{ csrf_token() }}"/>
                <div class="row">
                    <div class="col-12 text-danger main-error"></div>
                    <div class="col-12 bg-success main-success mb-2"></div>
                </div>
                <input type="hidden" name="id" value="{{ $doc->id }}">
                <input type="hidden" name="sd" value="{{ $doc->sd_id }}">
                @include('admin.partial.edit_field_translate', ['item' => $dItem, 'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'title', 'required' => true])
                @include('admin.partial.edit_field_translate', ['item' => $dItem,'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(),'field' => 'description', 'required' => true])
                <div class="form-group row">
                    <div class="col-md-6 col-md-offset-3">
                        <button id="save" type="button" class="btn btn-success edit-sd-document">{{ __('custom.save') }}</button>
                        @if(isset($canDeleteSd) && $canDeleteSd)
                            <a href="javascript:;"
                               class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                               data-target="#modal-delete-resource"
                               data-resource-id="{{ $doc->id }}"
                               data-resource-name="{{ $doc->id }} ({{ $defaultTranslation[0]['title'] }})"
                               data-resource-delete-url="{{ route('admin.strategic_documents.document.delete', $dItem) }}"
                               data-toggle="tooltip"
                               title="{{ __('custom.delete') }}">{{ __('custom.delete') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </form>

            <div class="row mt-4">
                <h3 class="custom-left-border col-12">Файлове</h3>
                <form class="row sd-form-files" data-extension="{{ implode(',', \App\Models\File::ALLOWED_FILE_STRATEGIC_DOC) }}" data-size="{{ (config('filesystems.max_upload_file_size') * 1024) }}"  action="{{ route('admin.upload.file.languages', ['object_id' => $doc->id, 'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="formats" value="ALLOWED_FILE_STRATEGIC_DOC">
                    @php($defaultLang = config('app.default_lang'))
                    @foreach(config('available_languages') as $lang)
                        <div class="col-md-6 mb-3">
                            <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}
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
                            </label>
                            <input class="form-control form-control-sm @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
                            @error('file_'.$lang['code'])
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="textarea"><span class="d-none">*</span>
                                {{--                <span class="required">*</span>--}}
                            </label>
                            <div class="form-check">
                                <input type="checkbox" name="is_visible" class="form-check-input" value="1" @if(old('is_visible', 0)) checked="" @endif>
                                <label class="form-check-label" for="is_visible">
                                    Видим в репорти
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12"></div>
                    <div class="col-md-4">
                        <br>
                        <button id="save" type="button" class="btn btn-success sd-submit-files">{{ __('custom.save') }}</button>
                    </div>
                </form>
                @if(isset($docFiles) && sizeof($docFiles))
                    <table class="table table-sm table-hover table-bordered mt-4">
                        <tbody>
                        <tr>
                            <th>{{ __('custom.name') }}</th>
                            <th>Видим в репорти</th>
                            <th></th>
                        </tr>
                        @foreach($docFiles as $f)
                            @if($f['id'])
                                <tr>
                                    <td>{{ $f['description_'.$f['locale']] }} ({{ strtoupper($f['locale']) }})</td>
                                    <td><i class="fas @if($f['is_visible']) fa-check text-success @else fa-minus text-danger @endif"></i></td>
                                    <td>
                                        <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f['id']]) }}">
                                            <i class="fas fa-download me-1" role="button"
                                               data-toggle="tooltip" title="{{ __('custom.download') }}"></i>
                                        </a>
                                        <a class="btn btn-sm btn-danger" type="button" href="{{ route('admin.delete.file', ['file' => $f['id'], 'disk' => 'public_uploads']).'?is_sd_file=1' }}">
                                            <i class="fas fa-trash me-1" role="button"
                                               data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    @if(isset($doc->children) && sizeof($doc->children))
        <div class="row accordion" id="accordionExample">
            @foreach($doc->children as $doc)
                @include('admin.strategic_documents.documents.tree_element', ['doc' => $doc])
            @endforeach
        </div>
    @endif
</div>
