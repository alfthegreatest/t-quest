@extends('layouts.app')

@section('content')
    <x-page-heading>{{ $game->title }}</x-page-heading>

    <div class="flex md:flex-row flex-col">
        @if ($game->image)
            <div class="mb-4 flex-1">
                <div class="relative">
                    @can('admin')
                        <x-edit-link :class="'absolute top-2 left-2'" :href="route('game.edit', $game->id)">Edit game</x-edit-link>
                    @endcan
                    <img class="mx-auto w-auto max-w-[400px] h-auto float-none md:float-left object-cover rounded"
                        src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}" title="{{ $game->title }}">
                </div>
            </div>
        @endif

        <div class="flex flex-col flex-1 justify-center">
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