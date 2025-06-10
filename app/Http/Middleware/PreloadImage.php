<?php
namespace App\Http\Middleware;

use App\Services\ImageService;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class PreloadImage
{
    public function handle($request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if ($path = $request->attributes->get('preload_image')) {
            foreach (ImageService::preloadHeaders($path) as $header) {
                $response->headers->set('Link', $header, false);
            }
        }

        return $response;
    }
}
