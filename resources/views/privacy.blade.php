@extends('layouts.app', [
    'title' => sprintf('Privacy | %s', config()->string('app.name', "Michael's Website")),
])

@section('content')
    <article class="flex flex-col shadow my-4">
        <div class="bg-white flex flex-col justify-start p-6">
            <h1 class="text-3xl font-bold pb-4">Privacy Policy</h1>

            <p class="text-sm pb-3">Last Updated: February 10, 2022</p>

            <p class="pb-3">Your privacy is important to me, therefore this site is designed to collect as little information about my visitors as possible, with the information being gathered strictly for informational/analytical purposes only.</p>

            <h2 class="text-2xl font-bold pb-3">Collected Information</h2>
            <p class="pb-3">Some potentially identifying information is collected as a result of visiting this website. This information, and the purpose of its collection, includes:</p>
            <ul class="list-disc list-inside pb-3">
                <li><strong>IP Address</strong> &mdash; Your IP address is collected and stored in the web server's logs and security tools as a means of ensuring the security of this website and preventing abuse.</li>
            </ul>
        </div>
    </article>
@endsection
