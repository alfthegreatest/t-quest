<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
        padding: 10px;
        max-height: 90vh;
        overflow-y: auto;
    }
</style>