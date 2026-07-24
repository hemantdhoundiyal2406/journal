<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = JournalSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'journal_logo', 'hero_banner']);

        foreach ($data as $key => $val) {
            JournalSetting::setKey($key, $val);
        }

        if ($request->hasFile('journal_logo')) {
            $path = $request->file('journal_logo')->store('settings', 'public');
            JournalSetting::setKey('journal_logo', $path, 'general', 'image');
        }

        if ($request->hasFile('hero_banner')) {
            $path = $request->file('hero_banner')->store('settings', 'public');
            JournalSetting::setKey('hero_banner', $path, 'general', 'image');
        }

        return back()->with('success', 'Journal settings saved successfully.');
    }
}
