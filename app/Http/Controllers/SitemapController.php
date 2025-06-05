<?php

namespace App\Http\Controllers;

use App\Models\Skladchina;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $add = function(string $loc, ?string $lastmod = null) use ($xml) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $loc);
            if ($lastmod) {
                $url->addChild('lastmod', $lastmod);
            }
        };

        $add(route('home'));
        $add(route('skladchinas.index'));

        foreach (Category::all() as $category) {
            $add(route('categories.show', $category->slug));
        }

        foreach (Skladchina::all() as $skladchina) {
            $add(route('skladchinas.show', $skladchina), optional($skladchina->updated_at)->toAtomString());
        }

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }
}
