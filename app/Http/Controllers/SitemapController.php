<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CmsPage;
use App\Models\Issue;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $articles = Article::where('status', 'Published')->latest()->get();
        $issues = Issue::where('is_published', true)->latest()->get();
        $pages = CmsPage::where('is_active', true)->get();

        $content = view('frontend.sitemap', compact('articles', 'issues', 'pages'))->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $url = url('/sitemap.xml');
        $robots = "User-agent: *\nDisallow: /admin/\nDisallow: /storage/temp/\n\nSitemap: {$url}\n";
        return response($robots, 200)->header('Content-Type', 'text/plain');
    }
}
