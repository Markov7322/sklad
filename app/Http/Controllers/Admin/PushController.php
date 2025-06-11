<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\SiteNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PushController extends Controller
{
    public function create()
    {
        return view('admin.push.create');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'url' => 'nullable|string',
        ]);

        Notification::send(User::where('notify_site', true)->get(), new SiteNotification($data['title'], $data['message'], $data['url']));

        return back()->with('status', 'sent');
    }
}
