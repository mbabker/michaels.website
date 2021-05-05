<?php

use App\Sheets\BlogPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\HtmlString;
use Spatie\SchemaOrg\BlogPosting;
use Spatie\SchemaOrg\Person;
use Spatie\SchemaOrg\Schema;

function about_page_schema(): HtmlString
{
    $schema = Schema::aboutPage()
        ->mainEntityOfPage(
            Schema::webPage()
                ->url(route('about'))
        )
        ->headline('About Michael Babker')
    ;

    return new HtmlString($schema->toScript());
}

/**
 * @param LengthAwarePaginator<BlogPost> $posts
 */
function blog_schema(LengthAwarePaginator $posts): HtmlString
{
    /** @var BlogPosting[] $postSchemas */
    $postSchemas = [];

    foreach ($posts as $post) {
        $postSchemas[] = blog_post_schema($post);
    }

    $schema = Schema::blog()
        ->mainEntityOfPage(
            Schema::webPage()
                ->url(route('blog.index'))
        )
        ->headline("Michael Babker's Blog")
        ->author(site_owner_schema())
        ->blogPost($postSchemas)
    ;

    return new HtmlString($schema->toScript());
}

function blog_post_schema(BlogPost $post): BlogPosting
{
    return Schema::blogPosting()
        ->mainEntityOfPage(
            Schema::webPage()
                ->url($post->url)
        )
        ->headline($post->title)
        ->image(asset(sprintf('images/%s', $post->image ?: 'home-bg.jpg')))
        ->datePublished($post->published_date)
        ->dateModified($post->modified_date)
        ->author(
            Schema::person()
                ->name($post->author)
        )
        ->description($post->teaser)
    ;
}

function blog_post_schema_as_script(BlogPost $post): HtmlString
{
    return new HtmlString(blog_post_schema($post)->toScript());
}

function site_owner_schema(): Person
{
    return Schema::person()
        ->name('Michael Babker')
        ->url(route('homepage'))
        ->sameAs([
            'https://github.com/mbabker',
            'https://www.linkedin.com/in/mbabker',
            'https://twitter.com/mbabker',
        ])
    ;
}

function site_owner_schema_as_script(): HtmlString
{
    return new HtmlString(site_owner_schema()->toScript());
}
