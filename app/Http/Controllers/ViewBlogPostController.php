<?php

namespace App\Http\Controllers;

use App\Sheets\BlogPost;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Sheets;

final class ViewBlogPostController
{
    public function __invoke(Sheets $repository, string $slug): View
    {
        $blogRepository = $repository->collection('blog');

        $post = $blogRepository->all()->firstWhere('slug', '=', $slug);

        abort_unless($post instanceof BlogPost, 404);

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
