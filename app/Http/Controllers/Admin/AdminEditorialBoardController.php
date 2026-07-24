<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EditorialMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEditorialBoardController extends Controller
{
    public function index()
    {
        $members = EditorialMember::orderBy('sort_order')->get();
        return view('admin.editorial.index', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'designation' => 'required|string|max:150',
            'university' => 'required|string|max:200',
            'country' => 'required|string|max:100',
            'biography' => 'nullable|string|max:2000',
            'orcid' => 'nullable|string|max:50',
            'google_scholar' => 'nullable|url|max:255',
            'photo' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('editorial', 'public');
        }

        EditorialMember::create([
            'name' => $validated['name'],
            'designation' => $validated['designation'],
            'university' => $validated['university'],
            'country' => $validated['country'],
            'biography' => $validated['biography'] ?? null,
            'orcid' => $validated['orcid'] ?? null,
            'google_scholar' => $validated['google_scholar'] ?? null,
            'photo' => $photoPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Editorial board member added successfully.');
    }

    public function destroy($id)
    {
        $member = EditorialMember::findOrFail($id);
        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }
        $member->delete();

        return back()->with('success', 'Editorial member deleted successfully.');
    }
}
