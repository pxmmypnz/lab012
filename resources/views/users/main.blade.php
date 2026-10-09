@extends('layouts.main', [
    'title' => "User: {$title}",
])

@section('header')
    <h1 class="app-page-title">User: {{ $title }}</h1>
@endsection
