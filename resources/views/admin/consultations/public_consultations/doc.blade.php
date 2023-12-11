<form class="row" action="{{ route('admin.consultations.public_consultations.store.documents') }}" method="post" enctype="multipart/form-data">
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
                                    </div>
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
