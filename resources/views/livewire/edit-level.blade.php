<div id="edit-level-popup">
    @if($showEditLevelPopup)
    <div wire:click="$set('showEditLevelPopup', false)" class="overlay">
        <div wire:click.stop class="popup">
            <h2 class="text-xl font-bold mb-4">Edit level "{{$name}}"</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                <label class="label-base">Name <x-field-notification field="name" /></label>
                @error('name') <span class="error">{{ $message }}</span> @enderror
                <input 
                    type="text" 
                    wire:model.live.debounce.1000ms="name" 
                    placeholder="Name" 
                    class="input-text @error('name') border-red-500 ring-red-500 @enderror"
                >
                
                <label class="label-base">Description <x-field-notification field="description" /></label>
                @error('description') <span class="error">{{ $message }}</span> @enderror
                <textarea 
                    wire:model.live.debounce.2000ms="description" 
                    placeholder="Description"
                    class="input-text"
                ></textarea>
               
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Duration <x-field-notification field="availability_time" /></label>
                    @error('availability_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <input 
                                type="number" 
                                wire:model.live.debounce.1000ms="availability_time_days"
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
                                wire:model.live.debounce.1000ms="availability_time_hours"
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
                                wire:model.live.debounce.1000ms="availability_time_minutes"
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
                    <label class="block text-sm font-medium">Coordinates <x-field-notification field="coordinates" /></label>
                    @error('coordinates') 
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
                            class="select-on-map-btn"
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

    @once
    <x-leaflet-assets />
    @endonce

    @if($showMapModal)
    <div wire:click="$set('showMapModal', false)" 
        class="map_overlay" 
        style="z-index: 1001;"
    >
        <div wire:click.stop 
            class="map_popup" 
            style="max-width: 800px; width: 100%;"
            x-data="mapComponent()"
            x-init="initMap()"
        >
            <div class="flex gap-2">
                <button 
                    type="button"
                    wire:click="$set('showMapModal', false)"
                    class="confirm-btn">
                    Confirm
                </button>
            </div>

            <div class="flex gap-2 text-sm text-gray-600 mt-4">
                <div class="flex-1">
                    <strong>Latitude:</strong> <span x-text="$wire.latitude || 'Not selected'"></span>
                </div>
                <div class="flex-1">
                    <strong>Longitude:</strong> <span x-text="$wire.longitude || 'Not selected'"></span>
                </div>
            </div>

            <div class="mb-4">
                <div wire:ignore>
                    <div x-ref="mapContainer" 
                        style="height: 500px; width: 100%; border-radius: 8px;" 
                        class="border border-gray-300"
                    ></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>