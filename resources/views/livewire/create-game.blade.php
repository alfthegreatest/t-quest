<div>
    <button
        wire:click="$set('showAddGameModal', true)"
        class="bg-gray-700 hover:cursor-pointer hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded shadow"
    >Add new game</button>

    @if($showAddGameModal)
    <div wire:click="$set('showAddGameModal', false)" class="fixed inset-0 bg-black/90 flex items-center justify-center z-60">
        <div wire:click.stop class="bg-gray-800 text-white rounded-lg w-96 p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Add a new game</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                <input
                    type="text"
                    wire:model.live="title"
                    placeholder="Title"
                    class="w-full p-2 rounded bg-gray-700 
                    border border-gray-600 focus:outline-none 
                    focus:border-blue-500 @error('title') border-red-500 ring-red-500 @enderror"
                >

                <div class="w-full">
                    <label class="label-base">Start date</label>
                    <input type="datetime-local" wire:model.lazy="start_date" class="input-base">
                </div>

                <div class="w-full">
                    <label class="label-base">Finish date</label>
                    <input type="datetime-local" wire:model.lazy="finish_date" class="input-base">
                </div>


                <textarea
                    wire:model.live="description"
                    placeholder="Description"
                    class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500"
                ></textarea>

                <div class="w-full">
                    <label class="flex items-center justify-center w-full h-12 px-4 bg-gray-700 text-gray-300 rounded cursor-pointer hover:bg-gray-600 transition">
                        <span>Choose file (max {{$this->getMaxImageSizeMbProperty()}}Mb)</span>
                        <input type="file" wire:model="image" class="hidden">
                    </label>
                    @error('image') 
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                    
                    @if ($image && $this->canPreview($image))
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full max-h-32 mt-2 object-cover rounded">
                    @endif
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button 
                        type="submit" 
                        class="bg-green-700 hover:bg-green-600 text-white py-2 px-4 rounded font-semibold transition-colors
                        {{ $errors->any() ? 'cursor-not-allowed' : 'hover:cursor-pointer'}}"
                        {{ $errors->any() ? 'disabled' : '' }}
                    >
                        Save
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('showAddGameModal', false)" 
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