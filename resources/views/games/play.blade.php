@extends('layouts.app')

@section('content')
    <x-page-heading class="truncate">
        <x-back-link 
            href="{{ route('game.detail', $game->id) }}" 
            text=""
         />
        {{ $game->title }}
    </x-page-heading>

    <div  
        class="mx-auto max-w-[800px]" 
        x-data="mapComponent(@js($locations))"
        x-init="initMap()"
    >
        <div wire:ignore>
            <div x-ref="mapContainer"
                class="map-container"
            ></div>
        </div>
    </div>

    <script>
    function mapComponent(locations) {
        return {
            map: null,
            userMarker: null,
            markers: [],
            locations: locations,

            
            async initMap() {
                // Initialize map with default coordinates (will update once we get user location)
                this.map = L.map(this.$refs.mapContainer).setView([51.1263106, 16.9781963], 12);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 20
                }).addTo(this.map);
                
                // Get user's current location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Update map view to user location
                            this.map.setView([lat, lng], 13);

                            // Add marker at user location
                            this.userMarker = L.marker([lat, lng])
                                .addTo(this.map)
                                .bindPopup(`You are here!`)
                                .openPopup();

                            this.addOtherPoints();
                        },
                        (error) => {
                            console.error('Error getting location:', error);
                            alert('Unable to retrieve your location');
                            this.addOtherPoints();
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser');
                }
            },

            addOtherPoints() {
                this.locations.forEach(location => {
                    this.addMarker(
                        location.lat, 
                        location.lng,
                        location.name, 
                        location.description
                    );
                });
            },

            addMarker(lat, lng, title, description) {
                const marker = L.marker([lat, lng])
                    .addTo(this.map)
                    .bindPopup(`<b>${title}</b><br>${description}`);
                
                this.markers.push(marker);
                return marker;
            },
        }
    }
    </script>
@endsection