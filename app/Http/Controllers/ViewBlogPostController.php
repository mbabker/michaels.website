<?php

namespace App\Http\Controllers;

use App\Sheets\BlogPost;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Sheets;

class ViewBlogPostController
{
    public function __invoke(Sheets $repository, string $slug): View
    {
        $blogRepository = $repository->collection('blog');

        /** @var BlogPost|null $post */
        $post = $blogRepository->all()->firstWhere('slug', '=', $slug);

        abort_if($post === null, 404);

        return view(
            'blog.show',
            [
                'post' => $post,
            ]
        );
    }
}
