{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('current-issue') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('archives') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('editorial-board') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($pages as $page)
    <url>
        <loc>{{ route('cms.page', $page->slug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach($articles as $article)
    <url>
        <loc>{{ route('article.detail', $article->id) }}</loc>
        <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
        <changefreq>never</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
