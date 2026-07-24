<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;

class AdminCmsController extends Controller
{
    public function index()
    {
        $pages = CmsPage::all();
        return view('admin.cms.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = CmsPage::findOrFail($id);
        return view('admin.cms.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:300',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $page = CmsPage::findOrFail($id);
        $page->update([
            'title' => $validated['title'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'content' => $validated['content'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.cms.index')->with('success', "CMS Page '{$page->title}' updated successfully.");
    }
}
