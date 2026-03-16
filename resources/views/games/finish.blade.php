@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold text-white mb-6">
        Game: {{ $game->title }}
    </h1>

    <div class="rounded-2xl bg-gray-800 border border-white/10 p-4 max-w-sm shadow-lg">
        @if ($status === 'completed')
            <div class="mb-4 overflow-hidden rounded-xl aspect-video">
                <video class="w-full h-full object-cover" controls autoplay>
                    <source src="{{ asset('storage/dancing_gnome.mp4') }}" type="video/mp4">
                </video>
            </div>
        @endif
        <div class="mb-4">
            <span
                class="inline-block rounded-md bg-white/10 px-3 py-1 text-sm font-semibold text-white/80 uppercase tracking-wide">
                Game status: {{ $status }}
            </span>
        </div>

        @if ($status === 'upcoming')
            <div class="mb-4 inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-1.5">
                <span class="text-sm font-bold text-white">
                    <livewire:timer :start-timestamp="$game->start_date->timestamp"
                        :finish-timestamp="$game->finish_date->timestamp" :key="'timer-' . $game->id" />
                </span>
            </div>

            <div class="mb-4 space-y-1">
                <div class="text-xs text-white/50">
                    <span class="font-semibold text-white/70">Start:</span>
                    <span x-data x-text="formatUserDate('{{ $game->start_date }}')"></span>
                </div>
                <div class="text-xs text-white/50">
                    <span class="font-semibold text-white/70">Finish:</span>
                    <span x-data x-text="formatUserDate('{{ $game->finish_date }}')"></span>
                </div>
            </div>
        @endif

        <div class="mt-4">
            <p class="text-sm font-semibold text-white drop-shadow-[0_0_8px_rgba(168,85,247,0.8)]">
                Thank you for playing
            </p>
        </div>
    </div>
@endsection