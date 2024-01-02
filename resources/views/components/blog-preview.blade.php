@php /** @var \App\Sheets\BlogPost $post */ @endphp

<article class="flex flex-col shadow my-4">
    <a href="{{ $post->url }}" class="hover:opacity-75">
        <img src="{{ asset(sprintf('images/%s', $post->image ?: 'home-bg.webp')) }}" class="w-full" alt="" loading="lazy">
    </a>

    <div class="bg-white flex flex-col justify-start p-6">
        <a href="{{ $post->url }}" class="text-3xl font-bold hover:text-gray-600 pb-4">{{ $post->title }}</a>
        <p class="text-sm pb-3">
            By {{ $post->author }}, Published on <time datetime="{{ $post->published_date->format('c') }}">{{ $post->published_date->format('F j, Y') }}</time>
        </p>
        <p class="pb-6">{{ $post->teaser }}</p>
        <a href="{{ $post->url }}" class="uppercase text-gray-800 hover:text-black">Continue Reading <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
    </div>
</article>
