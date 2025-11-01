@extends('layouts.main')

@section('title', 'Главная')

@section('content')
    <nav class="text-sm text-gray-400 mb-6">
        <a href="{{ url('/') }}" class="hover:text-gray-200">Каталог</a>
    </nav>
    @include("components.big-section")
@endsection
