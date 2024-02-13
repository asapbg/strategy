@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">

                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                            @foreach(\App\Http\Controllers\Admin\StrategicDocumentsController::SECTIONS as $s)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.strategic_documents.edit', [$item->strategicDocument, $s]) }}">{{ __('custom.strategic_documents.sections.'.$s) }}</a>
                                </li>
                            @endforeach
                                <li class="nav-item">
                                    <button class="nav-link add_sd_document bg-success" data-url="{{ route('admin.strategic_documents.document.popup', ['sd' => $item->strategicDocument]) }}">+ {{ trans_choice('custom.strategic_documents.documents', 1) }}</button>
                                </li>
                            @if(isset($sdDocuments) && $sdDocuments->count())
                                @foreach($sdDocuments as $d)
                                    <li class="nav-item">
                                        <a class="nav-link @if($d->id == $item->id) active @endif" href="{{ route('admin.strategic_documents.document.edit', [$d]) }}">{{ $d->title }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="card-body p-0">
                        <div class="row mt-4">
                            <h3 class="custom-left-border col-12">{{ $item->title }}</h3>
                        </div>
                        <form class="row mt-2" data-url="{{ route('admin.strategic_documents.document.update') }}">
                            <meta name="csrf-token" content="{{ csrf_token() }}"/>
                            <div class="row">
                                <div class="col-12 text-danger main-error"></div>
                                <div class="col-12 bg-success main-success mb-2"></div>
                            </div>
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="sd" value="{{ $item->strategicDocument->id }}">
                            @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'title', 'required' => true])
                            @include('admin.partial.edit_field_translate', ['translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(),'field' => 'description', 'required' => true])
                            <div class="form-group row">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="button" class="btn btn-success edit-sd-document">{{ __('custom.save') }}</button>
                                    @can('delete', $item->strategicDocument)
                                        <a href="javascript:;"
                                           class="btn btn-sm btn-danger js-toggle-delete-resource-modal"
                                           data-target="#modal-delete-resource"
                                           data-resource-id="{{ $item->id }}"
                                           data-resource-name="{{ $item->id }} ({{ $item->title }})"
                                           data-resource-delete-url="{{ route('admin.strategic_documents.document.delete', $item) }}"
                                           data-toggle="tooltip"
                                           title="{{ __('custom.delete') }}">{{ __('custom.delete') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </form>

                        <div class="row mt-4">
                            <h3 class="custom-left-border col-12">Файлове</h3>
                            <form class="row sd-form-files" data-extension="{{ implode(',', \App\Models\File::ALLOWED_FILE_STRATEGIC_DOC) }}" data-size="{{ (config('filesystems.max_upload_file_size') * 1024) }}" action="{{ route('admin.upload.file.languages', ['object_id' => $item->id, 'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="formats" value="ALLOWED_FILE_STRATEGIC_DOC">
                                @php($defaultLang = config('app.default_lang'))
                                @foreach(config('available_languages') as $lang)
                                    <div class="col-md-6 mb-3">
                                        <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}
{{--                                            @if($lang['code'] == $defaultLang)--}}
{{--                                                <span class="required">*</span>--}}
{{--                                            @endif--}}
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
{{--                                            @if($lang['code'] == $defaultLang)--}}
{{--                                                <span class="required">*</span>--}}
{{--                                            @endif--}}
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
                            @if($item->files)
                                <table class="table table-sm table-hover table-bordered mt-4">
                                    <tbody>
                                    <tr>
                                        <th>{{ __('custom.name') }}</th>
                                        <th>Видим в репорти</th>
                                        <th></th>
                                    </tr>
                                    @foreach($item->files as $f)
                                        <tr>
                                            <td>{{ $f->{'description_'.$f->locale} }} ({{ strtoupper($f->locale) }})</td>
                                            <td><i class="fas @if($f->is_visible) fa-check text-success @else fa-minus text-danger @endif"></i></td>
                                            <td>
                                                <a class="btn btn-sm btn-secondary" type="button" target="_blank" href="{{ route('admin.download.file', ['file' => $f->id]) }}">
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
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row my-4">
                            <h3 class="custom-left-border col-12">
                                {{ trans_choice('custom.strategic_documents.documents', 2) }}
                                <button class="btn btn-sm btn-success add_sd_document d-inline-block" data-url="{{ route('admin.strategic_documents.document.popup', [$item->strategicDocument, $item]) }}">+ Дъщерен документ</button>
                            </h3>
                        </div>
                        @if(isset($documentTree) && sizeof($documentTree) && sizeof($documentTree[0]->children))
                            <div class="row accordion" id="accordionExample">
                                @foreach($documentTree[0]->children as $doc)
                                    @include('admin.strategic_documents.documents.tree_element', ['doc' => $doc])
                                @endforeach
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12">Все още няма добавени дъшерни документи</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @includeIf('modals.delete-resource', ['resource' => $title_singular])
    </section>
@endsection

