<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{
    public const SIZES = [100, 200, 300, 800, 1600];

    /**
     * Save uploaded file and generate watermarked thumbnails in AVIF and WEBP.
     */
    public static function saveUploadedAsWebp(UploadedFile $file, string $folder): array
    {
        $content = $file->get();
        $extension = strtolower($file->getClientOriginalExtension());
        $name = Str::uuid()->toString();

        $original = trim($folder, '/') . '/' . $name . '.' . $extension;
        Storage::disk('originals')->put($original, $content);

        $links = static::generateThumbnails($content, $folder, $name);

        return [
            'path' => trim($folder, '/') . '/' . $name . '.webp',
            'links' => $links,
        ];
    }

    public static function saveUrlAsWebp(string $url, string $folder): ?array
    {
        $contents = @file_get_contents($url);
        if ($contents === false) {
            return null;
        }
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        $name = Str::uuid()->toString();

        $original = trim($folder, '/') . '/' . $name . '.' . $extension;
        Storage::disk('originals')->put($original, $contents);

        $links = static::generateThumbnails($contents, $folder, $name);

        return [
            'path' => trim($folder, '/') . '/' . $name . '.webp',
            'links' => $links,
        ];
    }

    public static function cachedPath(string $path, int $width = 800): string
    {
        $disk = Storage::disk('images');
        $cache = $width . '/' . ltrim($path, '/');

        if (! $disk->exists($cache)) {
            $sourcePath = '800/' . ltrim($path, '/');
            if (! $disk->exists($sourcePath)) {
                abort(404);
            }

            $image = Image::make($disk->get($sourcePath));
            static::processImage($image, $width);
            $quality = in_array($width, [800, 1600], true) ? 60 : 50;
            $disk->put($cache, (string) $image->encode('webp', $quality));
        }

        return $cache;
    }

    protected static function generateThumbnails(string $content, string $folder, string $name): array
    {
        $links = [];
        foreach (self::SIZES as $size) {
            $image = Image::make($content);
            static::processImage($image, $size);
            $quality = in_array($size, [800, 1600], true) ? 60 : 50;

            $basePath = $size . '/' . trim($folder, '/') . '/' . $name;
            Storage::disk('images')->put($basePath . '.webp', (string) $image->encode('webp', $quality));
            Storage::disk('images')->put($basePath . '.avif', (string) $image->encode('avif', $quality));

            $links[$size] = [
                'webp' => $basePath . '.webp',
                'avif' => $basePath . '.avif',
            ];
        }

        return $links;
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

    public static function preloadHeaders(string $path): array
    {
        $sizes = '(max-width: 640px) 300px, 800px';
        $avifDesktop = asset('images/800/' . str_replace('.webp', '.avif', $path));
        $avifMobile = asset('images/300/' . str_replace('.webp', '.avif', $path));
        $webpDesktop = asset('images/800/' . $path);
        $webpMobile = asset('images/300/' . $path);

        return [
            "<{$avifDesktop}>; rel=preload; as=image; imagesrcset=\"{$avifMobile} 300w, {$avifDesktop} 800w\"; imagesizes=\"{$sizes}\"",
            "<{$webpDesktop}>; rel=preload; as=image; imagesrcset=\"{$webpMobile} 300w, {$webpDesktop} 800w\"; imagesizes=\"{$sizes}\"",
        ];
    }
}
