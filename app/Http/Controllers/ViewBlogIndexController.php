<?php

namespace App\Http\Controllers;

use App\Pagination\RoutableLengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\AbstractPaginator;
use Spatie\Sheets\Sheets;

final class ViewBlogIndexController
{
    public function __invoke(Sheets $repository, int $page = 1): View
    {
        $blogRepository = $repository->collection('blog');

        $posts = $blogRepository->all()->sortByDesc('date');

        $paginator = app()->make(
            RoutableLengthAwarePaginator::class,
            [
                'items' => $posts->slice((($page - 1) * 5), 5),
                'total' => $posts->count(),
                'perPage' => 5,
                'currentPage' => $page,
                'options' => [
                    'path' => AbstractPaginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ],
            ]
        );

        abort_if($paginator->currentPage() > $paginator->lastPage(), 404);

        return view(
            'blog.index',
            [
                'posts' => $paginator,
            ]
        );
    }
}
