<?php

namespace Tests\Feature;

use App\Sheets\BlogPost;
use Spatie\Sheets\Sheets;
use Tests\TestCase;

final class BlogTest extends TestCase
{
    /** @test */
    public function users_can_view_the_blog_list()
    {
        $this->get('/blog')
            ->assertViewIs('blog.index');
    }

    /** @test */
    public function users_can_view_pages_from_the_blog_list()
    {
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        $postCount = $blogRepository->all()->count();

        if ($postCount < 5) {
            $this->markTestSkipped('Blog does not have multiple pages');
        }

        $this->get('/blog/page/2')
            ->assertViewIs('blog.index');
    }

    /** @test */
    public function users_are_redirected_to_the_canonical_first_page_of_the_blog_list()
    {
        $this->get('/blog/page/1')
            ->assertRedirect('/blog');
    }

    /** @test */
    public function the_blog_list_returns_a_404_if_navigating_outside_the_pagination_range()
    {
        $this->get('/blog/page/1000000')
            ->assertNotFound();
    }

    /** @test */
    public function users_can_view_blog_posts()
    {
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->get(sprintf('/blog/%s', $post->slug))
            ->assertViewIs('blog.show');
    }

    /** @test */
    public function an_invalid_blog_post_triggers_a_404()
    {
        $this->get('/blog/does-not-exist')
            ->assertNotFound();
    }
}
