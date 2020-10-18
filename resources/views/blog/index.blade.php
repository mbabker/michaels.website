@php /** @var \App\Pagination\RoutableLengthAwarePaginator|\App\Sheets\BlogPost[] $posts */ @endphp

@extends('layouts.app', [
    'title' => sprintf('%sBlog | %s', ($posts->currentPage() > 1 ? sprintf('Page %d | ', $posts->currentPage()) : ''), config('app.name', "Michael's Website")),
    'description' => "Michael Babker's Blog",
])

@section('meta')
    @if($posts->currentPage() > 1)
        <link rel="canonical" href="{{ route('blog.index') }}" />
    @endif
@endsection

@section('content')
    <div itemscope itemtype="https://schema.org/Blog">
        <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{ url()->current() }}">
        @foreach($posts as $post)
            <x-blog-preview :post="$post" />
        @endforeach
    </div>
    {{ $posts->links() }}
@endsection
