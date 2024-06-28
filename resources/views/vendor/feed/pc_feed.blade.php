<?=
/* Using an echo tag here so the `<? ... ?>` won't get parsed as short tags */
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="{{ $meta['language'] }}">
    @foreach($meta as $key => $metaItem)
        @if($key === 'link')
            <{{ $key }} href="{{ url($metaItem) }}" rel="self"></{{ $key }}>
        @elseif($key === 'title')
            <{{ $key }}>{!! \Spatie\Feed\Helpers\Cdata::out($metaItem) !!}</{{ $key }}>
        @elseif($key === 'description')
            <subtitle>{{ $metaItem }}</subtitle>
        @elseif($key === 'language')
        @elseif($key === 'image')
            @if(!empty($metaItem))
                <logo>{!! $metaItem !!}</logo>
            @else

            @endif
        @else
            <{{ $key }}>{{ $metaItem }}</{{ $key }}>
        @endif
  @endforeach
    @foreach($items as $item)
        <entry>
            <author>
                <name>{!! \Spatie\Feed\Helpers\Cdata::out($item->authorName) !!}</name>
{{--                @if(!empty($item->authorEmail))--}}
{{--                    <email>{!! \Spatie\Feed\Helpers\Cdata::out($item->authorEmail) !!}</email>--}}
{{--                @endif--}}
            </author>
            @foreach($item->category as $category)
                <category term="{{ $category }}" />
            @endforeach
            <content type="html"><![CDATA[
                <a href="{{ url($item->link) }}">
                    <img alt="{{ $item->title }}" src="{{ url($item->enclosure) }}" />
                </a>
                {!! $item->summary !!}
                ]]></content>
            <id>{{ url($item->id) }}</id>
            <link href="{{ url($item->link) }}"/>
            <updated>{{ \Carbon\Carbon::parse($item->updated)->toAtomString() }}</updated>
            <title>{{ $item->title }}</title>
        </entry>
    @endforeach
</feed>
