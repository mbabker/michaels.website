<?php

namespace App\View\Components;

use App\Sheets\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class BlogPreview extends Component
{
    public BlogPost $post;

    public function __construct(BlogPost $post)
    {
        $this->post = $post;
    }

    public function render(): View
    {
        return view('components.blog-preview');
    }
}
