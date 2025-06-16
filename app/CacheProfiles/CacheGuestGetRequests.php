<?php

namespace App\CacheProfiles;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

/**
 * Cache only successful GET requests for guests (unauthenticated users).
 */
class CacheGuestGetRequests extends CacheAllSuccessfulGetRequests
{
    public function shouldCacheRequest(Request $request): bool
    {
        if (auth()->check()) {
            return false;
        }

        if ($request->is('login') || $request->is('register')) {
            return false;
        }

        return parent::shouldCacheRequest($request);
    }
}
