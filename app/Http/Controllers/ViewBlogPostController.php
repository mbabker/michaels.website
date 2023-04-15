<?php

namespace App\Http\Controllers;

use App\Sheets\BlogPost;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;

final class ViewBlogPostController
{
    public function __invoke(string $slug): View
    {
        $blogRepository = Sheets::collection('blog');

        $post = $blogRepository->all()->firstWhere('slug', '=', $slug);

        abort_unless($post instanceof BlogPost, 404);

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
