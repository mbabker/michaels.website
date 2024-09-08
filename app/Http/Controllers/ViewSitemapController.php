<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ViewSitemapController
{
    public function __invoke(): StreamedResponse
    {
        $disk = Storage::disk('local');

        abort_unless($disk->exists('sitemap.xml'), 404);

        return $disk->response('sitemap.xml');
    }
}
