<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleAuthor;
use App\Models\Author;
use Illuminate\Http\Request;

class AdminAuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('institution', 'LIKE', "%{$search}%")
                    ->orWhere('country', 'LIKE', "%{$search}%")
                    ->orWhere('orcid', 'LIKE', "%{$search}%");
            });
        }

        $authors = $query->orderBy('full_name')->paginate(20)->appends($request->all());

        return view('admin.authors.index', compact('authors'));
    }

    public function show($id)
    {
        $author = Author::findOrFail($id);
        $articleAuthors = ArticleAuthor::with('article')
            ->where('email', $author->email)
            ->get();

        return view('admin.authors.show', compact('author', 'articleAuthors'));
    }
}
