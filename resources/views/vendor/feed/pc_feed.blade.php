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
        <title>{!! \Spatie\Feed\Helpers\Cdata::out($item->title) !!}</title>
        <link rel="alternate" href="{{ url($item->link) }}" />
        <id>{{ url($item->id) }}</id>
        <author>
            <name>{!! \Spatie\Feed\Helpers\Cdata::out($item->authorName) !!}</name>
            @if(!empty($item->authorEmail))
                <email>{!! \Spatie\Feed\Helpers\Cdata::out($item->authorEmail) !!}</email>

            @endif
        </author>
        <summary type="html">
            <description>
                {!! \Spatie\Feed\Helpers\Cdata::out($item->summary) !!}
            </description>
        </summary>
        @if($item->__isset('enclosure'))
            <content type="html">
                <![CDATA[
                <a href="{{ url($item->link) }}">
                    <img alt="{{ $item->title }}" src="{{ url($item->enclosure) }}" />
                </a>
                ]]>
            </content>
        @endif
        @foreach($item->category as $category)
            <category term="{{ $category }}" />
        @endforeach
        <updated>{{ $item->timestamp() }}</updated>
    </entry>
    @endforeach
    </feed>
