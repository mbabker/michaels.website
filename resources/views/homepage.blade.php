@extends('layouts.app')

@section('content')
    <x-blog-preview :post="$latestPost" />
@endsection
