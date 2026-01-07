<div>
    <a title="add level" class="add-level-btn" wire:click="$set('showAddLevelModal', true)">+</a>
    @if($showAddLevelModal)
    <div wire:click="$set('showAddLevelModal', false)"
        class="overlay"
    >
        <div wire:click.stop class="popup">
            <h2 class="text-xl font-bold mb-4">new level</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                <input 
                    type="text" 
                    wire:model.live="title" 
                    placeholder="Title" 
                    class="input-text @error('title') border-red-500 ring-red-500 @enderror"
                >
               
                <div class="btn-group">
                    <button type="submit"
                        class="save-btn {{ $errors->any() ? 'cursor-not-allowed bg-gray-500' : 'hover:cursor-pointer bg-green-700 hover:bg-green-600'}}"
                        {{ $errors->any() ? 'disabled' : '' }}>
                        Save
                    </button>
                    <button type="button" wire:click="$set('showAddLevelModal', false)"
                        class="cancel-btn">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>