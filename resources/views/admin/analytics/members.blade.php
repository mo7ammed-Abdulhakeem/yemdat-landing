<x-admin-layout>
    <x-slot name="header">
        Member Analytics / تحليلات الأعضاء
    </x-slot>

    <div x-data="memberAnalytics()" x-init="fetchData()" class="space-y-6">
        
        <!-- Top Filters (Slicers) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Date (From) / تاريخ (من)</label>
                <input type="date" x-model="filters.start_date" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Date (To) / تاريخ (إلى)</label>
                <input type="date" x-model="filters.end_date" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Country / الدولة</label>
                <select x-model="filters.country" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                    <option value="">All Countries</option>
                    <template x-for="country in availableFilters.countries" :key="country">
                        <option :value="country" x-text="country"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Specialty / التخصص</label>
                <select x-model="filters.specialty" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                    <option value="">All Specialties</option>
                    <template x-for="specialty in availableFilters.specialties" :key="specialty">
                        <option :value="specialty" x-text="specialty"></option>
                    </template>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-4 flex justify-end mt-2">
                <button @click="resetFilters()" class="text-sm font-semibold text-gray-600 hover:text-yemdat-brown px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md border border-gray-200 transition">
                    Reset Filters / إعادة ضبط الفلاتر
                </button>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Growth Trend -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Audience Growth Trend / إتجاه نمو الجمهور</h3>
                <div class="relative h-72">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Gender Distribution -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Gender Distribution / التوزيع حسب الجنس</h3>
                <div class="relative h-64">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

            <!-- Education Breakdown -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Education Level / المستوى التعليمي</h3>
                <div class="relative h-64">
                    <canvas id="educationChart"></canvas>
                </div>
            </div>

            <!-- Top Specialties -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Top Specialties / أعلى التخصصات</h3>
                <div class="relative h-72">
                    <canvas id="specialtyChart"></canvas>
                </div>
            </div>

            <!-- Geographic Spread -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Geographic Spread / التوزيع الجغرافي</h3>
                <div class="relative h-72">
                    <canvas id="geoChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Loading Overlay -->
        <div x-show="loading" class="fixed inset-0 bg-white/50 z-50 flex items-center justify-center" style="display: none;">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yemdat-brown"></div>
        </div>
    </div>

    <!-- Chart.js Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('memberAnalytics', () => ({
                loading: false,
                filters: {
                    start_date: '',
                    end_date: '',
                    country: '',
                    specialty: ''
                },
                availableFilters: {
                    countries: [],
                    specialties: []
                },
                charts: {}, // Store chart instances to destroy them before re-render

                resetFilters() {
                    this.filters = { start_date: '', end_date: '', country: '', specialty: '' };
                    this.fetchData();
                },

                async fetchData() {
                    this.loading = true;
                    try {
                        // Build query string
                        const params = new URLSearchParams(this.filters).toString();
                        const response = await fetch(`{{ route('admin.analytics.api.members') }}?${params}`);
                        const data = await response.json();
                        
                        this.availableFilters = data.filters;
                        this.renderCharts(data);
                    } catch (error) {
                        console.error('Error fetching analytics data:', error);
                        alert('Failed to load analytics data.');
                    } finally {
                        this.loading = false;
                    }
                },

                renderCharts(data) {
                    // Common styling colors
                    const brandColors = ['#593E2D', '#C88D16', '#F2CB57', '#8c6239', '#e0a924', '#f5d97d', '#402a1d', '#996c11', '#f0c13c', '#261911'];

                    // 1. Growth Chart (Line)
                    this.initChart('growthChart', 'line', {
                        labels: data.growthTrend.map(d => d.date),
                        datasets: [{
                            label: 'New Registrations',
                            data: data.growthTrend.map(d => d.count),
                            borderColor: '#C88D16',
                            backgroundColor: 'rgba(200, 141, 22, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false
                    });

                    // 2. Gender Chart (Donut)
                    this.initChart('genderChart', 'doughnut', {
                        labels: data.genderDist.map(d => d.gender),
                        datasets: [{
                            data: data.genderDist.map(d => d.count),
                            backgroundColor: ['#593E2D', '#F2CB57', '#C88D16', '#ccc']
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%'
                    });

                    // 3. Education Chart (Bar)
                    this.initChart('educationChart', 'bar', {
                        labels: data.educationDist.map(d => d.education_level),
                        datasets: [{
                            label: 'Members',
                            data: data.educationDist.map(d => d.count),
                            backgroundColor: '#C88D16'
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false
                    });

                    // 4. Specialty Chart (Horizontal Bar)
                    this.initChart('specialtyChart', 'bar', {
                        labels: data.topSpecialties.map(d => d.specialty),
                        datasets: [{
                            label: 'Members',
                            data: data.topSpecialties.map(d => d.count),
                            backgroundColor: brandColors
                        }]
                    }, {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (e, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const clickedSpecialty = data.topSpecialties[index].specialty;
                                // Cross-filter logic
                                if (this.filters.specialty !== clickedSpecialty) {
                                    this.filters.specialty = clickedSpecialty;
                                    this.fetchData();
                                }
                            }
                        }
                    });

                    // 5. Geographic Spread (Bar for simplicity instead of geo map extension)
                    this.initChart('geoChart', 'bar', {
                        labels: data.geoSpread.map(d => d.country),
                        datasets: [{
                            label: 'Members per Country',
                            data: data.geoSpread.map(d => d.count),
                            backgroundColor: '#593E2D'
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (e, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const clickedCountry = data.geoSpread[index].country;
                                // Cross-filter logic
                                if (this.filters.country !== clickedCountry) {
                                    this.filters.country = clickedCountry;
                                    this.fetchData();
                                }
                            }
                        }
                    });
                },

                initChart(canvasId, type, data, options) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx) return;

                    // Destroy existing chart if it exists to prevent overlap
                    if (this.charts[canvasId]) {
                        this.charts[canvasId].destroy();
                    }

                    // Create new chart
                    this.charts[canvasId] = new Chart(ctx, {
                        type: type,
                        data: data,
                        options: {
                            ...options,
                            plugins: {
                                legend: {
                                    position: type === 'doughnut' ? 'right' : 'top',
                                    display: type === 'doughnut' || type === 'line'
                                }
                            }
                        }
                    });
                }
            }));
        });
    </script>
</x-admin-layout>
