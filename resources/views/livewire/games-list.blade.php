<div>
    <div class="mx-auto overflow-x-auto shadow rounded-lg dark:bg-gray-800 max-w-[800px]">
        <livewire:create-game />

        <div class="p-4 navigation">
            {{ $games->links() }}
        </div>

        <table class="users-table min-w-full border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th>ID</th>
                    <th>Active</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($games as $game)
                    <tr wire:key="game-{{ $game->id }}" class="hover:bg-gray-700">
                        <td>{{ $game->id }}</td>
                        <td class="text-center"><input type="checkbox" disabled {{ $game->active ? 'checked' : '' }} /></td>
                        <td><a href="/game/{{ $game->id }}">{{ $game->title }}</a></td>
                        <td>{!! $game->description !!}</td>
                        <td>
                            <a 
                                href="{{ route('game.edit', $game->id) }}" 
                                wire:navigate
                                class="bg-gray-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-gray-500 inline-block">
                                edit
                            </a>
                       </td>
                        <td><button type="button" wire:click="confirmDelete({{ $game->id }})"
                                class="bg-red-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-red-500">del</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 navigation">
            {{ $games->links() }}
        </div>

        @if($showModal)
            <div wire:click="$set('showModal', false)"
                class="fixed inset-0 bg-black/90 flex items-center justify-center z-60">
                <div wire:click.stop class="bg-gray-800 text-white rounded-lg w-96 p-6 shadow-lg">
                    <h2 class="text-xl font-bold mb-4">Do you want to delete the game number {{ $gameId }}?</h2>
                    <form class="space-y-4">
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="delete"
                                class="bg-red-600 text-white px-3 py-1 rounded cursor-pointer">Yes</button>
                            <button type="button" wire:click="$set('showModal', false)"
                                class="ml-2 bg-gray-300 px-3 py-1 rounded cursor-pointer">No</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>