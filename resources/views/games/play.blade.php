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
        class="fixed inset-0 w-full h-full" 
        x-data="mapComponent(@js($locations))"
        x-init="initMap()"
    >
        <div x-ref="mapContainer"
            class="h-full map-container"
        ></div>
    </div>

    <script>
    function mapComponent(locations) {
        return {
            map: null,
            userMarker: null,
            markers: [],
            locations: locations,

            
            async initMap() {
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

                            const userIcon = L.divIcon({
                                className: 'you-are-here',
                                html: `<div class="you-are-here"></div>`,
                                iconSize: [15, 15],
                                iconAnchor: [10, 10], // центр иконки
                            });

                            this.userMarker = L.marker([lat, lng], {
                                icon: userIcon,
                                interactive: false,
                            }).addTo(this.map).bindPopup('You are here');

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

<style>
.you-are-here {
    position: relative;
    width: 10px;
    height: 10px;
    background: #4285F4;
    border-radius: 50%;
}

.you-are-here::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(66,133,244,0.5);
    animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.7; }
    100% { transform: scale(3); opacity: 0; }
}
</style>
@endsection