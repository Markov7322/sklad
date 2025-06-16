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
use App\Models\User;

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
        $rows = Excel::toArray([], Storage::path($path))[0] ?? [];
        $headers = array_shift($rows) ?? [];
        $data = array_slice($rows, 0, 5);

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
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(Skladchina::statuses())),
            'organizer_id' => 'required|exists:users,id',
        ]);

        if (! $request->input('category_id') && ! $request->filled('new_category')) {
            return back()->withErrors(['category_id' => 'Укажите категорию или создайте новую'])->withInput();
        }

        $rows = Excel::toArray([], Storage::path($request->input('path')))[0] ?? [];
        $headers = array_shift($rows);

        if ($request->filled('new_category')) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($request->input('new_category'))],
                ['name' => $request->input('new_category')]
            );
            $categoryId = $category->id;
        } else {
            $categoryId = $request->input('category_id');
        }

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
            $coverLinks = null;
            $gallery = [];
            foreach ($images as $i => $url) {
                if ($saved = $this->downloadImage($url)) {
                    if ($i === 0) {
                        $cover = $saved['path'];
                        $coverLinks = $saved['links'];
                    } else {
                        $gallery[] = $saved;
                    }
                }
            }

            $name = $row[$indexes['name'] ?? -1] ?? 'Без названия';
            $slug = Str::slug($name);
            $original = $slug;
            $suffix = 1;
            while (Skladchina::where('slug', $slug)->exists()) {
                $slug = $original.'-'.$suffix++;
            }

            $skladchina = Skladchina::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $row[$indexes['description'] ?? -1] ?? null,
                'image_path' => $cover,
                'image_links' => $coverLinks,
                'full_price' => (float) ($row[$indexes['full_price'] ?? -1] ?? 0),
                'member_price' => (float) ($row[$indexes['member_price'] ?? -1] ?? 0),
                'status' => $request->input('status'),
                'organizer_id' => $request->input('organizer_id'),
                'category_id' => $categoryId,
            ]);

            foreach ($gallery as $pos => $img) {
                $skladchina->images()->create([
                    'path' => $img['path'],
                    'image_links' => $img['links'],
                    'position' => $pos,
                ]);
            }
        }

        Storage::delete($request->input('path'));

        return redirect()->route('admin.skladchinas.index');
    }

    protected function downloadImage(string $url): ?array
    {
        try {
            return \App\Services\ImageService::saveUrlAsWebp($url, 'skladchina_photos');
        } catch (\Exception $e) {
            return null;
        }
    }
}
