<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ViewSitemapController
{
    public function __invoke(): BinaryFileResponse
    {
        $disk = Storage::disk('local');

        abort_unless($disk->exists('sitemap.xml'), 404);

        return response()->file($disk->path('sitemap.xml'), [
            'Content-Type' => 'text/xml',
        ]);
    }
}
