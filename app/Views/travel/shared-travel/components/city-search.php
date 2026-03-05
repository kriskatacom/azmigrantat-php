<section>
    <div x-data="citySearchComponent()" class="max-w-5xl mx-auto px-5 md:px-0 -mt-12 relative z-50">
        <div class="bg-white p-5 md:p-7 rounded-md shadow-2xl flex flex-col md:flex-row gap-4 items-end border border-gray-100">

            <div class="flex-1 w-full">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2 ml-1">Тръгване от</label>
                <button @click="openModal('from')" type="button" class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-left hover:border-gray-200 transition-all">
                    <span x-text="fromCity || 'Избери град...'" :class="fromCity ? 'text-gray-900 font-bold' : 'text-gray-400'"></span>
                </button>
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs font-black text-gray-400 uppercase mb-2 ml-1">Пристигане в</label>
                <button @click="openModal('to')" type="button" class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-left hover:border-gray-200 transition-all">
                    <span x-text="toCity || 'Избери град...'" :class="toCity ? 'text-gray-900 font-bold' : 'text-gray-400'"></span>
                </button>
            </div>

            <button @click="search()" class="w-full md:w-auto bg-primary-dark text-white font-bold px-10 py-3.5 rounded-xl hover:bg-black transition-colors shadow-lg shadow-primary-dark/20">
                Търсене
            </button>
        </div>

        <template x-teleport="body">
            <div x-show="isLoading"
                class="fixed inset-0 z-200 bg-white/50 backdrop-blur-[2px] flex items-center justify-center"
                style="display: none;">
                <div class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center gap-4">
                    <div class="w-12 h-12 border-4 border-gray-100 border-t-primary-dark rounded-full animate-spin"></div>
                    <p class="text-sm font-bold text-gray-600 uppercase tracking-widest">Търсим най-добрите пътувания...</p>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fixed inset-0 z-110 flex items-start justify-center pt-10 md:pt-20 px-4 bg-black/60 backdrop-blur-sm">

                <div @click.away="closeModal()"
                    class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[70vh]">

                    <div class="p-5 border-b sticky top-0 bg-white z-10">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900" x-text="targetField === 'from' ? 'Тръгване от' : 'Пристигане в'"></h3>
                            <button @click="closeModal()" class="text-gray-400 hover:text-black text-2xl">&times;</button>
                        </div>
                        <input type="text"
                            x-ref="citySearch"
                            x-model="searchQuery"
                            placeholder="Напиши името на града..."
                            class="w-full px-4 py-3 bg-gray-100 border-none rounded-xl focus:ring-2 focus:ring-primary-dark outline-none">
                    </div>

                    <div class="overflow-y-auto p-2 custom-scrollbar">
                        <div class="grid grid-cols-1 gap-1">
                            <template x-for="city in filteredCities.slice(0, displayLimit)" :key="city.name">
                                <button @click="selectCity(city)"
                                    class="text-left px-4 py-3 hover:bg-gray-50 rounded-xl transition-colors flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 border border-gray-100">
                                            <img :src="city.image_url || '/assets/images/no-image.png'" class="w-full h-full object-cover">
                                        </div>
                                        <span x-text="city.name" class="text-gray-700 group-hover:text-primary-dark font-bold"></span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </template>
                        </div>

                        <template x-if="filteredCities.length > displayLimit">
                            <button @click="displayLimit += 20"
                                class="w-full py-4 text-primary-dark font-black hover:underline transition-colors border-t mt-2 uppercase text-xs tracking-widest">
                                Покажи още градове...
                            </button>
                        </template>

                        <template x-if="filteredCities.length === 0">
                            <div class="p-10 text-center text-gray-400">
                                Няма намерен град с това име.
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</section>

<script>
    function citySearchComponent() {
        return {
            allCities: <?= $citiesJson ?>,
            showModal: false,
            isLoading: false,
            searchQuery: '',
            targetField: '',
            fromCity: '',
            fromSlug: '',
            toCity: '',
            toSlug: '',
            displayLimit: 20,

            init() {
                this.$watch('showModal', value => {
                    document.body.style.overflow = value ? 'hidden' : '';
                });
            },

            get filteredCities() {
                if (this.searchQuery === '') return this.allCities;

                const q = this.searchQuery.toLowerCase();

                return this.allCities.filter(city => {
                    const nameMatch = city.name.toLowerCase().includes(q);

                    const slugMatch = city.slug ? city.slug.toLowerCase().includes(q) : false;

                    return nameMatch || slugMatch;
                });
            },

            selectCity(city) {
                if (this.targetField === 'from') {
                    this.fromCity = city.name;
                    this.fromSlug = city.slug;
                } else {
                    this.toCity = city.name;
                    this.toSlug = city.slug;
                }
                this.closeModal();
            },

            openModal(field) {
                this.targetField = field;
                this.showModal = true;
                this.searchQuery = '';
                this.displayLimit = 20;
                setTimeout(() => this.$refs.citySearch.focus(), 150);
            },

            closeModal() {
                this.showModal = false;
            },

            search() {
                this.isLoading = true;

                const baseUrl = '/travel/shared-travel/drivers';
                const params = new URLSearchParams();

                if (this.fromSlug) params.append('from', this.fromSlug);
                if (this.toSlug) params.append('to', this.toSlug);

                window.location.href = baseUrl + '?' + params.toString();
            }
        }
    }
</script>