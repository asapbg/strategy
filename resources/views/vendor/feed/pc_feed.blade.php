<?=
/* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="{{ $meta['language'] }}">
    <icon>{{ asset('img/strategy-logo.svg') }}</icon>
    @foreach($meta as $key => $metaItem)
        @if($key === 'title')
            <{{ $key }}>{!! \Spatie\Feed\Helpers\Cdata::out($metaItem) !!}</{{ $key }}>
        @elseif($key === 'link')
            <{{ $key }} href="{{ url($metaItem) }}" rel="self"></{{ $key }}>
        @elseif(!in_array($key, ['description', 'language', 'image', '']))
            <{{ $key }}>{{ $metaItem }}</{{ $key }}>
        @endif
{{--        @if($key === 'link')--}}
{{--            <{{ $key }} href="{{ url($metaItem) }}" rel="self"></{{ $key }}>--}}
{{--        @elseif($key === 'title')--}}
{{--            <{{ $key }}>{!! \Spatie\Feed\Helpers\Cdata::out($metaItem) !!}</{{ $key }}>--}}
{{--        @elseif($key === 'description')--}}
{{--            <subtitle>{{ $metaItem }}</subtitle>--}}
{{--        @elseif($key === 'language')--}}
{{--        @elseif($key === '')--}}
{{--            @if(!empty($metaItem))--}}
{{--                <logo>{!! $metaItem !!}</logo>--}}
{{--            @else--}}

{{--            @endif--}}
{{--        @else--}}
{{--            <{{ $key }}>{{ $metaItem }}</{{ $key }}>--}}
{{--        @endif--}}
    @endforeach
    @foreach($items as $item)
        <entry>
            <author>
                <name>{{ $item->authorName }}</name>
                @if(isset($item->authorUrl) && $item->authorUrl)
                    <uri>{{ $item->authorUrl }}</uri>
                @endif
            </author>
            @foreach($item->category as $category)
                <category label="{{ $category }}" term="{{ $category }}" />
            @endforeach
            @if(isset($item->enclosure) && $item->enclosure)
                <summary type="html">
                    <description><![CDATA[
                        <img alt="{{ $item->title }}" src="{{ url($item->enclosure) }}" />
                        ]]></description>
                </summary>
            @endif
            <content type="html"><![CDATA[
                @if(isset($item->enclosure) && $item->enclosure)
                    <img alt="{{ $item->title }}" src="{{ url($item->enclosure) }}" />
                @endif
                {!! $item->summary !!}
                ]]></content>
            <id>{{ url($item->id) }}</id>
            <link href="{{ url($item->link) }}"/>
            <updated>{{ \Carbon\Carbon::parse($item->updated)->toAtomString() }}</updated>
            <published>@if(isset($item->published)){{ \Carbon\Carbon::parse($item->published)->toAtomString() }}@else{{ \Carbon\Carbon::parse($item->created)->toAtomString() }}@endif</published>
            <title>{{ $item->title }}</title>
        </entry>
    @endforeach
</feed>
