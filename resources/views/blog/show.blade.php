@php /** @var \App\Sheets\BlogPost $post */ @endphp

@extends('layouts.app', [
    'title' => sprintf('%s | %s', $post->title, config('app.name', "Michael's Website")),
    'ogType' => 'article',
])

@section('meta')
    <meta property="og:article:published_time" content="{{ $post->published_date->format('c') }}" />
    <meta property="og:article:modified_time" content="{{ $post->modified_date->format('c') }}" />
    <meta property="og:article:author" content="{{ $post->author }}" />
    {!! blog_post_schema_as_script($post) !!}
@endsection

@section('content')
    <article class="flex flex-col shadow my-4">
        <img src="{{ asset(sprintf('images/%s', $post->image ?: 'home-bg.jpg')) }}">

        <div class="bg-white flex flex-col justify-start p-6">
            <h1 class="text-3xl font-bold pb-4">{{ $post->title }}</h1>
            <p class="text-sm pb-3">
                By {{ $post->author }}, Published on <time datetime="{{ $post->published_date->format('c') }}">{{ $post->published_date->format('F j, Y') }}</time>
            </p>
            <div class="blog-contents">
                {!! $post->contents !!}
            </div>
        </div>
    </article>
@endsection
