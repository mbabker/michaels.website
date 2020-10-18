@php /** @var \App\Sheets\BlogPost $post */ @endphp

<article class="flex flex-col shadow my-4" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="Michael Babker">
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="{{ asset('images/about-michael.jpg') }}">
        </div>
    </div>
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{ $post->url }}">
    <meta itemprop="image" content="{{ $post->image ? asset(sprintf('images/%s', $post->image)) : asset('images/home-bg.jpg') }}">
    <meta itemprop="dateModified" content="{{ $post->modified_date->format('c') }}">

    <a href="{{ $post->url }}" class="hover:opacity-75">
        <img src="{{ asset(sprintf('images/%s', $post->image ?: 'home-bg.jpg')) }}">
    </a>

    <div class="bg-white flex flex-col justify-start p-6">
        <a href="{{ $post->url }}" class="text-3xl font-bold hover:text-gray-700 pb-4">{{ $post->title }}</a>
        <p class="text-sm pb-3">
            By <span itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">{{ $post->author }}</span></span>, Published on <time datetime="{{ $post->published_date->format('c') }}" itemprop="datePublished">{{ $post->published_date->format('F j, Y') }}</time>
        </p>
        <p class="pb-6" itemprop="description">{{ $post->teaser }}</p>
        <a href="{{ $post->url }}" class="uppercase text-gray-800 hover:text-black">Continue Reading <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
    </div>
</article>
