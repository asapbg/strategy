<?=
/* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="bg-BG" data-google-analytics-opt-out="">
    <updated>{{ \Carbon\Carbon::parse($item->updated_at)->toAtomString() }}</updated>
    <icon>{{ asset('img/strategy-logo.svg') }}</icon>
    <id>{{ route('rss.legislative_initiative.item', $item->id) }}</id>
    <link href="{{ route('rss.legislative_initiative.item', $item->id) }}" rel="self" type="application/atom+xml"/>
    <link href="{{ route('legislative_initiatives.view', $item) }}" rel="alternate" type="text/html"/>
    <logo>{{ asset('img/strategy-logo.svg') }}</logo>
    <title>{{ $item->facebookTitle }}</title>
    <entry>
        <author>
            <name>{{ $item->user?->fullname() }}</name>
            @if($item->user)
                <uri>{{ route('user.profile.li', $item->user) }}</uri>
            @endif
        </author>
        <category label="{{ trans_choice('custom.legislative_initiatives', 1) }}" term="{{ trans_choice('custom.legislative_initiatives', 1) }}"/>
        <content type="html"><![CDATA[
            <p><strong>{{ __('custom.to_administrations_of') }}:</strong>
                @if($item->institutions->count())
                    @foreach($item->institutions as $i)
                        {{ $i->name }};
                    @endforeach
                @endif
            </p>
            <p><strong>{{ __('custom.proposal_votes_period') }}:</strong> {{ displayDate($item->end_support_at) }}</p>
            <p><strong>{{ __('custom.law_paragraph') }}:</strong> {!! $item->law_paragraph !!}</p>
            <p><strong>{{ __('custom.law_text') }}:</strong> {!! $item->law_text !!}</p>
            <p><strong>{{ __('custom.description_of_suggested_change') }}:</strong> {!! $item->description !!}</p>
            <p><strong>{{ __('custom.change_motivations') }}:</strong> {!! $item->motivation !!}</p>
            <p><strong>{{ __('site.support') }}:</strong> {{ $item->countLikes() }} ({{ __('custom.for') }}) | {{ $item->countDislikes() }} ({{ __('site.li_dislikes') }})</p>
            ]]></content>
        <id>{{ $item->id }}</id>
        <link href="{{ route('legislative_initiatives.view', $item) }}"/>
        <updated>{{ \Carbon\Carbon::parse($item->updated_at)->toAtomString() }}</updated>
        <published>{{ \Carbon\Carbon::parse($item->created_at)->toAtomString() }}</published>
        <title>{{ $item->facebookTitle }}</title>
    </entry>
    @if($item->comments->count())
        @php($currentIndex = $item->comments->count())
        @foreach($item->comments as $c)
            <entry>
                <author>
                    <name>{{ $c->user?->fullname() }}</name>
                    @if($item->user)
                        <uri>{{ route('user.profile.li', $item->user) }}</uri>
                    @endif
                </author>
                <category label="comment" term="comment"/>
                <content type="html"><![CDATA[
                    {!! $c->description !!}
                    <p><strong>{{ __('site.support') }}:</strong> {{ $c->likes->count() }} ({{ __('custom.for') }}) | {{ $c->dislikes->count() }} ({{ __('site.li_dislikes') }})</p>
                    ]]>
                </content>
                <id>{{ $c->id }}</id>
                <link href="{{ route('legislative_initiatives.view', $item) }}"/>
                <updated>{{ \Carbon\Carbon::parse($c->updated_at)->toAtomString() }}</updated>
                <title>{{ trans_choice('custom.comments', 1) }} #{{ $currentIndex }} {{ __('custom.from') }} {{ $c->user?->fullname() }}</title>
            </entry>
            @php($currentIndex -= 1)
        @endforeach
    @endif
</feed>
