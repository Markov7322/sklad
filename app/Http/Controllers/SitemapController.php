<?php

namespace App\Http\Controllers;

use App\Models\Skladchina;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        // создаём корневой элемент <urlset>
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        // Расширяем функцию-добавления: add(loc, lastmod, changefreq, priority)
        $add = function(string $loc, ?string $lastmod = null, ?string $changefreq = null, ?string $priority = null) use ($xml) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $loc);
            if ($lastmod) {
                $url->addChild('lastmod', $lastmod);
            }
            if ($changefreq) {
                $url->addChild('changefreq', $changefreq);
            }
            if ($priority) {
                $url->addChild('priority', $priority);
            }
        };

        // Главная страница
        $add(
            route('home'),
            now()->toAtomString(),    // текущая дата/время
            'daily',                  // обновляется каждый день
            '1.0'                     // наивысший приоритет
        );

        // Страница списка всех складчин
        $add(
            route('skladchinas.index'),
            now()->toAtomString(),
            'daily',
            '0.9'
        );

        // Категории
        foreach (Category::all() as $category) {
            $add(
                route('categories.show', $category->slug),
                optional($category->updated_at)->toAtomString(),
                'monthly',    // предположим, что категория меняется раз в месяц
                '0.6'
            );
        }

        // Конкретные складчины (идентификатором свои updated_at)
        foreach (Skladchina::all() as $skladchina) {
            $add(
                route('skladchinas.show', $skladchina),
                optional($skladchina->updated_at)->toAtomString(),
                'weekly',     // складчина может обновляться еженедельно
                '0.8'
            );
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
