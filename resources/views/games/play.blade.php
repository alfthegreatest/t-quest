@extends('layouts.play')

@section('content')
    <div  
        class="fixed inset-0 w-full h-full" 
        x-data="mapComponent(@js($locations))"
        x-init="initMap()"
    >
        <!-- Loader overlay -->
        <div 
            x-show="loading"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 z-[1000] flex items-center justify-center"
            style="background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        >
            <div class="text-center">
                <div class="relative">
                    <div class="w-20 h-20 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-white text-lg font-medium">Map is loading...</p>
            </div>
        </div>

        <!-- Map container -->
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
            loading: true,

            // Get center amoung locations
            calculateCenter() {
                if (!this.locations || this.locations.length === 0) {
                    return { lat: 51.1263106, lng: 16.9781963, zoom: 12 }; // Default fallback
                }

                // If we has 1 location only
                if (this.locations.length === 1) {
                    return {
                        lat: this.locations[0].lat,
                        lng: this.locations[0].lng,
                        zoom: 14
                    };
                }

                // calculate the average value of coordinates
                let sumLat = 0;
                let sumLng = 0;
                
                this.locations.forEach(location => {
                    sumLat += parseFloat(location.lat);
                    sumLng += parseFloat(location.lng);
                });

                const centerLat = sumLat / this.locations.length;
                const centerLng = sumLng / this.locations.length;

                // calculating bounds to get zoom value
                const lats = this.locations.map(l => parseFloat(l.lat));
                const lngs = this.locations.map(l => parseFloat(l.lng));
                
                const minLat = Math.min(...lats);
                const maxLat = Math.max(...lats);
                const minLng = Math.min(...lngs);
                const maxLng = Math.max(...lngs);

                // Calculating the distance for automatic zoom
                const latDiff = maxLat - minLat;
                const lngDiff = maxLng - minLng;
                const maxDiff = Math.max(latDiff, lngDiff);

                // calculate zoom
                let zoom;
                if (maxDiff > 1) zoom = 10;
                else if (maxDiff > 0.5) zoom = 11;
                else if (maxDiff > 0.1) zoom = 12;
                else if (maxDiff > 0.05) zoom = 13;
                else if (maxDiff > 0.01) zoom = 14;
                else zoom = 15;

                return { lat: centerLat, lng: centerLng, zoom };
            },
            
            async initMap() {
                const center = this.calculateCenter();
                this.map = L.map(this.$refs.mapContainer).setView([center.lat, center.lng], center.zoom);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 20
                }).addTo(this.map);

                // add button of geolocation
                this.addGeolocationButton();
                
                this.addOtherPoints();
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            const userIcon = L.divIcon({
                                className: 'you-are-here',
                                html: `<div class="you-are-here"></div>`,
                                iconSize: [15, 15],
                                iconAnchor: [10, 10],
                            });

                            this.userMarker = L.marker([lat, lng], {
                                icon: userIcon,
                                interactive: false,
                            }).addTo(this.map).bindPopup('You are here');
                            
                            // Bounds recalculating (taking into account the user's position)
                            const allPoints = [
                                ...this.locations.map(loc => [parseFloat(loc.lat), parseFloat(loc.lng)]),
                                [lat, lng] // add user location
                            ];
                            
                            const bounds = L.latLngBounds(allPoints);
                            this.map.fitBounds(bounds, {
                                padding: [50, 50],
                                maxZoom: 15
                            });
                            
                            this.loading = false;
                        },
                        (error) => {
                            console.error('Error getting location:', error);
                            this.loading = false;
                        }
                    );
                } else {
                    this.loading = false;
                }
            },

            addGeolocationButton() {
                const GeolocationControl = L.Control.extend({
                    options: {
                        position: 'bottomright' // right bottom corner
                    },
                    
                    onAdd: (map) => {
                        const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                        const button = L.DomUtil.create('a', 'leaflet-control-geolocation', container);
                        
                        button.innerHTML = `
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="10" cy="10" r="3"/>
                                <path d="M10 1v3M10 16v3M1 10h3M16 10h3"/>
                            </svg>
                        `;
                        button.href = '#';
                        button.title = 'Show my location';
                        button.style.width = '40px';
                        button.style.height = '40px';
                        button.style.display = 'flex';
                        button.style.alignItems = 'center';
                        button.style.justifyContent = 'center';
                        button.style.textDecoration = 'none';
                        button.style.color = '#333';
                        button.style.backgroundColor = 'white';
                        button.style.borderRadius = '4px';
                        
                        L.DomEvent.on(button, 'click', (e) => {
                            L.DomEvent.stopPropagation(e);
                            L.DomEvent.preventDefault(e);
                            this.centerOnUser();
                        });
                        
                        return container;
                    }
                });
                
                this.map.addControl(new GeolocationControl());
            },

            centerOnUser() {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported');
                    return;
                }
                
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // delete old marker
                        if (this.userMarker) {
                            this.map.removeLayer(this.userMarker);
                        }
                        
                        // add a new marker
                        const userIcon = L.divIcon({
                            className: 'you-are-here',
                            html: `<div class="you-are-here"></div>`,
                            iconSize: [15, 15],
                            iconAnchor: [10, 10],
                        });

                        this.userMarker = L.marker([lat, lng], {
                            icon: userIcon,
                            interactive: false,
                        }).addTo(this.map).bindPopup('You are here').openPopup();
                        
                        // move map with animation
                        this.map.flyTo([lat, lng], 15, {
                            duration: 1
                        });
                    },
                    (error) => {
                        console.error('Error:', error);
                        alert('Unable to get your location');
                    }
                );
            },

            addOtherPoints() {
                this.locations.forEach(location => {
                    this.addMarker(
                        location.lat, 
                        location.lng,
                        location.title || location.name, 
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

.leaflet-control-geolocation:hover {
    background-color: #f4f4f4 !important;
}

.leaflet-bottom.leaflet-right {
    margin-bottom: 10px;
}
</style>
@endsection