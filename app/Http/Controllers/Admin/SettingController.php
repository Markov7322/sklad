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
        $discount = Setting::value('repeat_discount_percent', 40);
        $days = Setting::value('default_access_days', 30);
        return view('admin.settings.edit', compact('percent', 'discount', 'days'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'organizer_share_percent' => 'required|numeric|min:0|max:100',
            'repeat_discount_percent' => 'required|numeric|min:0|max:100',
            'default_access_days' => 'required|integer|min:0',
        ]);
        Setting::updateOrCreate(
            ['key' => 'organizer_share_percent'],
            ['value' => $data['organizer_share_percent']]
        );
        Setting::updateOrCreate(
            ['key' => 'repeat_discount_percent'],
            ['value' => $data['repeat_discount_percent']]
        );
        Setting::updateOrCreate(
            ['key' => 'default_access_days'],
            ['value' => $data['default_access_days']]
        );
        return redirect()->route('admin.settings.edit');
    }
}
