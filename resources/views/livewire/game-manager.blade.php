<div id="game-manager">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach($games as $game)
        <a href="/game/{{$game->id}}" class="bg-gray-800 text-white rounded-lg overflow-hidden shadow hover:shadow-lg transition hover:cursor-pointer">
            <img src="{{ $game->image ? asset('storage/' . $game->image) : '/games/game-icon.webp' }}" alt="{{ $game->title }}" title="{{ $game->title }}" class="w-full h-40 object-cover">
            <div class="p-4">
                <h3 class="text-lg font-bold mb-2">{{ $game->title }}</h3>
                <p class="text-gray-300 line-clamp-4">{{ $game->description }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
123
333
111