<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebPushController extends Controller
{
    public function saveSubscription(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        try {
            $request->user()->updatePushSubscription(
                endpoint: $data['endpoint'],
                key: $data['keys']['p256dh'],
                token: $data['keys']['auth'],
                contentEncoding: $request->header('Content-Encoding')
            );

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Failed to save push subscription: '.$e->getMessage());
            Log::channel('push')->error('Failed to save push subscription', ['exception' => $e]);

            return response()->json(['success' => false], 500);
        }
    }
}
