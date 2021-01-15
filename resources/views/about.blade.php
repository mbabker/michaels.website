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

            <div>
                <img class="float-right ml-4 my-2" src="{{ asset('images/about-michael.jpg') }}" alt="Michael">

                <p class="pb-3">Michael Babker is a veteran of the Information Technology industry who brings over 15 years of knowledge and experience to the table. After finding a passion for building with and contributing to open source, he has transitioned to and works primarily in a web developer role. He is a past contributor to Joomla where he has served as the release coordinator for four of Joomla's feature releases, a member of the Board of Directors of Open Source Matters, Inc. (the not-for-profit organization supporting the Joomla project), and as a member of its security team.</p>
                <p class="pb-3">Before transitioning into web development as a full time career, he served for nine years in the United States Army as an Information Technology Specialist and served in several roles including Help Desk Team Leader, Senior Information Systems Support Specialist, and Information Assurance Security Officer. As a Sergeant in the Army, he was directly responsible for the morale, welfare, and professional development of a team of junior Soldiers.</p>
                <p class="pb-3">Michael holds an Associate of Arts in Information Technology with a concentration in Web Design and is currently the Lead Data Architect for <a class="text-blue-500 hover:text-blue-800" href="https://happydog.digital" rel="nofollow noopener">Happy Dog</a>. When not actively coding, Michael can often be found engaged with the open source community as an advocate and evangelist for the continued growth and use of open software and the open web.</p>
            </div>

            <img class="mx-auto" src="{{ asset('images/mil-sig.png') }}" alt="militarysignatures.com" loading="lazy">
        </div>
    </article>
@endsection
