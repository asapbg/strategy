@if(isset($attFile) && $attFile)
    @if(!empty($attFile->description))
        <div class="d-inline-block mr-2">
            <a target="_blank" href="{{ route('admin.download.file', $attFile) }}" title="{{ __('custom.download') }}">
                {!! fileIcon($attFile->content_type) !!} {{ !empty($attFile->description) ? $attFile->description : $attFile->filename }}
            </a>
        </div>
        <div class="d-inline-block">
            @if(isset($delete) && !empty($delete))
                <button type="button" name="confirmModal" class="btn btn-sm btn-danger approveModal" data-file="{{ $attFile->id }}" data-title="Изтриване на файл" data-question="Сигурни ли сте, че искате да изтрите този файл?">{{ __('custom.delete') }}</button>
                <a href="{{ $delete }}" id="approveModalSubmit_{{ $attFile->id }}" class="d-none">{{ __('custom.delete') }}</a>
            @endif
        </div>
    @endif
@endif
