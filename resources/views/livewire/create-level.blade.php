<div>
    <a title="add level" class="add-level-btn" wire:click="$set('showAddLevelModal', true)">+</a>
    @if($showAddLevelModal)
    <div wire:click="$set('showAddLevelModal', false)"
        class="overlay"
    >
        <div wire:click.stop class="popup">
            <h2 class="text-xl font-bold mb-4">new level</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                <input 
                    type="text" 
                    wire:model.live="name"
                    placeholder="Name" 
                    class="input-text @error('name') border-red-500 ring-red-500 @enderror"
                >

                <textarea 
                    wire:model="description" 
                    placeholder="Description"
                    class="input-text"
                ></textarea>
               
                <div class="space-y-2">
                    @error('availability_time') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                    
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <input 
                                type="number" 
                                wire:model.live="availability_time_days"
                                min="0"
                                max="364"
                                placeholder="0" 
                                class="input-number @error('availability_time_days') border-red-500 @enderror"
                            >
                            <span class="input-suffiks">days</span>
                        </div>
                        
                        <div class="relative flex-1">
                            <input 
                                type="number" 
                                wire:model.live="availability_time_hours"
                                min="0"
                                max="23"
                                placeholder="0" 
                                class="input-number @error('availability_time_hours') border-red-500 @enderror"
                            >
                            <span class="input-suffiks">hours</span>
                        </div>
                        
                        <div class="relative flex-1">
                            <input 
                                type="number" 
                                wire:model.live="availability_time_minutes"
                                min="0"
                                max="59"
                                placeholder="0" 
                                class="input-number @error('availability_time_minutes') border-red-500 @enderror"
                            >
                            <span class="input-suffiks">minutes</span>
                        </div>
                    </div>
                    
                    @if($availability_time_days || $availability_time_hours || $availability_time_minutes)
                        <p class="text-sm text-gray-500">
                            Total: {{ $this->getFormattedTime() }}
                        </p>
                    @endif
                </div>


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