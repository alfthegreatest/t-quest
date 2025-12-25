@extends('layouts.app')

@section('content')
    <x-page-heading>{{ $game->title }}</x-page-heading>

    <div class="flex md:flex-row flex-col">
        @if ($game->image)
            <div class="mb-4 flex-1">
                <img class="mx-auto w-auto max-w-[400px] h-auto float-none md:float-left object-cover rounded"
                    src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}" title="{{ $game->title }}">
            </div>
        @endif

        <div class="flex flex-col flex-1 justify-center">
            @can('admin')
            <a href="{{ route('game.edit', $game->id) }}" 
                class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors group"
                title="Edit game">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            @endcan

            <div class="text-white"><span class="font-extrabold">Created by:</span>
                <span>{{ $game->creator->name ?? '-' }}</span>
            </div>

            <div>
                <div class="text-white">
                    <span class="font-extrabold">Start: &nbsp;</span>
                    <span x-data x-text="formatUserDate('{{ $game->start_date }}')"></span>
                </div>

                <div class="text-white">
                    <span class="font-extrabold">Finish: &nbsp;</span>
                    <span x-data x-text="formatUserDate('{{ $game->finish_date }}')"></span>
                </div>
                
                <livewire:timer 
                    :start-timestamp="$game->start_date->timestamp"
                    :finish-timestamp="$game->finish_date->timestamp" 
                    :key="'timer-'.$game->id"
                />
            </div>

        </div>
    </div>

    @if ($game->description)
        <div class="mt-4 text-white">
            <div class="font-extrabold">Description:</div>
            <div>{!! $game->description !!}</div>
        </div>
    @endif
@endsection