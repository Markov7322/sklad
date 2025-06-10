<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    public function __invoke()
    {
        $categories = Category::with(['skladchinas.participants'])->get();
        $firstImage = optional($categories->first()?->skladchinas?->first())->image_path;
        if ($firstImage) {
            request()->attributes->set('preload_image', $firstImage);
        }
        return view('home', compact('categories'));
    }
}
