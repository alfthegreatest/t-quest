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
                    <th>Title</th>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($games as $game)
                    <tr wire:key="game-{{ $game->id }}" class="">
                        <td>{{ $game->id }}</td>
                        <td>{{ $game->title }}</td>
                        <td>{{ $game->description }}</td>
                        <td><button type="button" wire:click="edit({{ $game->id }})" class="bg-gray-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-gray-500">edit</button></td>
                        <td><button type="button" wire:click="confirmDelete({{ $game->id }})" class="bg-red-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-red-500">del</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 navigation">
            {{ $games->links() }}
        </div>

        @if($showModal)
        <div wire:click="$set('showModal', false)" class="fixed inset-0 bg-black/90 flex items-center justify-center z-60">
            <div wire:click.stop class="bg-gray-800 text-white rounded-lg w-96 p-6 shadow-lg">
                <h2 class="text-xl font-bold mb-4">Do you want to delete the game number {{ $gameId }}?</h2>
                <form class="space-y-4">
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="delete" class="bg-red-600 text-white px-3 py-1 rounded cursor-pointer">Yes</button>
                        <button type="button" wire:click="$set('showModal', false)" class="ml-2 bg-gray-300 px-3 py-1 rounded cursor-pointer">No</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if($showEditGameModal)
        <div wire:click="$set('showEditGameModal', false)" class="fixed inset-0 bg-black/90 flex items-center justify-center z-60">
            <div wire:click.stop class="bg-gray-800 text-white rounded-lg w-96 p-6 shadow-lg">
                <h2 class="text-xl font-bold mb-4">Edit game #{{$gameId}}</h2>
                <form wire:submit.prevent="update" class="space-y-4">
                    @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                    <input
                        type="text"
                        wire:model.live="title"
                        placeholder="Title"
                        class="w-full p-2 rounded bg-gray-700 
                        border border-gray-600 focus:outline-none 
                        focus:border-blue-500 @error('title') border-red-500 ring-red-500 @enderror"
                        value="{{ $title }}"
                    >

                    <textarea
                        wire:model.live="description"
                        placeholder="Description"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500"
                    >{{ $description }}</textarea>

                    <div class="w-full">
                        <label class="flex items-center justify-center w-full h-12 px-4 bg-gray-700 text-gray-300 rounded cursor-pointer hover:bg-gray-600 transition">
                            <span>Choose file (max {{$this->getMaxImageSizeMbProperty()}}Mb)</span>
                            <input type="file" wire:model="image" class="hidden">
                        </label>
                        @error('image') 
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-40 object-cover">
                        @elseif ($imagePath)
                            <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-40 object-cover">
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button 
                            type="submit" 
                            class="bg-green-700 hover:bg-green-600 hover:cursor-pointer text-white py-2 px-4 rounded font-semibold transition-colors"
                        >
                            Update
                        </button>
                        <button 
                            type="button" 
                            wire:click="$set('showEditGameModal', false)" 
                            class="bg-red-700 hover:bg-red-600 hover:cursor-pointer text-white py-2 px-4 rounded font-semibold transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>