<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{
    public static function saveUploadedAsWebp(UploadedFile $file, string $folder, int $width = 1200): string
    {
        $content = $file->get();

        if (strtolower($file->getClientOriginalExtension()) === 'webp') {
            $original = trim($folder, '/') . '/original_' . Str::random(40) . '.webp';
            Storage::disk('images')->put($original, $content);
        } else {
            $original = 'originals/' . trim($folder, '/') . '/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
            Storage::disk('originals')->put($original, $content);
        }

        return static::saveAsWebp($content, $folder, $width);
    }

    public static function saveUrlAsWebp(string $url, string $folder, int $width = 1200): ?string
    {
        $contents = @file_get_contents($url);
        if ($contents === false) {
            return null;
        }
        $original = 'originals/' . trim($folder, '/') . '/' . Str::random(40) . '.' . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        Storage::disk('originals')->put($original, $contents);
        return static::saveAsWebp($contents, $folder, $width);
    }

    public static function cachedPath(string $path, int $width = 600): string
    {
        $cache = 'cache/'.$width.'/'.ltrim($path, '/');
        $disk = Storage::disk('images');
        if (! $disk->exists($cache)) {
            if (! $disk->exists($path)) {
                abort(404);
            }
            $image = Image::make($disk->get($path));
            static::processImage($image, $width);
            $disk->put($cache, (string) $image->encode('webp', 80));
        }
        return $disk->path($cache);
    }

    protected static function saveAsWebp(string $content, string $folder, int $width = 1200): string
    {
        $name = trim($folder, '/').'/'.Str::random(40).'.webp';
        $image = Image::make($content);
        static::processImage($image, $width);
        Storage::disk('images')->put($name, (string) $image->encode('webp', 80));
        return $name;
    }

    protected static function processImage($image, int $width): void
    {
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        static::addWatermark($image);
    }

    protected static function addWatermark($image): void
    {
        $width = $image->width();
        $height = $image->height();
        $canvas = Image::canvas($width, $height);

        $text = 'tg.skladmk.ru';
        $fontSize = 14;
        $angle = -30;
        $opacity = 0.15;

        $approxWidth = strlen($text) * $fontSize * 0.6;
        $approxHeight = $fontSize;
        $xStep = $approxWidth + 10;
        $yStep = $approxHeight + 10;

        $row = 0;
        for ($y = 0; $y <= $height + $approxHeight; $y += $yStep) {
            $startX = ($row % 2 === 0) ? 0 : $xStep / 2;
            for ($x = $startX; $x <= $width + $approxWidth; $x += $xStep) {
                $canvas->text($text, $x, $y, function ($font) use ($fontSize, $angle, $opacity) {
                    $font->size($fontSize);
                    $font->color('rgba(255,255,255,' . $opacity . ')');
                    $font->angle($angle);
                });
            }
            $row++;
        }

        $image->insert($canvas);
    }
}
