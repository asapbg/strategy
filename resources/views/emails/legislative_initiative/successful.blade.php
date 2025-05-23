@component('mail::message')

{{ __('Respected') }} {{ $username }},

{!! __('notifications_msg.legislative_initiative.success.main_text') !!}

{{ __('custom.name') }}: {{ __('custom.change_f') }} {{ __('custom.in') }} {{ $data['item']->law?->name }}<br>
{{ __('site.email.li.change') }}: {{ strip_tags(html_entity_decode($data['item']->description)) ?? '' }}<br>
{{ __('custom.author') }} : {{ $data['item']->user?->fullName() }}<br>
{{ __('site.email.li.law_institution') }} : {{ $data['item']->law && $data['item']->law->institutions->count() ? join(';', $data['item']->law->institutions->pluck('name')->toArray()) : '' }}<br>
@if(isset($data['url']) && !empty($data['url']))
{{ __('Link to the legislative initiative on the Public Consultation Portal') }}:
    @component('mail::link', ['url' => $data['url']])
        {{ __('See the legislative initiative') }}
    @endcomponent
@endif

{{ __('Autogenerated email') }}

@endcomponent
