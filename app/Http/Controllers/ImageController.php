<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __invoke(string $path)
    {
        $path = ltrim($path, '/');
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $cached = ImageService::cachedPath($path);

        return response()->file($cached, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age='.(60 * 60 * 24 * 30),
        ]);
    }
}
