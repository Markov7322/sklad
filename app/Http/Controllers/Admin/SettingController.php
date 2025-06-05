<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $percent = Setting::value('organizer_share_percent', 70);
        return view('admin.settings.edit', compact('percent'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'organizer_share_percent' => 'required|numeric|min:0|max:100',
        ]);
        Setting::updateOrCreate(
            ['key' => 'organizer_share_percent'],
            ['value' => $data['organizer_share_percent']]
        );
        return redirect()->route('admin.settings.edit');
    }
}
