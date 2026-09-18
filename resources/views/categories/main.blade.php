@extends('layouts.main', [
    'title' => "Category: {$title}",
])

@section('header')
    <h1 class="app-page-title">Category: {{ $title }}</h1>
@endsection
