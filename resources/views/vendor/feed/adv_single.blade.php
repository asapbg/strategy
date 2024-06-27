<?=
/* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="bg-BG" data-google-analytics-opt-out="">
    <updated>{{ \Carbon\Carbon::parse($item->updated_at)->toAtomString() }}</updated>
    <icon>{{ asset('img/strategy-logo.svg') }}</icon>
    <id>{{ route('rss.adv_boards.item', $item->id) }}</id>
    <link href="{{ route('rss.adv_boards.item', $item->id) }}" rel="self" type="application/atom+xml"/>
    <link href="{{ route('advisory-boards.view', $item) }}" rel="alternate" type="text/html"/>
    <logo>{{ asset('img/strategy-logo.svg') }}</logo>
    <title>{{ $item->name }}</title>
    <entry>
        <category label="{{ trans_choice('custom.advisory_boards', 1) }}" term="{{ trans_choice('custom.advisory_boards', 1) }}"/>
        <content type="html"><![CDATA[
            <p><strong>{{ trans_choice('custom.field_of_actions', 1) }}:</strong> {{ $item->policyArea?->name }}</p>
            <p><strong>{{ trans_choice('custom.authority_advisory_board', 1) }}:</strong> {{ $item->authority?->name }}</p>
            <p><strong>{{ __('custom.chairman_site') }}:</strong>
                @if($item->chairmen->count())
                    @foreach($item->chairmen as $c)
                        {{ $c->member_name }};
                    @endforeach
                @endif
            </p>
            <p><strong>{{ __('validation.attributes.act_of_creation') }}:</strong> {!! $item->establishment?->description !!}</p>
            ]]>
        </content>
        <id>{{ $item->id }}</id>
        <link href="{{ route('advisory-boards.view', $item) }}"/>
        <updated>{{ \Carbon\Carbon::parse($item->updated_at)->toAtomString() }}</updated>
        <published>{{ \Carbon\Carbon::parse($item->created_at)->toAtomString() }}</published>
        <title>{{ $item->name }}</title>
    </entry>
    @if($item->meetings->count())
        @foreach($item->meetings as $m)
            <entry>
                <category label="meeting" term="meeting"/>
                <content type="html"><![CDATA[
                    {!! $m->description !!}
                    ]]></content>
                <id>{{ $m->id }}</id>
                @if($m->siteFiles->count())
                    @foreach($m->siteFiles as $f)
                        <link href="{{ route('download.file', $f) }}"/>
                    @endforeach
                @endif
                <updated>{{ \Carbon\Carbon::parse($m->updated_at)->toAtomString() }}</updated>
                <title>{{ __('site.meeting') }} {{ __('custom.from') }} {{ displayDate($m->next_meeting) }}</title>
            </entry>
        @endforeach
    @endif
</feed>
