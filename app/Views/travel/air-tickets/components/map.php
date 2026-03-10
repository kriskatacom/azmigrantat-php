<?php
$id = 'map-' . uniqid();
$height = $height ?? '600px';
$zoom = (int)($zoom ?? 6);
$items = $items ?? [];
$center = $center ?? [42.7339, 25.4858];
$marker_img = $marker_img ?? "/assets/images/marker-icon.png";
?>

<script>
    document.addEventListener('alpine:init', () => {
        if (!window.mapComponentRegistered) {
            Alpine.data('mapComponent', () => ({
                map: null,
                airports: <?= json_encode($items) ?>,
                markers: [],
                search: '',
                filteredResults: [],
                openModal: false,
                selectedAirport: null,
                isNavigating: false, // Флаг, за да знаем кога тече автоматично преместване

                initMap() {
                    this.$nextTick(() => {
                        if (this.map) return;

                        this.map = L.map('<?= $id ?>', {
                            scrollWheelZoom: true,
                            zoomControl: true
                        }).setView([<?= $center[0] ?>, <?= $center[1] ?>], <?= $zoom ?>);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(this.map);

                        this.map.on('moveend', () => {
                            if (this.isNavigating && this.selectedAirport) {
                                this.openModal = true;
                                this.isNavigating = false;
                            }
                        });

                        const customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div class="marker-container"><img src="<?= $marker_img ?>" class="clean-marker-img"></div>`,
                            iconSize: [45, 45],
                            iconAnchor: [22, 45]
                        });

                        this.airports.forEach(airport => {
                            if (airport.latitude && airport.longitude) {
                                const marker = L.marker([airport.latitude, airport.longitude], {
                                    icon: customIcon
                                }).addTo(this.map);

                                marker.on('click', () => {
                                    this.selectedAirport = airport;
                                    this.map.flyTo([airport.latitude, airport.longitude], 14);
                                    this.isNavigating = true;
                                });

                                this.markers.push({
                                    id: airport.id,
                                    marker: marker
                                });
                            }
                        });

                        if (this.markers.length > 1) {
                            const group = new L.featureGroup(this.markers.map(m => m.marker));
                            this.map.fitBounds(group.getBounds().pad(0.1));
                        }

                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 300);
                    });
                },

                filterAirports() {
                    const term = this.search.toLowerCase().trim();
                    if (term === '') {
                        this.filteredResults = [];
                        return;
                    }
                    this.filteredResults = this.airports.filter(a =>
                        a.name.toLowerCase().includes(term) ||
                        (a.description && a.description.toLowerCase().includes(term))
                    ).slice(0, 8);
                },

                selectAirport(airport) {
                    this.selectedAirport = airport;
                    this.isNavigating = true;
                    this.search = '';

                    this.map.flyTo([airport.latitude, airport.longitude], 14, {
                        animate: true,
                        duration: 1.5
                    });
                },

                showAirport(airport) {
                    this.selectedAirport = airport;
                    this.openModal = true;
                }
            }));
            window.mapComponentRegistered = true;
        }
    });
</script>

<div x-data="mapComponent()" x-intersect.once="initMap()" class="w-full relative bg-gray-50" style="height: <?= $height ?>;">

    <div class="absolute top-6 left-4 right-4 md:right-auto md:left-14 z-1001 md:w-96">
        <div class="relative">
            <input
                x-model="search"
                @input.debounce.300ms="filterAirports"
                type="text"
                placeholder="Търси летище или град..."
                class="w-full py-3.5 px-5 rounded-2xl border-none shadow-2xl bg-white/95 backdrop-blur text-sm focus:ring-2 focus:ring-blue-500 outline-hidden text-black font-medium">

            <div
                x-show="search.length > 0 && filteredResults.length > 0"
                x-cloak x-transition
                class="absolute w-full mt-2 bg-white rounded-2xl shadow-2xl max-h-110 overflow-y-auto border border-gray-100 z-1002"
                @click.away="search = ''">
                <template x-for="airport in filteredResults" :key="airport.id">
                    <div
                        @click="selectAirport(airport)"
                        class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-0 flex gap-4 transition-colors">
                        <div class="w-16 h-16 shrink-0 bg-gray-100 rounded-xl overflow-hidden shadow-sm">
                            <template x-if="airport.image_url">
                                <img :src="airport.image_url" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <div class="flex flex-col justify-center overflow-hidden">
                            <h4 class="font-bold text-gray-900 text-sm truncate" x-text="airport.name"></h4>
                            <p class="text-gray-500 text-xs line-clamp-2 mt-1 leading-relaxed" x-text="airport.description"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div id="<?= $id ?>" class="w-full h-full" style="z-index: 1;"></div>

    <div
        x-show="openModal"
        x-cloak
        class="fixed inset-0 z-10000 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
        @click.self="openModal = false">
        <div class="bg-white w-full max-w-3xl rounded-3xl overflow-hidden shadow-2xl relative text-black" x-transition>
            <button @click="openModal = false" class="absolute top-4 right-4 z-20 bg-white/90 p-2 rounded-full shadow-lg hover:rotate-90 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex flex-col md:flex-row h-full">
                <div class="md:w-1/2 h-64 md:h-auto bg-gray-200">
                    <template x-if="selectedAirport?.image_url">
                        <img :src="selectedAirport.image_url" class="w-full h-full object-cover">
                    </template>
                </div>
                <div class="p-8 md:w-1/2 bg-white flex flex-col justify-center">
                    <h2 class="text-2xl font-bold mb-3" x-text="selectedAirport?.name"></h2>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed overflow-y-auto max-h-40" x-text="selectedAirport?.description"></p>
                    <div class="grid grid-cols-1 gap-3">
                        <a :href="selectedAirport?.website_url || '#'" target="_blank" class="w-full text-center bg-blue-600 text-white font-bold py-3.5 rounded-xl uppercase text-xs tracking-wider hover:bg-blue-700 transition-colors">Официален Сайт</a>
                        <a :href="`https://www.google.com/maps/dir/?api=1&destination=${selectedAirport?.latitude},${selectedAirport?.longitude}`" target="_blank" class="w-full text-center bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl uppercase text-xs tracking-wider hover:bg-gray-200 transition-colors">Упътване до там</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    .custom-div-icon {
        background: none !important;
        border: none !important;
    }

    .marker-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .clean-marker-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0px 5px 8px rgba(0, 0, 0, 0.5));
    }

    .line-clamp-2 {
        display: -webkit-box;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>