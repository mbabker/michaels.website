@php /** @var \App\Pagination\RoutableLengthAwarePaginator<\App\Sheets\BlogPost> $posts */ @endphp

@extends('layouts.app', [
    'title' => sprintf('%sBlog | %s', ($posts->currentPage() > 1 ? sprintf('Page %d | ', $posts->currentPage()) : ''), config()->string('app.name', "Michael's Website")),
    'description' => "Michael Babker's Blog",
])

@section('meta')
    <link rel="alternate" type="application/atom+xml" title="Michael's Blog" href="{{ route('feeds.blog') }}">
    @unless($posts->onFirstPage())
        <link rel="canonical" href="{!! route('blog.index') !!}" />
        <link rel="prev" href="{!! $posts->currentPage() - 1 === 1 ? route('blog.index') : $posts->previousPageUrl() !!}" />
    @endunless
    @if($posts->hasMorePages())
        <link rel="next" href="{!! $posts->nextPageUrl() !!}" />
    @endif
    {!! blog_schema($posts) !!}
@endsection

@section('content')
    @foreach($posts as $post)
        <x-blog-preview :post="$post" />
    @endforeach
    {{ $posts->links() }}
@endsection
