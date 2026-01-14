@props(['gameId' => null])

<a
    href="{{ route('game.play', $gameId) }}"
    {{ $attributes->class([
        'enter-game-btn',
        'block items-center justify-center',
        'p-2 rounded-md',
        'bg-gradient-to-r from-green-500 to-emerald-600',
        'text-white font-semibold',
        'shadow-md transition',
        'hover:from-green-400 hover:to-emerald-500 hover:shadow-lg hover:cursor-pointer',
        'active:scale-95'
    ]) }}
>
    Enter the game
</a>