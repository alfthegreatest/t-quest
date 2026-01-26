@extends('layouts.app')

@section('content')
    <x-back-link :href="route('welcome')" :text="'Back to games'"/>

    <x-page-heading class='truncate pl-2'>
        @if($game->is_in_progress)
        <x-in-progress-indicator class="bottom-[5px] right-[5px]" />
        @endif
        {{ $game->title }}
    </x-page-heading>

    <div class="flex md:flex-row flex-col">
        @if ($game->image)
            <div class="mb-4 flex-1">
                <div class="relative w-fit ">
                    {!! $shareButtons !!} 
                    @can('admin')
                    <x-edit-link
                        :class="'absolute top-2 left-2'"
                        href="{{ route('game.edit', $game->id) }}"
                        title="Edit game"
                    ></x-edit-link>
                    @endcan
                    <img class="mx-auto w-full md:flex-row max-w-[400px] h-auto object-cover rounded"
                        src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}" title="{{ $game->title }}">
                </div>
            </div>
        @endif


        <div class="flex flex-col flex-1 justify-center">
            @if($game->is_in_progress)
            <x-enter-game-btn :gameId="$game->id" :class="'w-fit'" />
            @endif
    
            <div class="text-white">
                <span class="font-extrabold">Created by:</span>
                <span>{{ $game->creator->name ?? '-' }}</span>
            </div>

            <div class="text-white">
                <span class="font-extrabold">Location: </span>
                <span>{{ $game->location?->title ?: 'not specified'}}</span>
            </div>

            <div>
                <div class="text-white">
                    <span class="font-extrabold">Start: </span>
                    <span x-data x-text="formatUserDate('{{ $game->start_date }}')"></span>
                </div>

                <div class="text-white">
                    <span class="font-extrabold">Finish: </span>
                    <span x-data x-text="formatUserDate('{{ $game->finish_date }}')"></span>
                </div>

                <livewire:timer :start-timestamp="$game->start_date->timestamp"
                    :finish-timestamp="$game->finish_date->timestamp" :key="'timer-' . $game->id" />
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