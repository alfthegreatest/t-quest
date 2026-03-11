<div class="max-w-xl mx-auto space-y-6 text-gray-200" x-data x-init="
    $wire.user_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $wire.call('timezoneDetected');">
    <div>
        <label class="label-base w-fit cursor-pointer">
            <input type="checkbox" wire:model.live="active" id="active"
                class="cursor-pointer w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
            Active <x-field-notification field="active" />
        </label>
    </div>
    <div>
        <label class="label-base">Title <x-field-notification field="title" /></label>
        <input type="text" wire:model.live.debounce.2000="title" class="input-base">
    </div>
    <div class="w-full sm:flex-1">
        <label class="label-base">Location <x-field-notification field="location_id" /></label>
        <select wire:model.lazy="location_id" class="input-base">
            <option value=''>not chosen</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="w-full sm:flex-1">
        <label class="label-base">Base location <x-field-notification field="base_location" /></label>
        @error('base_location')
            <div class="error">{{ $message }}</div>
        @enderror

        <div class="flex gap-2">
            <input type="number" wire:model.live="latitude" step="any" placeholder="Latitude"
                class="input-text flex-1 @error('latitude') border-red-500 @enderror" disabled>
            <input type="number" wire:model.live="longitude" step="any" placeholder="Longitude"
                class="input-text flex-1 @error('longitude') border-red-500 @enderror" disabled>
            <button type="button" wire:click="$set('showMapModal', true)" class="select-on-map-btn"
                title="Select on map">
                📍
            </button>
        </div>

        @if($showMapModal)
            <div wire:click="$set('showMapModal', false)" class="map_overlay">
                <div wire:click.stop class="map_popup relative max-w-3xl w-full" x-data="mapComponent3()"
                    x-init="initMap()">
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
                            <button type="button" @click="confirmSelection()" class="confirm-btn">Confirm
                            </button>
                        </div>

                        <div wire:ignore>
                            <div x-ref="mapContainer" class="map-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @once
            <script>
                function mapComponent3() {
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
                            this.$wire.set('latitude', this.tempLatitude);
                            this.$wire.set('longitude', this.tempLongitude);
                            this.$wire.set('showMapModal', false);
                        },
                    }
                }
            </script>
        @endonce


    </div>
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="w-full sm:flex-1">
            <label class="label-base">City / Region <x-field-notification field="location_id" /></label>
            <select wire:model.lazy="location_id" class="input-base">
                <option value=''>not chosen</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full sm:flex-1">
            <label class="label-base">Base coordinates <x-field-notification field="base_location" /></label>
            @error('base_location')
                <div class="error">{{ $message }}</div>
            @enderror

            <div class="flex gap-2">
                <input type="number" wire:model.live="latitude" step="any" placeholder="Latitude"
                    class="input-text flex-1 @error('latitude') border-red-500 @enderror" disabled>
                <input type="number" wire:model.live="longitude" step="any" placeholder="Longitude"
                    class="input-text flex-1 @error('longitude') border-red-500 @enderror" disabled>
                <button type="button" wire:click="$set('showMapModal', true)" class="select-on-map-btn"
                    title="Select on map">
                    📍
                </button>
            </div>

            @if($showMapModal)
                <div wire:click="$set('showMapModal', false)" class="map_overlay">
                    <div wire:click.stop class="map_popup relative max-w-3xl w-full" x-data="mapComponent3()"
                        x-init="initMap()">
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
                                <button type="button" @click="confirmSelection()" class="confirm-btn">Confirm
                                </button>
                            </div>

                            <div wire:ignore>
                                <div x-ref="mapContainer" class="map-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @once
                <script>
                    function mapComponent3() {
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
                                this.$wire.set('latitude', this.tempLatitude);
                                this.$wire.set('longitude', this.tempLongitude);
                                this.$wire.set('showMapModal', false);
                            },
                        }
                    }
                </script>
            @endonce
        </div>
    </div>

    <div class="w-full sm:flex-1 rounded-lg border border-gray-600 p-4 space-y-3">
        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Duration</h3>

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:flex-1">
                <label class="label-base">Start ({{ $user_timezone }}) <x-field-notification
                        field="start_date" /></label>
                <input type="datetime-local" wire:model.lazy="start_date" class="input-base">
            </div>

            <div class="w-full sm:flex-1">
                <label class="label-base">Finish ({{ $user_timezone }}) <x-field-notification
                        field="finish_date" /></label>
                <input type="datetime-local" wire:model.lazy="finish_date" class="input-base">
            </div>
        </div>
    </div>

    <div class="relative">
        <label class="label-base">Image <x-field-notification field="image" /></label>
        <label
            class="flex items-center justify-center w-full h-12 px-4 bg-gray-700 text-gray-300 rounded cursor-pointer hover:bg-gray-600 transition">
            <span>Choose file (max {{$this->getMaxImageSizeMbProperty()}}Mb)</span>
            <input type="file" wire:model="image" class="hidden">
        </label>
        @error('image')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        @if ($this->imageUrl)
            <img src="{{ $this->imageUrl }}" class="w-full h-full object-cover" alt="Game image">
            <button type="button" wire:click="removeImage"
                class="absolute bottom-0 w-full  bg-gray-500 text-white p-2 shadow-lg transition-all duration-200 opacity-90 hover:cursor-pointer hover:opacity-100">
                remove image
            </button>
        @endif
    </div>

    <div x-data="{ description: @js($description) }">
        <label class="label-base">Description (html allowed) <x-field-notification field="description" /></label>
        <div class="preview-box px-4 bg-gray-900" x-html="description"
            x-show="description && description.trim() !== ''"></div>
        <div wire:ignore>
            <textarea x-model="description" wire:model.blur="description"
                x-init="$el.style.height = $el.scrollHeight + 'px'"
                @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                @input.debounce.2000ms="$wire.set('description', description)" class="input-base"
                style="overflow:hidden; resize:none; min-height: 6rem;"></textarea>
        </div>
    </div>
</div>