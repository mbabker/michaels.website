<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $description ?? "Michael Babker's Personal Website" }}" />
        <meta property="og:description" content="{{ $description ?? "Michael Babker's Personal Website" }}" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="Michael's Website" />
        <meta property="og:title" content="{{ $ogTitle ?? $title ?? config('app.name', "Michael's Website") }}">
        <meta property="og:type" content="{{ $ogType ?? 'website' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:description" content="{{ $description ?? "Michael Babker's Personal Website" }}" />
        <meta name="twitter:title" content="{{ $ogTitle ?? $title ?? config('app.name', "Michael's Website") }}">
        @yield('meta')
        {!! site_owner_schema_as_script() !!}
        <title>{{ $title ?? config('app.name', "Michael's Website") }}</title>
        @googlefonts
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-gray-100">
        <nav class="w-full py-4 bg-babdev-blue shadow">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between">
                <nav class="w-full sm:w-auto">
                    <ul class="flex items-center justify-center sm:justify-between font-bold text-sm text-white uppercase no-underline">
                        <li><a class="hover:text-gray-200 hover:underline px-4" href="{{ route('about') }}">About</a></li>
                        <li><a class="hover:text-gray-200 hover:underline px-4" href="{{ route('blog.index') }}">Blog</a></li>
                    </ul>
                </nav>

                <div class="w-full sm:w-auto pt-4 sm:pt-0">
                    <div class="flex items-center justify-center sm:justify-between text-lg no-underline text-white">
                        <a class="hover:text-gray-200 hover:underline px-3" href="https://github.com/mbabker" rel="nofollow noopener">
                            <i class="fab fa-github" aria-hidden="true"></i>
                            <span class="sr-only">GitHub</span>
                        </a>
                        <a class="hover:text-gray-200 hover:underline px-3" href="https://www.instagram.com/michael.babker/" rel="nofollow noopener">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                            <span class="sr-only">Instagram</span>
                        </a>
                        <a class="hover:text-gray-200 hover:underline px-3" href="https://www.linkedin.com/in/mbabker" rel="nofollow noopener">
                            <i class="fab fa-linkedin" aria-hidden="true"></i>
                            <span class="sr-only">LinkedIn</span>
                        </a>
                        <a class="hover:text-gray-200 hover:underline px-3" href="https://fosstodon.org/@mbabker" rel="nofollow noopener me">
                            <i class="fab fa-mastodon" aria-hidden="true"></i>
                            <span class="sr-only">Mastodon</span>
                        </a>
                        <a class="hover:text-gray-200 hover:underline px-3" href="https://stackoverflow.com/users/498353/michael" rel="nofollow noopener">
                            <i class="fab fa-stack-overflow" aria-hidden="true"></i>
                            <span class="sr-only">Stack Overflow</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <header class="w-full container mx-auto">
            <div class="flex flex-col items-center text-center py-12">
                <a class="font-bold text-gray-800 uppercase hover:text-gray-600 text-5xl" href="{{ route('homepage') }}">
                    Michael's Website
                </a>
            </div>
        </header>

        <main class="container mx-auto flex flex-wrap">
            <section class="w-4/5 md:w-3/4 flex flex-col items-center mx-auto">
                @yield('content')
            </section>
        </main>

        <footer class="w-full border-t bg-white pb-12">
            <div class="w-full container mx-auto flex flex-col items-center">
                <div class="flex flex-col md:flex-row text-center md:text-left md:justify-between py-6">
                    <a href="{{ route('privacy') }}" class="uppercase px-3">Privacy Policy</a>
                </div>
                <div class="uppercase pb-6">&copy; 2014 - {{ date('Y') }} Michael Babker</div>
            </div>
        </footer>

        <script src="{{ PushManager::preload(asset('build/js/fontawesome.min.js'), ['as' => 'script']) }}"></script>
        @yield('bodyScripts')
    </body>
</html>
