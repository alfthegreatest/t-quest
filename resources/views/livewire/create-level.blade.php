<div>
    <a title="add level" class="add-level-btn" wire:click="$set('showAddLevelModal', true)">+</a>
    
    @if($showAddLevelModal)
    <div wire:click="$set('showAddLevelModal', false)" class="overlay">
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
                            Total: {{ $availabilityTimeFormatted }}
                        </p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Coordinates</label>
                    @error('latitude') 
                        <div class="text-red-500 text-sm">{{ $message }}</div> 
                    @enderror
                    @error('longitude') 
                        <div class="text-red-500 text-sm">{{ $message }}</div> 
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
        </div>
    </div>
    @endif

    @if($showMapModal)
    <div wire:click="$set('showMapModal', false)" 
         class="map_overlay" 
         style="z-index: 1001;">
        <div wire:click.stop 
             class="map_popup" 
             style="max-width: 800px; width: 90%;"
             x-data="mapComponent()"
             x-init="initMap()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Select Location</h3>
                <button 
                    type="button"
                    wire:click="$set('showMapModal', false)"
                    class="text-gray-500 hover:text-gray-700 text-2xl leading-none">
                    ×
                </button>
            </div>
            
            <div class="mb-4">
                <div wire:ignore>
                    <div x-ref="mapContainer" 
                         style="height: 500px; width: 100%; border-radius: 8px;" 
                         class="border border-gray-300"></div>
                </div>
            </div>
            
            <div class="flex gap-2 text-sm text-gray-600 mb-4">
                <div class="flex-1">
                    <strong>Latitude:</strong> <span x-text="$wire.latitude || 'Not selected'"></span>
                </div>
                <div class="flex-1">
                    <strong>Longitude:</strong> <span x-text="$wire.longitude || 'Not selected'"></span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button 
                    type="button"
                    wire:click="$set('showMapModal', false)"
                    class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Confirm
                </button>
                <button 
                    type="button"
                    wire:click="clearCoordinates"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    Clear
                </button>
            </div>
        </div>
    </div>
    @endif

    @once
        @push('leaflet_styles')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        @endpush

        @push('leaflet_scripts')
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                function mapComponent() {
                    return {
                        map: null,
                        marker: null,
                        initMap() {
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    if (this.$refs.mapContainer && !this.map) {
                                        this.map = L.map(this.$refs.mapContainer).setView([52.2297, 21.0122], 13);
                                        
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '© OpenStreetMap contributors'
                                        }).addTo(this.map);
                                        
                                        const currentLat = this.$wire.latitude;
                                        const currentLng = this.$wire.longitude;
                                        if (currentLat && currentLng) {
                                            this.marker = L.marker([currentLat, currentLng]).addTo(this.map);
                                            this.map.setView([currentLat, currentLng], 13);
                                        }
                                        
                                        this.map.on('click', (e) => {
                                            const lat = e.latlng.lat;
                                            const lng = e.latlng.lng;
                                            
                                            this.$wire.set('latitude', lat.toFixed(6));
                                            this.$wire.set('longitude', lng.toFixed(6));
                                            
                                            if (this.marker) {
                                                this.marker.setLatLng(e.latlng);
                                            } else {
                                                this.marker = L.marker(e.latlng).addTo(this.map);
                                            }
                                        });
                                        
                                        setTimeout(() => this.map.invalidateSize(), 100);
                                    }
                                }, 300);
                            });
                        }
                    }
                }
            </script>
            
            <style>
                .map_overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                }
                
                .map_popup {
                    background: white;
                    border-radius: 12px;
                    padding: 24px;
                    max-height: 90vh;
                    overflow-y: auto;
                }
            </style>
        @endpush
    @endonce
</div>
