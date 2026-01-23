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

                <div class="flex-1">
                    <label class="block text-sm font-medium">Points <x-field-notification field="points" /></label>
                    @error('points')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <input 
                        type="number" 
                        wire:model.live.debounce.1000ms="points"
                        min="0"
                        placeholder="0" 
                        class="input-number @error('points') border-red-500 @enderror"
                    >
                </div>

               
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

                <a 
                    target="_blank" 
                    href="{{ route('game.level.codes', [$gameId, $id]) }}"
                    class="inline-flex items-center gap-1 text-gray-400 hover:text-white transition mb-4"
                >codes<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

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

    @if($showMapModal)
    <div wire:click="$set('showMapModal', false)" 
        class="map_overlay"
    >
        <div wire:click.stop 
            class="map_popup relative max-w-3xl w-full" 
            x-data="mapComponent()"
            x-init="initMap()"
        >
            <div class="flex gap-2 text-sm text-gray-600">
                <div class="flex-1">
                    <strong>Latitude:</strong> <span x-text="tempLatitude || 'Not selected'"></span>
                </div>
                <div class="flex-1">
                    <strong>Longitude:</strong> <span x-text="tempLongitude || 'Not selected'"></span>
                </div>
            </div>

            <div>
                <div class="map-popup-btns">
                    <button 
                        type="button"
                        @click="confirmSelection()"
                        class="confirm-btn"
                    >Confirm
                    </button>
                </div>

                <div wire:ignore>
                    <div x-ref="mapContainer" class="map-container"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>