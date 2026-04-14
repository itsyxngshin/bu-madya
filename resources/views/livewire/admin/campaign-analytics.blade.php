<div class="min-h-screen bg-gray-50 p-4 md:p-8 font-sans text-gray-900 pb-32">
    
    {{-- Load Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.campaigns.index') }}" class="text-gray-400 hover:text-orange-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Campaign Impact Report</h1>
                </div>
                <p class="text-sm text-gray-500 ml-9">{{ $campaign->title }}</p>
            </div>
            
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Signatures</p>
                    <p class="text-3xl font-black text-gray-900 leading-none">{{ number_format($totalSignatures) }}</p>
                </div>
            </div>
        </div>

        @if($totalSignatures === 0)
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-200">
                <h3 class="text-xl font-bold text-gray-400">No data to display yet.</h3>
                <p class="text-sm text-gray-400 mt-2">Start sharing your campaign link to gather signatures!</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- CHART 1: Affiliation Breakdown (Doughnut) --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Demographic Breakdown</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="affiliationChart"></canvas>
                    </div>
                </div>

                {{-- CHART 2: Year Level Breakdown (Bar) --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Student Year Levels</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="yearLevelChart"></canvas>
                    </div>
                </div>

                {{-- CHART 3: Top Colleges (Horizontal Bar) --}}
                <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">College Participation (Leaderboard)</h3>
                    <div class="relative h-80 w-full">
                        <canvas id="collegeChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- The Fixed Script Block --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Affiliation Chart
                    const affiliationLabels = @json(array_keys($affiliationData));
                    const affiliationData = @json(array_values($affiliationData));

                    new Chart(document.getElementById('affiliationChart'), {
                        type: 'doughnut',
                        data: {
                            labels: affiliationLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                            datasets: [{
                                data: affiliationData,
                                backgroundColor: ['#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#ef4444'],
                                borderWidth: 0,
                                cutout: '75%'
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                    });

                    // Year Level Chart
                    new Chart(document.getElementById('yearLevelChart'), {
                        type: 'bar',
                        data: {
                            labels: @json(array_keys($yearLevelData)),
                            datasets: [{
                                label: 'Students',
                                data: @json(array_values($yearLevelData)),
                                backgroundColor: '#f97316',
                                borderRadius: 6
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
                    });

                    // College Chart
                    new Chart(document.getElementById('collegeChart'), {
                        type: 'bar',
                        data: {
                            labels: @json(array_keys($collegeData)),
                            datasets: [{
                                label: 'Signatures',
                                data: @json(array_values($collegeData)),
                                backgroundColor: '#2563eb',
                                borderRadius: 6
                            }]
                        },
                        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
                    });
                });
            </script>
        @endif
    </div>
</div>