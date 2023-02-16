<?php

namespace Tests\Feature;

use App\Sheets\BlogPost;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Sheets\Sheets;
use Tests\TestCase;

final class BlogTest extends TestCase
{
    #[Test]
    public function users_can_view_the_blog_list(): void
    {
        $this->get('/blog')
            ->assertViewIs('blog.index');
    }

    #[Test]
    public function users_can_view_pages_from_the_blog_list(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        $postCount = $blogRepository->all()->count();

        if ($postCount < 5) {
            $this->markTestSkipped('Blog does not have multiple pages');
        }

        $this->get('/blog/page/2')
            ->assertViewIs('blog.index');
    }

    #[Test]
    public function users_are_redirected_to_the_canonical_first_page_of_the_blog_list(): void
    {
        $this->get('/blog/page/1')
            ->assertRedirect('/blog');
    }

    #[Test]
    public function the_blog_list_returns_a_404_if_navigating_outside_the_pagination_range(): void
    {
        $this->get('/blog/page/1000000')
            ->assertNotFound();
    }

    #[Test]
    public function users_can_view_blog_posts(): void
    {
        /** @var Sheets $repository */
        $repository = $this->app->make(Sheets::class);

        $blogRepository = $repository->collection('blog');

        /** @var BlogPost $post */
        $post = $blogRepository->all()->first();

        $this->get(sprintf('/blog/%s', $post->slug))
            ->assertViewIs('blog.show');
    }

    #[Test]
    public function an_invalid_blog_post_triggers_a_404(): void
    {
        $this->get('/blog/does-not-exist')
            ->assertNotFound();
    }
}
