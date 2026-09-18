@extends('layouts.main', [
    'title' => "Shop: {$title}",
])

@section('header')
    <h1 class="app-page-title">Shop: {{ $title }}</h1>
@endsection
