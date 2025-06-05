<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{
    public static function saveUploadedAsWebp(UploadedFile $file, string $folder): string
    {
        return static::saveAsWebp($file->get(), $folder);
    }

    public static function saveUrlAsWebp(string $url, string $folder): ?string
    {
        $contents = @file_get_contents($url);
        if ($contents === false) {
            return null;
        }
        return static::saveAsWebp($contents, $folder);
    }

    public static function cachedPath(string $path): string
    {
        $cache = 'cache/'.ltrim($path, '/');
        $disk = Storage::disk('public');
        if (! $disk->exists($cache)) {
            if (! $disk->exists($path)) {
                abort(404);
            }
            $image = Image::make($disk->get($path))->encode('webp', 100);
            $disk->put($cache, (string) $image);
        }
        return $disk->path($cache);
    }

    protected static function saveAsWebp(string $content, string $folder): string
    {
        $name = trim($folder, '/').'/'.Str::random(40).'.webp';
        $image = Image::make($content)->encode('webp', 100);
        Storage::disk('public')->put($name, (string) $image);
        return $name;
    }
}
