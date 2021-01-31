<?php

namespace Tests\Unit;

use App\Pagination\RoutableLengthAwarePaginator;
use App\Sheets\BlogPost;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\HtmlString;
use Spatie\SchemaOrg\BlogPosting;
use Spatie\SchemaOrg\Person;
use Spatie\Sheets\Sheets;
use Tests\TestCase;

final class SchemaTest extends TestCase
{
    /** @test */
    public function the_page_schema_for_the_about_page_is_generated()
    {
        $this->assertInstanceOf(HtmlString::class, about_page_schema());
    }

    /** @test */
    public function the_page_schema_for_the_blog_list_is_generated()
    {
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        $posts = $blogRepository->all()->sortByDesc('date');

        $paginator = $this->app->make(
            RoutableLengthAwarePaginator::class,
            [
                'items' => $posts->slice(0, 5),
                'total' => $posts->count(),
                'perPage' => 5,
                'currentPage' => 1,
                'options' => [
                    'path' => AbstractPaginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ],
            ]
        );

        $this->assertInstanceOf(HtmlString::class, blog_schema($paginator));
    }

    /** @test */
    public function the_schema_object_for_a_blog_post_is_generated()
    {
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->assertInstanceOf(BlogPosting::class, blog_post_schema($post));
    }

    /** @test */
    public function the_page_schema_for_a_blog_post_is_generated()
    {
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->assertInstanceOf(HtmlString::class, blog_post_schema_as_script($post));
    }

    /** @test */
    public function the_schema_object_for_the_site_owner_is_generated()
    {
        $this->assertInstanceOf(Person::class, site_owner_schema());
    }

    /** @test */
    public function the_page_schema_for_the_site_owner_is_generated()
    {
        $this->assertInstanceOf(HtmlString::class, site_owner_schema_as_script());
    }
}
