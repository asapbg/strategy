@php
    $class = $class ?? 'mb-5';
@endphp

@if($item->meetings_per_year)
    <h5 class="{{ $class }}">{{ __('validation.attributes.meetings_per_year') . ' ' . $item->meetings_per_year }}</h5>
@else
    <h5 class="{{ $class }}">{{ __('custom.no_meetings_per_year') }}</h5>
@endif
