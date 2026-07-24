<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Issue;
use App\Models\Volume;
use Illuminate\Http\Request;

class AdminVolumeIssueController extends Controller
{
    public function index()
    {
        $volumes = Volume::with(['issues' => function ($q) {
            $q->withCount('articles');
        }])->orderBy('year', 'desc')->get();

        return view('admin.volumes.index', compact('volumes'));
    }

    public function storeVolume(Request $request)
    {
        $request->validate([
            'volume_number' => 'required|string|max:50',
            'year' => 'required|integer|min:2000|max:2099',
            'title' => 'nullable|string|max:250',
            'description' => 'nullable|string|max:1000',
        ]);

        Volume::create([
            'volume_number' => $request->volume_number,
            'year' => $request->year,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Volume created successfully.');
    }

    public function storeIssue(Request $request)
    {
        $request->validate([
            'volume_id' => 'required|exists:volumes,id',
            'issue_number' => 'required|string|max:50',
            'title' => 'nullable|string|max:250',
            'publication_month' => 'required|string|max:30',
            'publication_year' => 'required|integer|min:2000|max:2099',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        Issue::create([
            'volume_id' => $request->volume_id,
            'issue_number' => $request->issue_number,
            'title' => $request->title,
            'publication_month' => $request->publication_month,
            'publication_year' => $request->publication_year,
            'cover_image' => $coverPath,
            'is_published' => false,
        ]);

        return back()->with('success', 'Issue created successfully.');
    }

    public function publishIssue($id)
    {
        $issue = Issue::findOrFail($id);
        $issue->is_published = true;
        $issue->published_at = now();
        $issue->save();

        // Also publish assigned articles if any
        Article::where('issue_id', $issue->id)->update([
            'status' => 'Published',
            'published_at' => now(),
        ]);

        return back()->with('success', "Issue {$issue->issue_number} has been published successfully.");
    }
}
