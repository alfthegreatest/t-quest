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
                
                @if(!$showDeleteLevelButtons)
                <div class="btn-group">
                    <button type="button" wire:click.prevent="$set('showDeleteLevelButtons', true)"
                        class="del-btn">
                        Delete level
                    </button>

                    <button type="button" wire:click="$set('showEditLevelPopup', false)"
                        class="cancel-btn">
                        Close
                    </button>
                </div>
                @endif
                
                @if($showDeleteLevelButtons)
                <div class="btn-group">
                    <div class="text-xl">Delete the level?</div>
                    <button type="button" wire:click.prevent="deleteLevel( {{$id}} )" class="yes-btn">
                        Yes
                    </button>
                    <button type="button" wire:click="$set('showDeleteLevelButtons', false)"
                        class="no-btn">
                        No
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
    @endif
</div>