<?=
/* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="bg-BG" data-google-analytics-opt-out="">
    <updated>{{ \Carbon\Carbon::parse($item->updated_at)->toAtomString() }}</updated>
    <icon>{{ asset('img/strategy-logo.svg') }}</icon>
    <id>{{ route('rss.public-consultation.item', $item->id) }}</id>
    <link href="{{ route('rss.adv_boards.item', $item->id) }}" rel="self" type="application/atom+xml"/>
    <link href="{{ route('public_consultation.view', $item->id) }}" rel="alternate" type="text/html"/>
    <logo>{{ asset('img/strategy-logo.svg') }}</logo>
    <title>{{ $item->title }} </title>
    <entry>
        <author>
            <name>{{ $item->responsibleInstitution?->name }}</name>
            @if($item->responsibleInstitution)
                <uri>{{ route('institution.profile.pc', $item->responsibleInstitution) }}</uri>
            @endif
        </author>
        <category label="{{ $item->nomenclatureLevelLabel }}" term="{{ $item->nomenclatureLevelLabel }}"/>
        <content type="html"><![CDATA[
            <p><strong>Област на политика:</strong> Държавна администрация</p>
            <p><strong>Срок за коментари:</strong> 07.06.2024</p>
            <p><strong>Институция:</strong> Агенция за публичните предприятия и контрол</p>
            <p><strong>Вид акт:</strong> Акт на орган извън изпълнителната власт</p>
            <p><strong>Описание:</strong> описание пак и пак</p>
            ]]></content>
        <id>10265</id>
        <link href="https://strategy.asapbg.com/bg/public-consultations/10265"/>
        <updated>{{ \Carbon\Carbon::parse($item->updated_at)->toAtomString() }}</updated>
        <published>{{ \Carbon\Carbon::parse($item->created_at)->toAtomString() }}</published>
        <title>{{ $item->title }}</title>
    </entry>
    @php($timeLine = $item->orderTimeline(true))
    @if(sizeof($timeLine))
        @foreach($timeLine as $t)
            @if($t['isActive'])
                <entry>
                    <category label="{{ $t['label'] }}" term="{{ $t['label'] }}"/>
                    <content type="html"><![CDATA[ {!! $t['description'] !!} ]]></content>
                    <id>{{ $t['label'] }}</id>
                    @if(isset($t['file']))
                        <link href="{{ route('download.file', $t['file']) }}"/>
                    @endif
                    @if(isset($t['date']))
                        <updated>{{ \Carbon\Carbon::parse($t['date'])->toAtomString() }}</updated>
                    @endif
                    <title>{{ $t['label'] }}</title>
                </entry>
            @endif
        @endforeach
    @endif
    @php($currentIndex = $item->comments->count())
    @foreach($item->comments as $c)
        <entry>
            <author>
                <name>{{ $c->user?->fullname() }}</name>
                @if($item->user)
                    <uri>{{ route('user.profile.pc', $item->user) }}</uri>
                @endif
            </author>
            <category label="comment" term="comment"/>
            <content type="html"><![CDATA[
                {!! $c->content !!}
                ]]>
            </content>
            <id>{{ $c->id }}</id>
            <link href="{{ route('public_consultation.view', $item->id) }}"/>
            <updated>{{ \Carbon\Carbon::parse($c->updated_at)->toAtomString() }}</updated>
            <title>{{ trans_choice('custom.comments', 1) }} #{{ $currentIndex }} {{ __('custom.from') }} {{ $c->user?->fullname() }}</title>
        </entry>
        @php($currentIndex -= 1)
    @endforeach
</feed>
