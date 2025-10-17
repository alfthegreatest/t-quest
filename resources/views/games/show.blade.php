@extends('layouts.app')

@section('content')
    <x-page-heading>{{ $game->title }}</x-page-heading>
    <div class="mb-4 text-white">{{ $game->description }}</div>
@endsection