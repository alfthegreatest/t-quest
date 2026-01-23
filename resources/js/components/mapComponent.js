export default function mapComponent(locations) {
    return {
        activeLevelId: null,
        currentClosestMarker: null,
        defaultIcon: null,
        initialBounds: null,
        isNear: false,
        locations: locations,
        loading: true,
        map: null,
        markers: [],
        range: 20,
        redIcon: null,
        watchId: null,
        userMarker: null,
        
        async initMap() {
            const icon = {
                shadowUrl: '/images/markers/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41],
                interactive: true,
            };

            this.defaultIcon = new L.Icon({
                ...icon,
                iconUrl: '/images/markers/marker-icon-blue.png',
            });

            this.redIcon = new L.Icon({
                ...icon,
                iconUrl: '/images/markers/marker-icon-red.png',
            });
            
            this.createMap();
            this.addControls();
            this.addLocationMarkers();
            this.saveInitialBounds();
            await this.addUserLocation();
        },

        createMap() {
            const center = this.calculateCenter();
            this.map = L.map(this.$refs.mapContainer, {
                center: [center.lat, center.lng],
                zoom: center.zoom,
                zoomControl: false
            });

            L.control.zoom({
                position: 'bottomleft'
            }).addTo(this.map);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 20
            }).addTo(this.map);
        },

        calculateCenter() {
            if (this.locations.length === 1) {
                return {
                    lat: this.locations[0].lat,
                    lng: this.locations[0].lng,
                    zoom: 14
                };
            }

            const coords = this.locations.map(loc => ({
                lat: parseFloat(loc.lat),
                lng: parseFloat(loc.lng)
            }));

            const centerLat = coords.reduce((sum, c) => sum + c.lat, 0) / coords.length;
            const centerLng = coords.reduce((sum, c) => sum + c.lng, 0) / coords.length;

            const lats = coords.map(c => c.lat);
            const lngs = coords.map(c => c.lng);
            const maxDiff = Math.max(
                Math.max(...lats) - Math.min(...lats),
                Math.max(...lngs) - Math.min(...lngs)
            );

            const zoom = maxDiff > 1 ? 10 :
                        maxDiff > 0.5 ? 11 :
                        maxDiff > 0.1 ? 12 :
                        maxDiff > 0.05 ? 13 :
                        maxDiff > 0.01 ? 14 : 15;

            return { lat: centerLat, lng: centerLng, zoom };
        },

        addControls() {
            this.addControl('bottomright', 'Show all locations', this.getShowAllIcon(), () => this.showAllLocations());
            this.addControl('bottomright', 'Show my location', this.getGeolocationIcon(), () => this.centerOnUser());
        },

        addControl(position, title, iconHTML, onClick) {
            const Control = L.Control.extend({
                options: { position },
                onAdd: () => {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    const button = L.DomUtil.create('a', 'leaflet-control-custom', container);
                    
                    button.innerHTML = iconHTML;
                    button.href = '#';
                    button.title = title;
                    Object.assign(button.style, {
                        width: '30px',
                        height: '30px',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        textDecoration: 'none',
                        color: '#333',
                        backgroundColor: 'white',
                        borderRadius: '4px'
                    });
                    
                    L.DomEvent.on(button, 'click', (e) => {
                        L.DomEvent.stopPropagation(e);
                        L.DomEvent.preventDefault(e);
                        onClick.call(this);
                    });
                    
                    return container;
                }
            });
            
            this.map.addControl(new Control());
        },

        getShowAllIcon() {
            return `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
            </svg>`;
        },

        getGeolocationIcon() {
            return `<svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="10" cy="10" r="3"/>
                <path d="M10 1v3M10 16v3M1 10h3M16 10h3"/>
            </svg>`;
        },

        calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371e3;
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lng2 - lng1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                    Math.cos(φ1) * Math.cos(φ2) *
                    Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c;
        },

        addLocationMarkers() {
            this.locations.forEach(location => {
                const marker = L.marker([location.lat, location.lng], {
                icon: this.defaultIcon,
                opacity: location.passed ? 0.3 : 1,
            })
            .addTo(this.map)
            .bindPopup(`<b>${location.title || location.name}</b><br>${location.description}`);
                
                this.markers.push({ marker, location });
            });
        },

        updateClosestMarkerColor(userLat, userLng) {
            if (this.markers.length === 0) return;

            let closestMarker = null;
            let minDistance = Infinity;

            this.markers.forEach(({ marker, location }) => {
                const distance = this.calculateDistance(
                    userLat, 
                    userLng, 
                    parseFloat(location.lat), 
                    parseFloat(location.lng)
                );

                if (distance < minDistance) {
                    minDistance = distance;
                    closestMarker = { marker, location, distance };
                }
            });

            if (this.currentClosestMarker && 
                this.currentClosestMarker.marker !== closestMarker.marker) {
                this.currentClosestMarker.marker.setIcon(this.defaultIcon);
            }

            if (closestMarker.distance <= this.range) {
                closestMarker.marker.setIcon(this.redIcon);
                this.currentClosestMarker = closestMarker;
                if( !closestMarker.location.passed ) {
                    this.activeLevelId = closestMarker.location.id;
                    this.isNear = true;
                }
            } else {
                if (this.currentClosestMarker) {
                    this.currentClosestMarker.marker.setIcon(this.defaultIcon);
                    this.currentClosestMarker = null;
                }
                this.activeLevelId = null;
                this.isNear = false;
            }
        },

        saveInitialBounds() {
            if (this.locations?.length) {
                const points = this.locations.map(loc => [
                    parseFloat(loc.lat), 
                    parseFloat(loc.lng)
                ]);
                this.initialBounds = L.latLngBounds(points);
            }
        },

        async addUserLocation() {
            if (!navigator.geolocation) {
                this.loading = false;
                return;
            }

            this.watchId = navigator.geolocation.watchPosition(
                (position) => this.handleUserPosition(position),
                (error) => this.handleGeolocationError(error),
                {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 5000
                }
            );
        },

        handleUserPosition(position) {
            const { latitude: lat, longitude: lng } = position.coords;
            
            if (this.userMarker) {
                this.userMarker.setLatLng([lat, lng]);
            } else {
                this.createUserMarker(lat, lng);
                this.fitBoundsWithUser(lat, lng);
            }
            
            this.updateClosestMarkerColor(lat, lng);
            this.loading = false;
        },

        handleGeolocationError(error) {
            console.error('Geolocation error:', error);
            this.loading = false;
        },

        createUserMarker(lat, lng) {
            const userIcon = L.divIcon({
                className: 'you-are-here',
                html: '<div class="you-are-here"></div>',
                iconSize: [15, 15],
                iconAnchor: [10, 10],
            });

            this.userMarker = L.marker([lat, lng], {
                icon: userIcon,
                interactive: false,
            }).addTo(this.map).bindPopup('You are here');
        },

        fitBoundsWithUser(lat, lng) {
            const allPoints = [
                ...this.locations.map(loc => [parseFloat(loc.lat), parseFloat(loc.lng)]),
                [lat, lng]
            ];
            
            const bounds = L.latLngBounds(allPoints);
            this.map.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 15
            });
        },

        showAllLocations() {
            if (this.initialBounds) {
                this.map.flyToBounds(this.initialBounds, {
                    padding: [50, 50],
                    maxZoom: 15,
                    duration: 1
                });
            } else {
                const center = this.calculateCenter();
                this.map.flyTo([center.lat, center.lng], center.zoom, {
                    duration: 1
                });
            }
        },

        centerOnUser() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported');
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude: lat, longitude: lng } = position.coords;
                    if (this.userMarker) {
                        this.userMarker.setLatLng([lat, lng]);
                    } else {
                        this.createUserMarker(lat, lng);
                    }
                    
                    this.updateClosestMarkerColor(lat, lng);
                    this.userMarker.openPopup();
                    
                    this.map.flyTo([lat, lng], 15, { duration: 1 });
                },
                (error) => {
                    console.error('Geolocation error:', error);
                    alert('Unable to get your location');
                }
            );
        },

        stopWatching() {
            if (this.watchId) {
                navigator.geolocation.clearWatch(this.watchId);
            }
        }
    }
}