<div id="edit-level-popup">
    @if($showEditLevelPopup)
    <div wire:click="$set('showEditLevelPopup', false)" class="overlay">
        <div wire:click.stop class="popup">
            <h2 class="text-xl font-bold mb-4">Edit level "{{$name}}"</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                <input 
                    type="text" 
                    wire:model.blur="name" 
                    placeholder="Name" 
                    class="input-text @error('name') border-red-500 ring-red-500 @enderror"
                >

                <div class="btn-group">
                    <button type="submit"
                        class="save-btn {{ $errors->any() ? 'cursor-not-allowed bg-gray-500' : 'hover:cursor-pointer bg-green-700 hover:bg-green-600'}}"
                        {{ $errors->any() ? 'disabled' : '' }}>
                        Save
                    </button>
                    <button type="button" wire:click="$set('showEditLevelPopup', false)"
                        class="cancel-btn">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>