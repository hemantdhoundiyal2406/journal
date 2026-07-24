<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class AdminPublishedArticlesController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['authors', 'volume', 'issue'])
            ->where('status', 'Published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('doi', 'LIKE', "%{$search}%")
                    ->orWhere('manuscript_id', 'LIKE', "%{$search}%");
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(15)->appends($request->all());

        return view('admin.published.index', compact('articles'));
    }

    public function updateDoi(Request $request, $id)
    {
        $request->validate([
            'doi' => 'required|string|max:100',
            'start_page' => 'nullable|string|max:20',
            'end_page' => 'nullable|string|max:20',
        ]);

        $article = Article::findOrFail($id);
        $article->update([
            'doi' => $request->doi,
            'start_page' => $request->start_page,
            'end_page' => $request->end_page,
        ]);

        return back()->with('success', "DOI updated for {$article->manuscript_id}.");
    }
}
