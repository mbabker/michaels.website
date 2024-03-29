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
    public function test_the_page_schema_for_the_about_page_is_generated(): void
    {
        $this->assertInstanceOf(HtmlString::class, about_page_schema());
    }

    public function test_the_page_schema_for_the_blog_list_is_generated(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        $posts = $blogRepository->all()->sortByDesc('date');

        /** @var RoutableLengthAwarePaginator<BlogPost> $paginator */
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
            ],
        );

        $this->assertInstanceOf(HtmlString::class, blog_schema($paginator));
    }

    public function test_the_schema_object_for_a_blog_post_is_generated(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->assertInstanceOf(BlogPosting::class, blog_post_schema($post));
    }

    public function test_the_page_schema_for_a_blog_post_is_generated(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->assertInstanceOf(HtmlString::class, blog_post_schema_as_script($post));
    }

    public function test_the_schema_object_for_the_site_owner_is_generated(): void
    {
        $this->assertInstanceOf(Person::class, site_owner_schema());
    }

    public function test_the_page_schema_for_the_site_owner_is_generated(): void
    {
        $this->assertInstanceOf(HtmlString::class, site_owner_schema_as_script());
    }
}
