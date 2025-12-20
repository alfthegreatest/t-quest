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

        <div 
            
            class="flex flex-col flex-1 justify-center"
        >
            <div class="text-white"><span class="font-extrabold">Created by:</span>
                <span>{{ $game->creator->name ?? '-' }}</span>
            </div>

            <div x-data="{
                formatDate(isoDate) {
                    const date = new Date(isoDate);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    return `${day}.${month}.${year}, ${hours}:${minutes}`;
                }
            }">
                <div class="text-white">
                    <span class="font-extrabold">Start: &nbsp;</span>
                    <span x-text="formatDate('{{ $game->start_date }}')">{{ $game->start_date }}</span>
                </div>

                <div class="text-white">
                    <span class="font-extrabold">Finish: &nbsp;</span>
                    <span x-text="formatDate('{{ $game->finish_date }}')">{{ $game->finish_date }}</span>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-4 text-white">
        <div class="font-extrabold">Description:</div>
        <div>{!! $game->description !!}</div>
    </div>
@endsection