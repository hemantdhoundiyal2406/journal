<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTimeline;
use Illuminate\Http\Request;

class AdminSubmissionInboxController extends Controller
{
    public function index()
    {
        $newSubmissions = Article::with(['authors', 'files'])
            ->whereIn('status', ['Submitted', 'Screening', 'Revised Received'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.inbox.index', compact('newSubmissions'));
    }

    public function triageAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:accept,reject,hold,revision,missing_files',
            'comment' => 'nullable|string|max:1000',
        ]);

        $article = Article::findOrFail($id);
        $oldStatus = $article->status;

        $newStatus = match ($request->action) {
            'accept' => 'Under Review', // Moved into peer review process
            'reject' => 'Rejected',
            'hold' => 'Screening',
            'revision' => 'Revision Required',
            'missing_files' => 'Revision Required',
        };

        $article->status = $newStatus;
        $article->save();

        ArticleTimeline::create([
            'article_id' => $article->id,
            'status_from' => $oldStatus,
            'status_to' => $newStatus,
            'comment' => "Inbox Action: " . strtoupper($request->action) . ". " . ($request->comment ?? ''),
            'created_by' => 'Editorial Triage Admin',
        ]);

        return back()->with('success', "Submission {$article->manuscript_id} updated to {$newStatus}.");
    }
}
