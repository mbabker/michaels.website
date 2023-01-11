<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Spatie\Sheets\Sheets;

final class HomepageController
{
    public function __invoke(Sheets $repository): View
    {
        $blogRepository = $repository->collection('blog');

        return view('homepage', [
            'latestPost' => $blogRepository->all()->last(),
        ]);
    }
}
