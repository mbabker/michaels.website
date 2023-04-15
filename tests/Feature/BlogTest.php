<?php

namespace Tests\Feature;

use App\Sheets\BlogPost;
use Spatie\Sheets\Sheets;
use Tests\TestCase;

final class BlogTest extends TestCase
{
    public function test_users_can_pull_the_blog_feed(): void
    {
        $this->get('/feeds/blog')
            ->assertOk();
    }

    public function test_users_can_view_the_blog_list(): void
    {
        $this->get('/blog')
            ->assertOk()
            ->assertViewIs('blog.index');
    }

    public function test_users_can_view_pages_from_the_blog_list(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        $postCount = $blogRepository->all()->count();

        if ($postCount < 5) {
            $this->markTestSkipped('Blog does not have multiple pages');
        }

        $this->get('/blog/page/2')
            ->assertOk()
            ->assertViewIs('blog.index');
    }

    public function test_users_are_redirected_to_the_canonical_first_page_of_the_blog_list(): void
    {
        $this->get('/blog/page/1')
            ->assertRedirect('/blog');
    }

    public function test_the_blog_list_returns_a_404_if_navigating_outside_the_pagination_range(): void
    {
        $this->get('/blog/page/1000000')
            ->assertNotFound();
    }

    public function test_users_can_view_blog_posts(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->get(sprintf('/blog/%s', $post->slug))
            ->assertOk()
            ->assertViewIs('blog.show');
    }

    public function test_an_invalid_blog_post_triggers_a_404(): void
    {
        $this->get('/blog/does-not-exist')
            ->assertNotFound();
    }
}
