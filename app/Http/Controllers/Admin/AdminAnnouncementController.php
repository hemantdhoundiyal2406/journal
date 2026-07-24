<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('id', 'desc')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:top_bar,popup_notice,latest_news,call_for_papers',
            'title' => 'required|string|max:200',
            'content' => 'required|string|max:2000',
            'link' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        Announcement::create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'link' => $validated['link'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Announcement created successfully.');
    }

    public function toggle($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        return back()->with('success', 'Announcement status updated.');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Announcement deleted successfully.');
    }
}
