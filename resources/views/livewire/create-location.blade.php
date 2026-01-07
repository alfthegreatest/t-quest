<div>
    <button wire:click="$set('showAddLocationModal', true)"
        class="bg-gray-700 hover:cursor-pointer hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded shadow">
    Add new</button>

    @if($showAddLocationModal)
        <div wire:click="$set('showAddLocationModal', false)"
            class="fixed inset-0 bg-black/90 flex items-center justify-center z-60">
            <div wire:click.stop class="bg-gray-800 text-white rounded-lg w-96 p-6 shadow-lg">
                <h2 class="text-xl font-bold mb-4">New location</h2>
                <form wire:submit.prevent="save" class="space-y-4">
                    @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                    <input type="text" wire:model.blur="title" placeholder="Title" class="w-full p-2 rounded bg-gray-700 
                                        border border-gray-600 focus:outline-none 
                                        focus:border-blue-500 @error('title') border-red-500 ring-red-500 @enderror">

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit"
                            class="text-white py-2 px-4 rounded font-semibold transition-colors
                                            {{ $errors->any() ? 'cursor-not-allowed bg-gray-500' : 'hover:cursor-pointer bg-green-700 hover:bg-green-600'}}"
                            {{ $errors->any() ? 'disabled' : '' }}>
                            Save
                        </button>
                        <button type="button" wire:click="$set('showAddLocationModal', false)"
                            class="bg-red-700 hover:bg-red-600 hover:cursor-pointer text-white py-2 px-4 rounded font-semibold transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>