@extends('layouts.app')

@section('content')
    <x-page-heading class="truncate">
        <x-back-link 
            href="{{ route('game.detail', $game->id) }}" 
            text=""
         />
        {{ $game->title }}
    </x-page-heading>

    <div class="flex md:flex-row flex-col text-white">
    </div>
@endsection