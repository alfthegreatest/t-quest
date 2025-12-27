<div id="game-manager">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach($games as $game)
            <a href="/game/{{$game->id}}"
                class="relative bg-gray-800 text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition hover:cursor-pointer">

                @can('admin')
                    <x-edit-link :class="'absolute top-2 right-2'" :href="route('game.edit', $game->id)">Edit game</x-edit-link>
                @endcan

                <img src="{{ $game->image ? asset('storage/' . $game->image) : '/games/game-icon.webp' }}"
                    alt="{{ $game->title }}" title="{{ $game->title }}" class="w-full h-40 object-cover">

                <div class="bg-[#bebebe] w-fit px-2">
                    <livewire:timer :start-timestamp="$game->start_date->timestamp"
                        :finish-timestamp="$game->finish_date->timestamp" :key="'timer-' . $game->id" />
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">{{ $game->title }}</h3>
                    <p class="text-gray-300 line-clamp-4">{!! $game->description !!}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>