<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\CmsPage;
use App\Models\EditorialMember;
use App\Models\Issue;
use App\Models\JournalSetting;
use App\Models\Volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FrontendController extends Controller
{
    public function index()
    {
        $latestArticles = Article::with(['authors', 'volume', 'issue'])
            ->where('status', 'Published')
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        $featuredArticles = Article::with(['authors', 'volume', 'issue'])
            ->where('status', 'Published')
            ->orderBy('view_count', 'desc')
            ->take(4)
            ->get();

        $currentIssue = Issue::with(['volume', 'articles' => function ($q) {
            $q->where('status', 'Published')->with('authors');
        }])
            ->where('is_published', true)
            ->orderBy('publication_year', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $topBarAnnouncement = Announcement::where('type', 'top_bar')
            ->where('is_active', true)
            ->latest()
            ->first();

        $popupNotice = Announcement::where('type', 'popup_notice')
            ->where('is_active', true)
            ->latest()
            ->first();

        $editorialMembers = EditorialMember::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $callForPapers = Announcement::where('type', 'call_for_papers')
            ->where('is_active', true)
            ->latest()
            ->first();

        $stats = [
            'total_published' => Article::where('status', 'Published')->count(),
            'total_volumes' => Volume::count(),
            'total_issues' => Issue::where('is_published', true)->count(),
            'total_downloads' => Article::sum('download_count'),
        ];

        return view('frontend.index', compact(
            'latestArticles',
            'featuredArticles',
            'currentIssue',
            'topBarAnnouncement',
            'popupNotice',
            'editorialMembers',
            'callForPapers',
            'stats'
        ));
    }

    public function articleDetail($id)
    {
        $article = Article::with(['authors', 'volume', 'issue', 'files'])
            ->where('id', $id)
            ->firstOrFail();

        // Increment view count securely
        $article->increment('view_count');

        $relatedArticles = Article::with(['authors'])
            ->where('status', 'Published')
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->take(4)
            ->get();

        return view('frontend.article_detail', compact('article', 'relatedArticles'));
    }

    public function downloadManuscript($id)
    {
        $article = Article::findOrFail($id);
        $file = $article->manuscriptFile;

        if (!$file || !Storage::disk('local')->exists($file->file_path)) {
            return back()->with('error', 'Manuscript PDF file not found on server.');
        }

        $article->increment('download_count');
        return Storage::disk('local')->download($file->file_path, $file->original_name);
    }

    public function currentIssue()
    {
        $currentIssue = Issue::with(['volume', 'articles' => function ($q) {
            $q->where('status', 'Published')->with('authors');
        }])
            ->where('is_published', true)
            ->orderBy('publication_year', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return view('frontend.current_issue', compact('currentIssue'));
    }

    public function archives()
    {
        $volumes = Volume::with(['issues' => function ($q) {
            $q->where('is_published', true)->withCount(['articles' => function ($aq) {
                $aq->where('status', 'Published');
            }]);
        }])
            ->orderBy('year', 'desc')
            ->get();

        return view('frontend.archives', compact('volumes'));
    }

    public function issueDetail($id)
    {
        $issue = Issue::with(['volume', 'articles' => function ($q) {
            $q->where('status', 'Published')->with('authors');
        }])->findOrFail($id);

        return view('frontend.issue_detail', compact('issue'));
    }

    public function editorialBoard()
    {
        $members = EditorialMember::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('designation');

        return view('frontend.editorial_board', compact('members'));
    }

    public function cmsPage($slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('frontend.cms_page', compact('page'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');
        $articleType = $request->input('type');
        $sortBy = $request->input('sort', 'latest');

        $articlesQuery = Article::with(['authors', 'volume', 'issue'])
            ->where('status', 'Published');

        if ($query) {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('abstract', 'LIKE', "%{$query}%")
                    ->orWhere('keywords', 'LIKE', "%{$query}%")
                    ->orWhere('manuscript_id', 'LIKE', "%{$query}%")
                    ->orWhere('doi', 'LIKE', "%{$query}%")
                    ->orWhereHas('authors', function ($aq) use ($query) {
                        $aq->where('first_name', 'LIKE', "%{$query}%")
                            ->orWhere('last_name', 'LIKE', "%{$query}%")
                            ->orWhere('email', 'LIKE', "%{$query}%")
                            ->orWhere('institution', 'LIKE', "%{$query}%")
                            ->orWhere('country', 'LIKE', "%{$query}%");
                    });
            });
        }

        if ($category) {
            $articlesQuery->where('category', $category);
        }

        if ($articleType) {
            $articlesQuery->where('article_type', $articleType);
        }

        if ($sortBy === 'views') {
            $articlesQuery->orderBy('view_count', 'desc');
        } elseif ($sortBy === 'downloads') {
            $articlesQuery->orderBy('download_count', 'desc');
        } else {
            $articlesQuery->orderBy('published_at', 'desc');
        }

        $articles = $articlesQuery->paginate(10)->appends($request->all());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.partials.search_results', compact('articles'))->render(),
                'total' => $articles->total(),
            ]);
        }

        return view('frontend.search', compact('articles', 'query', 'category', 'articleType', 'sortBy'));
    }

    public function verifyCertificate($token)
    {
        $article = Article::with(['authors', 'volume', 'issue'])
            ->where('certificate_token', $token)
            ->first();

        return view('frontend.certificate_verify', compact('article', 'token'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! Your message has been submitted to the editorial office.');
    }
}
