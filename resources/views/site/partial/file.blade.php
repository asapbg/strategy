@php
    $file ??= new \App\Models\File();
    $debug ??= false;

    // Take the up-to-date file
    $file_up_to_date = $file->versions->count() === 0 ? $file : $file->versions->sortByDesc('id')->first();
@endphp

<div class="document-wrapper-ks mt-3">
    <a href="{{ route('download.file', $file_up_to_date) }}" class="main-color text-decoration-none fs-18">
        {!! fileIcon($file_up_to_date->content_type) !!}
        {{ $file_up_to_date->name }}
    </a>

    <div class="document-info-field d-flex mt-3 pb-2">
        <div class="doc-info-item">
            <strong> {{ __('custom.status') }}:</strong>

            @php $class = $file_up_to_date->active ? 'active-li' : 'closed-li'; @endphp

            <span class="w-min-content {{ $class }}">
                {{ $file_up_to_date->active ? __('custom.active_m') : __('custom.inactive_m') }}
            </span>
        </div>

        @if(!empty($file_up_to_date->resolution_council_ministers))
            <div class="doc-info-item">
                <strong>{{ __('custom.resolution_council_ministers_short') }}:</strong>
                <span><a href="#" class="text-decoration-none">{{ $file_up_to_date->resolution_council_ministers }}</a></span>
            </div>
        @endif

        @if(!empty($file_up_to_date->state_newspaper))
            <div class="doc-info-item">
                <strong>{{ __('custom.state_papernew_short') }}:</strong>
                <span><a href="#" class="text-decoration-none">{{ $file_up_to_date->state_newspaper }}</a></span>
            </div>
        @endif

        @if(!empty($file_up_to_date->effective_at))
            <div class="doc-info-item">
                <strong> {{ __('custom.effective_at') }}:</strong>
                <span>{{ \Carbon\Carbon::parse($file_up_to_date->effective_at)->format('d.m.Y') . __('custom.year_short') }}</span>
            </div>
        @endif

        <div class="doc-info-item">
            <strong> {{ __('custom.date_published') }}:</strong>
            <span>{{ \Carbon\Carbon::parse($file_up_to_date->created_at)->format('d.m.Y') . __('custom.year_short') }}</span>
        </div>

        <div class="doc-info-item">
            <strong> {{ trans_choice('custom.kinds', 1) }}:</strong>
            @php $class = $file_up_to_date->active ? 'text-success' : 'text-danger'; @endphp
            <span
                class="{{ $class }}">{{ $file_up_to_date->active ? __('custom.active_document') : __('custom.inactive_document') }}</span>
        </div>
    </div>

    @if(!empty($file->versions) && $file->versions->count() > 1)
        <div class="file-version pb-2">
            <strong> {{ __('custom.versions') }}:</strong>

            <span>
                @php $total_versions = $file->versions->count(); @endphp

                @foreach($file->versions as $key => $file)
                    @if(++$key === $total_versions)
                        @continue
                    @endif

                    <a href="#"
                       class="text-decoration-none">{{ __('custom.version') . ' ' . $file->version }} - {{ \Carbon\Carbon::parse($file->created_at)->format('d.m.Y') }}</a>

                    @if(++$key !== $total_versions)
                        <span>&#47;</span>
                    @endif
                @endforeach
            </span>
        </div>
    @endif
</div>
