<?php

namespace App\Observers;

use Spatie\ResponseCache\Facades\ResponseCache;

class FlushResponseCacheObserver
{
    public function saved(): void
    {
        ResponseCache::clear();
    }

    public function deleted(): void
    {
        ResponseCache::clear();
    }

    public function restored(): void
    {
        ResponseCache::clear();
    }
}
