<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap.base') }}</loc>
        <lastmod>{{ \Carbon\Carbon::now()->startOfMonth()->toIso8601String() }}</lastmod>
    </sitemap>
    @if(isset($addItemsPages) && $addItemsPages)
        @for($i = 1; $i <= $addItemsPages; $i++)
            <sitemap>
                <loc>{{ route('sitemap.sub', ['page' => $i]) }}</loc>
                <lastmod>{{ $modifyTime }}</lastmod>
            </sitemap>
        @endfor
    @endif
</sitemapindex>
