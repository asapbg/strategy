@if (!isset($file_name))
    @php
        $file_name = fileIcon($file->content_type). " $file->description - ".displayDate($file->created_at);
    @endphp
@endif
@if(canPreview($file))
    @if(is_android() && $file->content_type == 'application/pdf')
        @php
            $path = (!str_contains($file->path, 'files') ? 'files/' : '') . $file->path;
            $pdfUrl = asset($path);
        @endphp
        <a class="main-color text-decoration-none d-block" href="{{ $pdfUrl }}">{!! $file_name !!}</a>
    @else
        <a class="main-color text-decoration-none preview-file-modal d-block" href="javascript:void(0)"
           title="{{ __('custom.preview') }}" data-file="{{ $file->id }}" data-url="{{ route('modal.file_preview', ['id' => $file->id]) }}"
        >
            {!! $file_name !!}
        </a>
    @endif
@else
    <a class="main-color text-decoration-none d-block" href="{{ route('download.file', $file->id) }}" target="_blank" title="{{ __('custom.download') }}">
        {!! $file_name !!}
    </a>
@endif
