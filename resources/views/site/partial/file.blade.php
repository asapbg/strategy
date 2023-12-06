@php
    $file ??= new \App\Models\File();
@endphp

<div class="document-wrapper-ks mt-3">
    <a href="{{ route('download.file', $file) }}" class="main-color text-decoration-none fs-18">
        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>
        {{ empty($file->custom_name) ? $file->filename : $file->custom_name }}
    </a>

    <div class="document-info-field d-flex mt-3 pb-2">
        <div class="doc-info-item">
            <strong> {{ __('custom.status') }}:</strong>

            @php $class = $file->active ? 'active-li' : 'closed-li'; @endphp

            <span class="w-min-content {{ $class }}">
                {{ $file->active ? __('custom.active_m') : __('custom.inactive_m') }}
            </span>
        </div>

        <div class="doc-info-item">
            <strong>{{ __('custom.resolution_council_ministers_short') }}:</strong>
            <span><a href="#" class="text-decoration-none">{{ $file->resolution_council_ministers }}</a></span>
        </div>
        <div class="doc-info-item">
            <strong>{{ __('custom.state_papernew_short') }}:</strong>
            <span><a href="#" class="text-decoration-none">{{ $file->state_newspaper }}</a></span>
        </div>
        <div class="doc-info-item">
            <strong> {{ __('custom.effective_at') }}:</strong>
            <span>{{ \Carbon\Carbon::parse($file->effective_at)->format('d.m.Y') . __('custom.year_short') }}</span>
        </div>
        <div class="doc-info-item">
            <strong> {{ __('custom.date_published') }}:</strong>
            <span>{{ \Carbon\Carbon::parse($file->created_at)->format('d.m.Y') . __('custom.year_short') }}</span>
        </div>
        <div class="doc-info-item">
            <strong> {{ trans_choice('custom.kinds', 1) }}:</strong>
            @php $class = $file->active ? 'text-success' : 'text-danger'; @endphp
            <span class="{{ $class }}">{{ $file->active ? __('custom.active_document') : __('custom.inactive_document') }}</span>
        </div>
    </div>
    <div class="file-version pb-2">
        <strong> {{ __('custom.versions') }}:</strong>

        <span>
            <a href="#" class="text-decoration-none">Версия 1 - 10.05.2023</a>
            <span>&#47;</span>
            <a href="#" class="text-decoration-none">Версия 2 - 15.05.2023</a>
            <span>&#47;</span>
            <a href="#" class="text-decoration-none">Версия 3 - 25.06.2023</a>
        </span>
    </div>
</div>
