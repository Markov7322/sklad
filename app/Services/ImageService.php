<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{
    public const SIZES = [100, 200, 400, 800];

    public static function saveUploadedAsWebp(UploadedFile $file, string $folder): string
    {
        $content = $file->get();
        $extension = strtolower($file->getClientOriginalExtension());
        $name = Str::uuid()->toString();

        $original = trim($folder, '/') . '/' . $name . '.' . $extension;
        Storage::disk('originals')->put($original, $content);

        static::generateThumbnails($content, $folder, $name);

        return trim($folder, '/') . '/' . $name . '.webp';
    }

    public static function saveUrlAsWebp(string $url, string $folder): ?string
    {
        $contents = @file_get_contents($url);
        if ($contents === false) {
            return null;
        }
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        $name = Str::uuid()->toString();

        $original = trim($folder, '/') . '/' . $name . '.' . $extension;
        Storage::disk('originals')->put($original, $contents);

        static::generateThumbnails($contents, $folder, $name);

        return trim($folder, '/') . '/' . $name . '.webp';
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
            $quality = $width === 800 ? 70 : 60;
            $disk->put($cache, (string) $image->encode('webp', $quality));
        }

        return $cache;
    }

    protected static function generateThumbnails(string $content, string $folder, string $name): void
    {
        foreach (self::SIZES as $size) {
            $image = Image::make($content);
            static::processImage($image, $size);
            $path = $size . '/' . trim($folder, '/') . '/' . $name . '.webp';
            $quality = $size === 800 ? 70 : 60;
            Storage::disk('images')->put($path, (string) $image->encode('webp', $quality));
        }
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
