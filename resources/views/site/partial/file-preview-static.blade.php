@if(is_android())
    @php
        $path = (!str_contains($f['path'], 'files') ? 'files/' : '') . $f['path'];
        $pdfUrl = asset($path);
    @endphp
    <a class="main-color text-decoration-none d-block" href="{{ $pdfUrl }}">{!! $f['icon'] !!} {{ $f['name'] }}</a>
@else
    <a class="main-color text-decoration-none preview-file-modal d-block" role="button" href="javascript:void(0)"
       title="{{ __('custom.preview') }}" data-url="{{ route('modal.file_preview_static_page').'?path='.$f['path'] }}"
    >
        {!! $f['icon'] !!} {{ $f['name'] }}
    </a>
@endif
