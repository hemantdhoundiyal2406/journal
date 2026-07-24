<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class AdminEmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.email_templates.index', compact('templates'));
    }

    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.email_templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:250',
            'body_html' => 'required|string',
        ]);

        $template = EmailTemplate::findOrFail($id);
        $template->update($validated);

        return redirect()->route('admin.email-templates.index')->with('success', "Email Template '{$template->name}' updated successfully.");
    }
}
