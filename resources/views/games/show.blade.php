@extends('layouts.app')

@section('content')
    <x-page-heading>{{ $game->title }}</x-page-heading>
    <div class="mb-4 text-white"><span class="font-extrabold">Created by:</span>
        <span>{{ $game->creator->name ?? '-' }}</span>
    </div>

    <div class="mb-4 text-white">
        <div class="font-extrabold">Description:</div>
        <div>{!! $game->description !!}</div>
    </div>
@endsection