@php /** @var \App\Pagination\RoutableLengthAwarePaginator<\App\Sheets\BlogPost> $posts */ @endphp

@extends('layouts.app', [
    'title' => sprintf('%sBlog | %s', ($posts->currentPage() > 1 ? sprintf('Page %d | ', $posts->currentPage()) : ''), config('app.name', "Michael's Website")),
    'description' => "Michael Babker's Blog",
])

@section('meta')
    @if($posts->currentPage() > 1)
        <link rel="canonical" href="{{ route('blog.index') }}" />
    @endif
    {!! blog_schema($posts) !!}
@endsection

@section('content')
    @foreach($posts as $post)
        <x-blog-preview :post="$post" />
    @endforeach
    {{ $posts->links() }}
@endsection
