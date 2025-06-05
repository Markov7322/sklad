<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Skladchina;
use App\Models\SkladchinaImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class SkladchinaImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $path = $request->file('file')->store('tmp');
        $headers = (new HeadingRowImport)->toArray($path)[0] ?? [];
        $rows = Excel::toArray([], $path)[0] ?? [];
        $data = array_slice($rows, 1, 5);

        return view('admin.import.preview', [
            'path' => $path,
            'headers' => $headers,
            'rows' => $data,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'mapping' => 'required|array',
            'category_id' => 'required|exists:categories,id',
        ]);

        $rows = Excel::toArray([], $request->input('path'))[0] ?? [];
        $headers = array_shift($rows);

        $indexes = [];
        foreach ($request->input('mapping') as $field => $header) {
            $idx = array_search($header, $headers);
            if ($idx !== false) {
                $indexes[$field] = $idx;
            }
        }

        foreach ($rows as $row) {
            $images = [];
            if (isset($indexes['images']) && ! empty($row[$indexes['images']])) {
                $images = array_map('trim', explode(',', $row[$indexes['images']]));
            }

            $cover = null;
            $gallery = [];
            foreach ($images as $i => $url) {
                if ($saved = $this->downloadImage($url)) {
                    if ($i === 0) {
                        $cover = $saved;
                    } else {
                        $gallery[] = $saved;
                    }
                }
            }

            $skladchina = Skladchina::create([
                'name' => $row[$indexes['name'] ?? -1] ?? 'Без названия',
                'description' => $row[$indexes['description'] ?? -1] ?? null,
                'image_path' => $cover,
                'full_price' => (float) ($row[$indexes['full_price'] ?? -1] ?? 0),
                'member_price' => (float) ($row[$indexes['member_price'] ?? -1] ?? 0),
                'status' => Skladchina::STATUS_DONATION,
                'organizer_id' => $request->user()->id,
                'category_id' => $request->input('category_id'),
            ]);

            foreach ($gallery as $pos => $img) {
                $skladchina->images()->create([
                    'path' => $img,
                    'position' => $pos,
                ]);
            }
        }

        Storage::delete($request->input('path'));

        return redirect()->route('admin.skladchinas.index');
    }

    protected function downloadImage(string $url): ?string
    {
        try {
            $contents = @file_get_contents($url);
            if ($contents === false) {
                return null;
            }
            $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $name = 'skladchina_photos/' . Str::random(40) . '.' . $ext;
            Storage::disk('public')->put($name, $contents);
            return $name;
        } catch (\Exception $e) {
            return null;
        }
    }
}
