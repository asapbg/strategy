<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if(sizeof($items))
        @foreach($items as $item)
            <url>
                <loc>{{ $item['url'] }}</loc>
                @if(isset($item['lastmod']))
                    <lastmod>{{ \Carbon\Carbon::parse($item['lastmod'])->toIso8601String() }}</lastmod>
                @endif
                @if(isset($item['changefreq']))
                    <changefreq>{{ $item['changefreq'] }}</changefreq>
                @endif
                @if(isset($item['priority']))
                    <priority>{{ $item['priority'] }}</priority>
                @endif
            </url>
        @endforeach
    @endif
</urlset>
