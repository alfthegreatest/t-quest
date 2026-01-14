<div id="game-manager">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach($games as $game)
        <div class="relative bg-gray-800 text-white rounded-lg overflow-hidden shadow min-h-[320px]">
                <div class="bg-[#bebebe] w-fit px-2 absolute top-0">
                    <livewire:timer :start-timestamp="$game->start_date->timestamp"
                        :finish-timestamp="$game->finish_date->timestamp" :key="'timer-' . $game->id" />
                </div>

                <a href="{{ route('game.detail', $game->id) }}" class="hover:shadow-lg transition hover:cursor-pointer">
                    @can('admin')
                        <x-edit-link 
                            :class="'absolute top-2 right-2'" 
                            href="{{ route('game.edit', $game->id) }}"
                            title="Edit game"    
                        ></x-edit-link>
                    @endcan

                    <img src="{{ $game->image ? asset('storage/' . $game->image) : '/games/game-icon.webp' }}"
                        alt="{{ $game->title }}" title="{{ $game->title }}" class="w-full h-40 object-cover">
                </a>
                <div>
                    <div class="text-xs">
                        <div class="text-white">
                            <span class="font-extrabold">Start: </span>
                            <span x-data x-text="formatUserDate('{{ $game->start_date }}')"></span>
                        </div>

                        <div class="text-white">
                            <span class="font-extrabold">Finish: </span>
                            <span x-data x-text="formatUserDate('{{ $game->finish_date }}')"></span>
                        </div>
                    </div>
                </div>

                <div class="p-2">
                    <h3 class="text-lg font-bold">
                        @if($game->is_in_progress)
                            <x-in-progress-indicator class="mr-[5px]" />
                        @endif
                        {{ $game->title }}
                    </h3>
                    <div class="mt-2.5"><span class="font-extrabold">Location:</span> <span>{{ $game->location?->title ?: 'not specified' }}</span></div>
                    @if($game->is_in_progress)
                    <x-enter-game-btn 
                        :gameId="$game->id" 
                        :class="'absolute bottom-0 left-5 right-3 w-full'"
                    />
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>