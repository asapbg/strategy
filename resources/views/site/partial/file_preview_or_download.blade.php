@if(canPreview($f))
    <a class="main-color text-decoration-none preview-file-modal" role="button" href="javascript:void(0)" title="{{ __('custom.preview') }}" data-file="{{ $f->id }}" data-url="{{ route('modal.file_preview', ['id' => $f->id]) }}">
        {!! fileIcon($f->content_type) !!} {{ $f->description }} - {{ displayDate($f->created_at) }}
    </a>
@else
    <a class="main-color text-decoration-none" href="{{ route('download.file', $f->id) }}" role="button" target="_blank" title="{{ __('custom.download') }}" >
        {!! fileIcon($f->content_type) !!} {{ $f->description }} - {{ displayDate($f->created_at) }}
    </a>
@endif
