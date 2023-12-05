@php
    $file_name ??= '';
    $is_active ??= true;
    $effective_from ??= \Carbon\Carbon::now();
    $published_from ??= \Carbon\Carbon::now();
    $type ??= '';
    $versions ??= [];
@endphp

<div class="document-wrapper-ks mt-3">
    <a href="#" class="main-color text-decoration-none fs-18">
        <i class="fa-regular fa-file-pdf main-color me-2 fs-5"></i>
        {{ $file_name }}
    </a>

    <div class="document-info-field d-flex mt-3 pb-2">
        <div class="doc-info-item">
            <strong> {{ __('custom.status') }}:</strong>
            <span class="active-li w-min-content">
                {{ $is_active ? __('custom.active_m') : __('custom.inactive_m') }}
            </span>
        </div>

        <div class="doc-info-item">
            <strong> ПМС №:</strong>
            <span><a href="#" class="text-decoration-none">150 обн.</a></span>
        </div>
        <div class="doc-info-item">
            <strong> ДВ:</strong>
            <span><a href="#" class="text-decoration-none">№105</a></span>
        </div>
        <div class="doc-info-item">
            <strong> {{ __('custom.effective_from') }}:</strong>
            <span>{{ \Carbon\Carbon::parse($effective_from)->format('d.m.Y') . __('custom.year_short') }}</span>
        </div>
        <div class="doc-info-item">
            <strong> {{ __('custom.date_published') }}:</strong>
            <span>{{ \Carbon\Carbon::parse($published_from)->format('d.m.Y') . __('custom.year_short') }}</span>
        </div>
        <div class="doc-info-item">
            <strong> {{ trans_choice('custom.kinds', 1) }}:</strong>
            <span class="text-success">{{ $type }}</span>
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
