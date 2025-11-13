<form class="row" action="{{ route('admin.consultations.public_consultations.store.documents') }}" method="post" enctype="multipart/form-data">
    <div class="col-md-12 mb-2">
        <h4>Основни документи</h4>
    </div>
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
    <input type="hidden" name="act_type" value="{{ $item->act_type_id }}">
    @foreach(\App\Enums\DocTypesEnum::docsByActType($item->act_type_id) as $docType)
        @if($docType != \App\Enums\DocTypesEnum::PC_COMMENTS_REPORT->value)
            @foreach(config('available_languages') as $lang)
                @php($validationRules = \App\Enums\DocTypesEnum::validationRules($docType, $lang['code']))
                @php($fieldName = 'file_'.$docType.'_'.$lang['code'])
                <div class="col-md-6 mb-3">
                    <label for="{{ $fieldName }}" class="form-label">{{ __('validation.attributes.'.$fieldName) }} @if(in_array('required', $validationRules))<span class="required">*</span>@endif </label>
                    <input class="form-control form-control-sm @error($fieldName) is-invalid @enderror" id="{{ $fieldName }}" type="file" name="{{ $fieldName }}">
                    @error($fieldName)
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    @if(isset($documents) && sizeof($documents))
                        @if(isset($documents[$docType.'_'.$lang['code']]) && sizeof($documents[$docType.'_'.$lang['code']]))
                            @foreach($documents[$docType.'_'.$lang['code']] as $doc)
                                @if($docType != \App\Enums\DocTypesEnum::PC_COMMENTS_REPORT->value)
                                    <div class="mb-3 @if($loop->first) mt-3 @endif">
                                        <a class="mr-3" href="{{ route('admin.download.file', $doc) }}" target="_blank" title="{{ __('custom.download') }}">
                                            {!! fileIcon($doc->content_type) !!} {{ $doc->{'description_'.$doc->locale} }} - {{ __('custom.'.$doc->locale) }} | {{ __('custom.version_short').' '.$doc->version }} | {{ displayDate($doc->created_at) }} | {{ $doc->user ? $doc->user->fullName() : '' }}
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info preview-file-modal" data-file="{{ $doc->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $doc->id]) }}">{{ __('custom.preview') }}</button>
                                        <a class="btn btn-sm btn-danger ml-2 js-toggle-delete-resource-modal" type="button"
                                           data-target="#modal-delete-resource"
                                           data-resource-id="{{ $doc->id }}"
                                           data-resource-title="Документа"
                                           data-resource-name="Документа"
                                           data-resource-delete-url="{{ route('admin.delete.file', ['file' => $doc->id]) }}"
                                        >
                                            <i class="fas fa-trash me-1" role="button" data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                        </a>
                                    </div>
                                @endif
                                @if($item->documentsAtt->count())
                                    @php($f = 0)
                                    @foreach($item->documentsAtt as $att)
                                        @if($att->doc_type == $docType.'00' && $att->locale == $lang['code'])
                                            @if(!$f)
                                                <div><p><strong>Приложения:</strong></p>
                                            @endif
                                            @php($f = 1)
                                            <div class="mb-2">
                                                <a class="mr-3 ml-3" href="{{ route('admin.download.file', $att) }}" target="_blank" title="{{ __('custom.download') }}">
                                                    {!! fileIcon($att->content_type) !!} {{ $att->{'description_'.$att->locale} }} | {{ displayDate($att->created_at) }} | {{ $att->user ? $att->user->fullName() : '' }}
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-info preview-file-modal" data-file="{{ $doc->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $doc->id]) }}">{{ __('custom.preview') }}</button>
                                                <a class="btn btn-sm btn-danger ml-2 js-toggle-delete-resource-modal" type="button"
                                                   data-target="#modal-delete-resource"
                                                   data-resource-id="{{ $doc->id }}"
                                                   data-resource-title="Приложението"
                                                   data-resource-name="Приложението"
                                                   data-resource-delete-url="{{ route('admin.delete.file', ['file' => $att->id]) }}"
                                                >
                                                    <i class="fas fa-trash me-1" role="button" data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($f)
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endif
                </div>
            @endforeach
        @endif
    @endforeach
    <div class="col-md-6 col-md-offset-3">
        <button id="doc_save" type="submit" class="btn btn-success d-none">{{ __('custom.save') }}</button>
        <button id="doc_save_fake" data-btn="#doc_save" type="button" class="btn btn-success">{{ __('custom.save') }}</button>
        <button id="doc_stay" type="submit" name="stay" value="1" class="btn btn-success d-none">{{ __('custom.save_and_stay') }}</button>
        <button id="doc_stay_fake" data-btn="#doc_stay" type="button" name="stay" value="1" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>
        <a href="{{ route($listRouteName) }}"
           class="btn btn-primary">{{ __('custom.cancel') }}</a>
    </div>
</form>

@if(isset($subDocumentsTypes) && sizeof($subDocumentsTypes))
<hr class="custom-hr mt-4">
<form class="row" action="{{ route('admin.consultations.public_consultations.store.sub_documents') }}" method="post" enctype="multipart/form-data">
    <div class="col-md-12 mb-2">
        <h4>Допълнителни документи (приложения)</h4>
    </div>
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">
{{--    <input type="hidden" name="act_type" value="{{ $item->act_type_id }}">--}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-sm-12 control-label" for="parent_type">Приложи към:<span class="required">*</span></label>
            <div class="col-12">
                <select id="parent_type" name="parent_type" class="form-control form-control-sm select2 select2-no-clear @error('parent_type'){{ 'is-invalid' }}@enderror">
                    <option value="" @if(old('parent_type', 0) == 0) selected @endif>---</option>
                    @foreach($subDocumentsTypes as $docType)
                        @if(!in_array($docType, [\App\Enums\DocTypesEnum::PC_COMMENTS_REPORT->value, \App\Enums\DocTypesEnum::PC_OTHER_DOCUMENTS->value]))
                            @php($docParentName = 'file_'.$docType)
                            <option value="{{ $docType.'00' }}"
                                    @if(substr(old('parent_type', 0), 0, -2) == $docType) selected @endif
                            >{{ __('validation.attributes.'.$docParentName) }}</option>
                        @endif
                    @endforeach
                </select>
                @error('parent_type')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-12"></div>
    @foreach(config('available_languages') as $lang)
        <div class="col-md-6 mb-3">
            <label for="description_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.display_name_'.$lang['code']) }}<span class="required">*</span> </label>
            <input value="{{ old('description_'.$lang['code'], '') }}" class="form-control form-control-sm @error('description_'.$lang['code']) is-invalid @enderror" id="description_{{ $lang['code'] }}" type="text" name="description_{{ $lang['code'] }}">
            @error('description_'.$lang['code'])
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
    @foreach(config('available_languages') as $lang)
        <div class="col-md-6 mb-3">
            <label for="file_{{ $lang['code'] }}" class="form-label">{{ __('validation.attributes.file_'.$lang['code']) }}<span class="required">*</span> </label>
            <input class="form-control form-control-sm @error('file_'.$lang['code']) is-invalid @enderror" id="file_{{ $lang['code'] }}" type="file" name="file_{{ $lang['code'] }}">
            @error('file_'.$lang['code'])
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    @endforeach
    <div class="col-12">
        <button id="doc_save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
{{--        <button id="doc_save_fake" data-btn="#doc_save" type="button" class="btn btn-success">{{ __('custom.save') }}</button>--}}
        <button id="doc_stay" type="submit" name="stay" value="1" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>
{{--        <button id="doc_stay_fake" data-btn="#doc_stay" type="button" name="stay" value="1" class="btn btn-success">{{ __('custom.save_and_stay') }}</button>--}}
        <a href="{{ route($listRouteName) }}"
           class="btn btn-primary">{{ __('custom.cancel') }}</a>
    </div>
</form>
@endif

@if($item->oldFiles->count())
    <div class="row">
        <hr class="custom-hr mt-4">
        <div class="col-md-12 mb-2">
            <h4>Файлове (import)</h4>
            @foreach($item->oldFiles as $oldFile)
                <div class="mb-3 @if($loop->first) mt-3 @endif">
                    <a class="mr-3" href="{{ route('admin.download.file', $oldFile) }}" target="_blank" title="{{ __('custom.download') }}">
                        {!! fileIcon($oldFile->content_type) !!} {{ $oldFile->{'description_'.$oldFile->locale} }} - {{ __('custom.'.$oldFile->locale) }} | {{ __('custom.version_short').' '.$oldFile->version }} | {{ displayDate($oldFile->created_at) }} | {{ $oldFile->user ? $oldFile->user->fullName() : '' }}
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-info preview-file-modal" data-file="{{ $oldFile->id }}" data-url="{{ route('admin.preview.file.modal', ['id' => $oldFile->id]) }}">{{ __('custom.preview') }}</button>
                    <a class="btn btn-sm btn-danger js-toggle-delete-resource-modal" type="button"
                       data-target="#modal-delete-resource"
                       data-resource-id="{{ $oldFile->id }}"
                       data-resource-title="Документа"
                       data-resource-name="Документа"
                       data-resource-delete-url="{{ route('admin.delete.file', ['file' => $oldFile->id]) }}"
                    >
                        <i class="fas fa-trash me-1" role="button" data-toggle="tooltip" title="{{ __('custom.delete') }}"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            let cancelBtnTxt = '{{ __('custom.cancel') }}';
            let continueTxt = '{{ __('custom.continue') }}';
            let titleTxt = '{{ __('custom.change_file') }}';
            let fileChangeWarningTxt = '{{ __('custom.change_file_warning') }}';
            $('#doc_save_fake, #doc_stay_fake').on('click', function (){
                let submitId = $(this).data('btn');
                new MyModal({
                    title: titleTxt,
                    footer: '<button class="btn btn-sm btn-success ms-3" onclick="$(\''+ submitId +'\').click();">'+ continueTxt +'</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="'+ cancelBtnTxt +'">'+ cancelBtnTxt +'</button>',
                    body: '<div class="alert alert-danger">'+ fileChangeWarningTxt +'</div>',
                });
            });
        });
    </script>
@endpush
