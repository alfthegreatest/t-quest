@extends('layouts.app')

@section('content')
    <x-page-heading>{{ $game->title }}</x-page-heading>
    <div class="mb-4 text-white"><span class="font-extrabold">Description:</span> <span>{{ $game->description }}</span></div>
    <div class="mb-4 text-white"><span class="font-extrabold">Created by:</span> <span>{{ $game->creator->name ?? '-' }}</span></div>
@endsection