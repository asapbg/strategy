@php($dItem = \App\Models\StrategicDocumentChildren::find($doc->id))
@php($translation = json_decode($doc->translations, true))
@php($defaultTranslation = array_filter($translation, function ($el){ return $el['locale'] == config('app.default_lang'); }))
@php($defaultTranslation = array_values($defaultTranslation))
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
                <input type="hidden" id="strategic_document_level_id" name="strategic_document_level_id" value="{{ $doc->strategic_document_level_id }}">
{{--                <input type="hidden" id="accept_act_institution_type_id" name="accept_act_institution_type_id" value="{{ $doc->accept_act_institution_type_id }}">--}}
                @include('admin.partial.edit_field_translate', ['item' => $dItem, 'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(), 'field' => 'title', 'required' => true])
                @include('admin.partial.edit_field_translate', ['item' => $dItem,'translatableFields' => \App\Models\StrategicDocumentChildren::translationFieldsProperties(),'field' => 'description', 'required' => true])
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">{{ trans_choice('custom.strategic_document_level', 1) }}</label>
                            <div class="col-12">
                                {{ $doc->strategic_document_level_id ? __('custom.strategic_document.dropdown.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($doc->strategic_document_level_id)) : '---' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="strategic_document_type_id">{{ trans_choice('custom.strategic_document_type', 1) }}
                                <span class="required">*</span></label>
                            <div class="col-12">
                                <select  name="strategic_document_type_id"
                                         class="form-control strategic_document_type_id form-control-sm select2 @error('strategic_document_type_id'){{ 'is-invalid' }}@enderror">
                                    <option value="" @if(old('strategic_document_type_id', '') == '') selected @endif>
                                        ---
                                    </option>
                                    @if(isset($strategicDocumentTypes) && $strategicDocumentTypes->count())
                                        @foreach($strategicDocumentTypes as $row)
                                            @if($row->active)
                                                <option value="{{ $row->id }}"
                                                        @if(old('strategic_document_type_id', $doc->strategic_document_type_id) == $row->id) selected @endif
                                                        data-id="{{ $row->id }}"
                                                >{{ $row->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('strategic_document_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_strategic_document_type_id"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="accept_act_institution_type_id">{{ trans_choice('custom.authority_accepting_strategic', 1) }}
                                <span class="required">*</span></label>
                            <div class="col-12">
                                <select name="accept_act_institution_type_id"
                                        class="form-control form-control-sm accept_act_institution_type_id select2 @error('accept_act_institution_type_id'){{ 'is-invalid' }}@enderror">
                                    <option value=""
                                            @if(old('accept_act_institution_type_id', $doc->id ? $doc->accept_act_institution_type_id : '') == '') selected @endif>---
                                    </option>
                                    @if(isset($authoritiesAcceptingStrategic) && $authoritiesAcceptingStrategic->count())
                                        @foreach($authoritiesAcceptingStrategic as $row)
                                            <option value="{{ $row->id }}"
                                                    @if(old('accept_act_institution_type_id', ($doc->id ? $doc->accept_act_institution_type_id : 0)) == $row->id) selected @endif
                                                    data-id="{{ $row->id }}"
                                                    data-level="{{ $row->nomenclature_level_id }}"
                                            >{{ $row->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('accept_act_institution_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-md-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="col-sm-12 control-label">{{ trans_choice('custom.authority_accepting_strategic', 1) }}</label>--}}
{{--                            <div class="col-12">--}}
{{--                                {{ $doc->accept_act_institution_type_id ? $doc->accept_act_institution_name : '' }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="active">{{ trans_choice('custom.public_consultations', 1) }}</label>
                            <div class="col-12">
                                <select  name="public_consultation_id" data-types2ajax="pc" data-urls2="{{ route('admin.select2.ajax', 'pc') }}" data-placeholders2="{{ __('custom.search_pc_record_js_placeholder') }}"
                                         class="form-control form-control-sm public_consultation_id select2-autocomplete-ajax @error('public_consultation_id'){{ 'is-invalid' }}@enderror">
                                    @if($doc->public_consultation_id)
                                        <option value="{{ $doc->public_consultation_id }}" selected>{{ $doc->consultation_reg_num }}</option>
                                    @endif
                                </select>
                                @error('public_consultation_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_public_consultation_id"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 d-none prisSection">
                    <div class="col-md-12 d-none" id="pris-act">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">
                                    {{ trans_choice('custom.category', 1) }}
                                </label>

                                <span class="text-danger" id="connect-doc-error"></span>
                                <select name="legal_act_type_filter" class="form-control form-control-sm legal_act_type_filter select2 @error('legal_act_type_filter'){{ 'is-invalid' }}@enderror">
                                    <option value="" @if(old('legal_act_type_filter', '') == '') selected @endif>--</option>
                                    @if(isset($legalActTypes) && $legalActTypes->count())
                                        @foreach($legalActTypes as $row)
                                            <option value="{{ $row->id }}" @if($row->id == old('legal_act_type_filter', $doc->pris_act_id ? $doc->pris_legal_act_type_id : 0)) selected @endif>{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('legal_act_type_filter')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_legal_act_type_filter"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-12 control-label"
                                       for="pris_act_id">Акт за приемане от раздел „Актове на МС“</label>
                                <select name="pris_act_id" data-types2ajax="pris_doc" data-urls2="{{ route('admin.select2.ajax', 'pris_doc') }}" data-placeholders2="{{ __('custom.search_pris_doc_js_placeholder') }}"
                                        class="form-control form-control-sm pris_act_id select2-autocomplete-ajax @error('pris_act_id'){{ 'is-invalid' }}@enderror">
                                    @if($doc->pris_act_id)
                                        <option value="{{ $doc->pris_act_id }}" selected>{{ $doc->pris_reg_num }}</option>
                                    @endif
                                </select>
                                @error('pris_act_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_pris_act_id"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="document_date_accepted">
                                <i class="fas fa-info-circle text-info mr-2" data-toggle="tooltip" title="Когато документът е свързан с Акт за приемане от раздел „Актове на МС“, дата на приемане се взима автоматично от акта. Когато Дата на приемане остане празно, автоматично се взима днешна дата."></i>
                                {{ __('custom.date_accepted') }}
                                <span class="required">*</span></label>
                            <div class="col-12">
                                <input type="text"  name="document_date_accepted"
                                       class="form-control form-control-sm document_date_accepted datepicker @error('document_date_accepted'){{ 'is-invalid' }}@enderror"
                                       value="{{ old('document_date_accepted', $doc->document_date_accepted ? displayDate($doc->document_date_accepted) : '') }}">
                                @error('document_date_accepted')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_document_date_accepted"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="document_date_pris">{{ __('custom.date_expiring') }}
                                <span class="required">*</span></label>
                            <div class="col-12">
                                <input type="text"  name="document_date_expiring"
                                       class="form-control form-control-sm document_date_expiring datepicker @error('document_date_expiring'){{ 'is-invalid' }}@enderror"
                                       value="{{ old('document_date_expiring', $doc->document_date_expiring ?? '') }}">
                                @error('document_date_expiring')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_document_date_expiring"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="document_date_pris">{{ __('custom.date_indefinite') }}
                                <span class="required">*</span></label>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="date_expiring_indefinite"
                                           class="form-check-input date_expiring_indefinite"
                                           value="1" @if(is_null(old('document_date_expiring', $doc->document_date_expiring))) checked @endif>
                                    <label class="form-check-label" for="date_valid_indefinite_main">
                                        {{ __('custom.date_expring_indefinite') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-12 control-label"
                                   for="strategic_act_link">{{ __('validation.attributes.link_to_monitorstat') }}</label>
                            <div class="col-12">
                                <input type="text" name="link_to_monitorstat"
                                       class="form-control form-control-sm @error('link_to_monitorstat'){{ 'is-invalid' }}@enderror"
                                       value="{{ old('link_to_monitorstat', $doc->link_to_monitorstat ?? '') }}">
                                @error('link_to_monitorstat')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="ajax-error text-danger mt-1 error_link_to_monitorstat"></div>
                            </div>
                        </div>
                    </div>
                </div>


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
                <form class="row sd-form-files" id="fileform_{{ $doc->id }}" data-extension="{{ implode(',', \App\Models\File::ALLOWED_FILE_STRATEGIC_DOC) }}" data-size="{{ (config('filesystems.max_upload_file_size') * 1024) }}"  action="{{ route('admin.upload.file.languages', ['object_id' => $doc->id, 'object_type' => \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN]) }}" method="post" name="form" id="form" enctype="multipart/form-data">
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
                            <div class="ajax-error text-danger mt-1 error_{{ 'description_'.$lang['code'] }}"></div>
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
                            <div class="ajax-error text-danger mt-1 error_{{ 'file_'.$lang['code'] }}"></div>
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
                        <button id="save" type="button" class="btn btn-success sd-submit-files">{{ __('custom.save') }}</button>
                    </div>
                </form>
                @if(isset($docFiles) && sizeof($docFiles))
                    <table class="table table-sm table-hover table-bordered mt-4">
                        <tbody>
                        <tr>
                            <th>{{ __('custom.name') }}</th>
{{--                            <th>Видим в репорти</th>--}}
                            <th></th>
                        </tr>
                        @foreach($docFiles as $f)
                            @if($f['id'])
                                <tr>
                                    <td>{{ $f['description_'.$f['locale']] }} ({{ strtoupper($f['locale']) }})</td>
{{--                                    <td><i class="fas @if($f['is_visible']) fa-check text-success @else fa-minus text-danger @endif"></i></td>--}}
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
