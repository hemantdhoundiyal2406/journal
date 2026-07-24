<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;
use Illuminate\Http\Request;

class AdminReviewerController extends Controller
{
    public function index(Request $request)
    {
        $query = Reviewer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('expertise', 'LIKE', "%{$search}%")
                    ->orWhere('university', 'LIKE', "%{$search}%")
                    ->orWhere('country', 'LIKE', "%{$search}%");
            });
        }

        $reviewers = $query->orderBy('name')->paginate(15);
        return view('admin.reviewers.index', compact('reviewers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'expertise' => 'required|string|max:500',
            'university' => 'required|string|max:200',
            'country' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        Reviewer::create($validated);

        return back()->with('success', 'Reviewer added to database successfully.');
    }

    public function destroy($id)
    {
        Reviewer::findOrFail($id)->delete();
        return back()->with('success', 'Reviewer removed successfully.');
    }
}
