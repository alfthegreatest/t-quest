<div>
    <a title="add level" class="add-level-btn" wire:click="$set('showAddLevelModal', true)">+</a>
    
    @if($showAddLevelModal)
    <div wire:click="$set('showAddLevelModal', false)" class="overlay">
        <div wire:click.stop class="popup">
            <h2 class="text-xl font-bold mb-4">new level</h2>
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
    <div wire:click="cancelMapSelection" 
        class="map_overlay"
    >
        <div wire:click.stop 
            class="map_popup relative max-w-3xl w-full" 
            x-data="mapComponent2()"
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
                        class="confirm-btn">
                        Confirm
                    </button>
                    <button 
                        type="button"
                        @click="clearSelection()"
                        class="clear-btn">
                        Clear
                    </button>
                </div>

                <div wire:ignore>
                    <div x-ref="mapContainer" 
                        class="map-container"></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        function mapComponent2() {
            return {
                map: null,
                marker: null,
                tempLatitude: null,
                tempLongitude: null,
                
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
                                    this.tempLatitude = currentLat;
                                    this.tempLongitude = currentLng;
                                    this.marker = L.marker([currentLat, currentLng]).addTo(this.map);
                                    this.map.setView([currentLat, currentLng], 13);
                                }
                                
                                this.map.on('click', (e) => {
                                    const lat = e.latlng.lat;
                                    const lng = e.latlng.lng;
                                    
                                    this.tempLatitude = lat.toFixed(6);
                                    this.tempLongitude = lng.toFixed(6);
                                    
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
                },
                
                confirmSelection() {
                    console.log('set');
                    this.$wire.set('latitude', this.tempLatitude);
                    this.$wire.set('longitude', this.tempLongitude);
                    this.$wire.set('showMapModal', false);
                },
                
                clearSelection() {
                    this.tempLatitude = null;
                    this.tempLongitude = null;
                    
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                        this.marker = null;
                    }
                }
            }
        }
    </script>

</div>