<form wire:submit.prevent="save" class="space-y-4">
    @error('name') <span class="error">{{ $message }}</span> @enderror
    <input 
        type="text"
        wire:model.live.debounce.1000ms="name"
        placeholder="Name"
        class="input-text @error('name') border-red-500 ring-red-500 @enderror"
    >

    <textarea 
        wire:model="description" 
        placeholder="Description"
        class="input-text"
    ></textarea>
    
    <div class="space-y-2">
        <label class="block text-sm font-medium">Duration</label>
        @error('availability_time')
            <span class="error">{{ $message }}</span>
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
                Total: {{ $availabilityTimeFormatted }}
            </p>
        @endif
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium">Coordinates</label>
        @error('latitude') 
            <div class="error">{{ $message }}</div> 
        @enderror
        @error('longitude') 
            <div class="error">{{ $message }}</div> 
        @enderror
        
        <div class="flex gap-2">
            <input 
                type="number" 
                wire:model.live="latitude"
                step="any"
                placeholder="Latitude"
                class="input-text flex-1 @error('latitude') border-red-500 @enderror"
                disabled
            >
            <input 
                type="number" 
                wire:model.live="longitude"
                step="any"
                placeholder="Longitude"
                class="input-text flex-1 @error('longitude') border-red-500 @enderror"
                disabled
            >
            <button 
                type="button"
                wire:click="$set('showMapModal', true)"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2 hover:cursor-pointer"
                title="Select on map">
                📍
            </button>
        </div>
        
        @if($latitude && $longitude)
            <p class="text-sm text-gray-500">
                Selected: {{ number_format($latitude, 6) }}, {{ number_format($longitude, 6) }}
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