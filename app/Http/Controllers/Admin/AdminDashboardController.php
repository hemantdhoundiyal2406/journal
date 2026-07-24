<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTimeline;
use App\Models\Author;
use App\Models\Issue;
use App\Models\Volume;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_articles' => Article::count(),
            'new_submissions' => Article::where('status', 'Submitted')->count(),
            'under_review' => Article::where('status', 'Under Review')->count(),
            'accepted' => Article::where('status', 'Accepted')->count(),
            'rejected' => Article::where('status', 'Rejected')->count(),
            'published' => Article::where('status', 'Published')->count(),
            'current_volume' => Volume::orderBy('year', 'desc')->first()?->volume_number ?? '1',
            'current_issue' => Issue::where('is_published', true)->latest()->first()?->issue_number ?? '1',
            'total_authors' => Author::count(),
        ];

        $latestSubmissions = Article::with(['authors'])
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $recentActivities = ArticleTimeline::with('article')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Monthly submission chart data for current year
        $driver = DB::getDriverName();
        $monthQuery = $driver === 'sqlite' ? DB::raw("cast(strftime('%m', created_at) as integer) as month") : DB::raw('MONTH(created_at) as month');

        $monthlySubmissions = Article::select(
            $monthQuery,
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'latestSubmissions', 'recentActivities', 'monthlySubmissions'));
    }
}
