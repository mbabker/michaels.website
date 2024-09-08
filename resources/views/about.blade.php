@extends('layouts.app', [
    'title' => sprintf('About Me | %s', config('app.name', "Michael's Website")),
    'description' => 'About Michael Babker',
    'ogType' => 'profile',
])

@section('meta')
    <link rel="canonical" href="{{ route('about') }}" />
    <meta property="og:profile:first_name" content="Michael" />
    <meta property="og:profile:last_name" content="Babker" />
    <meta property="og:profile:username" content="mbabker" />
    <meta property="og:profile:gender" content="male" />
    {!! about_page_schema() !!}
@endsection

@section('content')
    <article class="flex flex-col shadow my-4">
        <div class="bg-white flex flex-col justify-start p-6">
            <h1 class="text-3xl font-bold pb-4">About Me</h1>

            <p class="text-lg text-gray-500 pb-6">
                Army vet, IT geek, Open Source aficionado. Holds a prejudice against crap code and openly shares it.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-[60%_40%] gap-4">
                <div class="lg:col-start-2 place-self-center">
                    <img src="{{ asset('images/michael-2021.webp') }}" alt="Michael">
                </div>
                <div class="col-start-1 lg:row-start-1">
                    <p class="pb-3">Michael Babker is a veteran of the Information Technology industry who brings nearly 20 years of knowledge and experience to the table. After finding a passion for building with and contributing to the open-source software community, he has transitioned to and works as a backend software developer for web-facing platforms. He is a past contributor to Joomla where he has served as the release coordinator for four of their feature releases and dozens of maintenance releases, a member of the Board of Directors of Open Source Matters, Inc. (the not-for-profit organization supporting the Joomla project), and as a member of its security team. Today, he is a regular contributor to the PHP ecosystem, focused primarily on integrations with Doctrine and Symfony, as well as a maintainer for <a class="text-blue-500 hover:text-blue-800" href="https://www.babdev.com" rel="nofollow noopener">a number of his own PHP packages</a>.</p>
                    <p class="pb-3">Before transitioning into web development as a full-time career, he served for nine years in the United States Army as an Information Technology Specialist, where his duties were focused in roles including Help Desk Team Leader, Senior Information Systems Support Specialist, and Information Assurance Security Officer. As a Sergeant in the Army, he was directly responsible for the morale, welfare, and professional development of a team of junior Soldiers.</p>
                    <p class="pb-3">Michael holds an Associate of Arts in Information Technology with a concentration in Web Design and is currently the Lead Data Architect for  <a class="text-blue-500 hover:text-blue-800" href="https://happydog.digital" rel="nofollow noopener">Happy Dog</a>.</p>
                </div>
                <div class="lg:col-span-2 place-self-center">
                    <img src="{{ asset('images/mil-sig.webp') }}" alt="militarysignatures.com" loading="lazy">
                </div>
            </div>
        </div>
    </article>
@endsection
