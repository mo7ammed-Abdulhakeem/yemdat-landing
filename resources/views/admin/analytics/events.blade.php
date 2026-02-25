<x-admin-layout>
    <x-slot name="header">
        Event Performance Analytics / تحليلات أداء الفعاليات
    </x-slot>

    <div x-data="eventAnalytics()" x-init="fetchData()" class="space-y-6">
        
        <!-- Top Filters (Slicers) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Event Date (From) / تاريخ الفعالية (من)</label>
                <input type="date" x-model="filters.start_date" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Event Date (To) / تاريخ الفعالية (إلى)</label>
                <input type="date" x-model="filters.end_date" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Specific Event / فعالية محددة</label>
                <select x-model="filters.event_id" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                    <option value="">All Events</option>
                    <template x-for="event in availableFilters.eventsList" :key="event.id">
                        <option :value="event.id" x-text="event.title_en"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Modality / نوع الفعالية</label>
                <select x-model="filters.location_type" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                    <option value="">Any Location</option>
                    <option value="Zoom">Virtual / Zoom</option>
                    <option value="Online">Online</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lecturer / المُحاضر</label>
                <select x-model="filters.lecturer" @change="fetchData()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                    <option value="">All Lecturers</option>
                    <template x-for="lecturer in availableFilters.lecturers" :key="lecturer">
                        <option :value="lecturer" x-text="lecturer"></option>
                    </template>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-5 flex justify-end mt-2">
                <button @click="resetFilters()" class="text-sm font-semibold text-gray-600 hover:text-yemdat-brown px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md border border-gray-200 transition">
                    Reset Filters / إعادة ضبط الفلاتر
                </button>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Event Attendance -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Event Attendance (Top 10) / حضور الفعاليات</h3>
                <div class="relative h-72">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Active vs Past -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Event Status Distribution / حالة الفعاليات</h3>
                <div class="relative h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Top Lecturers -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Most Active Lecturers / أنشط المحاضرين</h3>
                <div class="relative h-64">
                    <canvas id="lecturerChart"></canvas>
                </div>
            </div>

            <!-- Registration Pace -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Global Event Registration Pace / وتيرة تسجيل الفعاليات</h3>
                <div class="relative h-72">
                    <canvas id="paceChart"></canvas>
                </div>
            </div>

            <!-- Events Timeline -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Events Volume Timeline (By Month) / حجم الفعاليات شهرياً</h3>
                <div class="relative h-64">
                    <canvas id="timelineChart"></canvas>
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
            Alpine.data('eventAnalytics', () => ({
                loading: false,
                filters: {
                    start_date: '',
                    end_date: '',
                    event_id: '',
                    location_type: '',
                    lecturer: ''
                },
                availableFilters: {
                    lecturers: [],
                    eventsList: []
                },
                charts: {}, // Store chart instances

                resetFilters() {
                    this.filters = { start_date: '', end_date: '', event_id: '', location_type: '', lecturer: '' };
                    this.fetchData();
                },

                async fetchData() {
                    this.loading = true;
                    try {
                        const params = new URLSearchParams(this.filters).toString();
                        const response = await fetch(`{{ route('admin.analytics.api.events') }}?${params}`);
                        const data = await response.json();
                        
                        this.availableFilters = data.filters;
                        this.renderCharts(data);
                    } catch (error) {
                        console.error('Error fetching event analytics data:', error);
                        alert('Failed to load event analytics data.');
                    } finally {
                        this.loading = false;
                    }
                },

                renderCharts(data) {
                    const brandColors = ['#593E2D', '#C88D16', '#F2CB57', '#8c6239', '#e0a924', '#f5d97d', '#402a1d', '#996c11', '#f0c13c', '#261911'];

                    // 1. Attendance Chart (Bar)
                    this.initChart('attendanceChart', 'bar', {
                        labels: data.attendance.map(a => a.title.length > 20 ? a.title.substring(0, 20) + '...' : a.title),
                        datasets: [{
                            label: 'Total RSVP Registrations',
                            data: data.attendance.map(a => a.count),
                            backgroundColor: '#C88D16'
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false
                    });

                    // 2. Status Chart (Pie)
                    this.initChart('statusChart', 'pie', {
                        labels: data.activeVsPast.map(s => s.status),
                        datasets: [{
                            data: data.activeVsPast.map(s => s.count),
                            backgroundColor: ['#F2CB57', '#593E2D']
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false
                    });

                    // 3. Lecturer Chart (Horizontal Bar)
                    this.initChart('lecturerChart', 'bar', {
                        labels: data.topLecturers.map(l => l.lecturer),
                        datasets: [{
                            label: 'Sessions Taught',
                            data: data.topLecturers.map(l => l.count),
                            backgroundColor: brandColors
                        }]
                    }, {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (e, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const clickedLecturer = data.topLecturers[index].lecturer;
                                if (this.filters.lecturer !== clickedLecturer) {
                                    this.filters.lecturer = clickedLecturer;
                                    this.fetchData();
                                }
                            }
                        }
                    });

                    // 4. Registration Pace (Line)
                    this.initChart('paceChart', 'line', {
                        labels: data.regPace.map(p => p.date),
                        datasets: [{
                            label: 'Daily Signups for Filtered Events',
                            data: data.regPace.map(p => p.count),
                            borderColor: '#593E2D',
                            backgroundColor: 'rgba(89, 62, 45, 0.1)',
                            fill: true,
                            tension: 0.3
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false
                    });

                    // 5. Timeline (Bar)
                    this.initChart('timelineChart', 'bar', {
                        labels: data.timeline.map(t => t.month),
                        datasets: [{
                            label: 'Number of Events Conducted',
                            data: data.timeline.map(t => t.count),
                            backgroundColor: '#F2CB57'
                        }]
                    }, {
                        responsive: true,
                        maintainAspectRatio: false
                    });
                },

                initChart(canvasId, type, data, options) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx) return;

                    if (this.charts[canvasId]) {
                        this.charts[canvasId].destroy();
                    }

                    this.charts[canvasId] = new Chart(ctx, {
                        type: type,
                        data: data,
                        options: {
                            ...options,
                            plugins: {
                                legend: {
                                    position: type === 'pie' ? 'right' : 'top',
                                    display: type === 'pie' || type === 'line'
                                }
                            }
                        }
                    });
                }
            }));
        });
    </script>
</x-admin-layout>
