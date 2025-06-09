<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __invoke(string $path)
    {
        $path = ltrim($path, '/');
        if (!Storage::disk('images')->exists('800/'.$path)) {
            abort(404);
        }

        $width = (int) request('w', 800);
        if ($width <= 0) {
            $width = 800;
        }

        $relative = ImageService::cachedPath($path, $width);
        $cached = Storage::disk('images')->path($relative);

        return response()->file($cached, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age='.(60 * 60 * 24 * 30),
        ]);
    }
}
