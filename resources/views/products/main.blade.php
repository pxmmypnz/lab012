@extends('layouts.main', [
    'title' => "Product: {$title}",
])

@section('header')
    <h1 class="app-page-title">Product: {{ $title }}</h1>
@endsection
