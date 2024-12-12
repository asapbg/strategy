@if($item->meetings_per_year)
    <h5 class="mb-5">{{ __('validation.attributes.meetings_per_year') . ' ' . $item->meetings_per_year }}</h5>
@else
    <h5 class="mb-5">{{ __('custom.no_meetings_per_year') }}</h5>
@endif
