<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;

final class HomepageController
{
    public function __invoke(): View
    {
        $blogRepository = Sheets::collection('blog');

        return view('homepage', [
            'latestPost' => $blogRepository->all()->last(),
        ]);
    }
}
