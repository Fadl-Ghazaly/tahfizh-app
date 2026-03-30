<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- 4 Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Box Total Santri -->
                <div class="rounded-lg shadow-lg p-6 text-white card-hover transition duration-300 transform hover:-translate-y-1" style="background-color: var(--primary)">
                    <h3 class="text-lg font-semibold mb-2 text-white/90">Total Santri</h3>
                    <p class="text-3xl font-bold">{{ $totalSantri }}</p>
                </div>
                <!-- Box Total Setoran -->
                <div class="bg-green-500 rounded-lg shadow-lg p-6 text-white card-hover transition duration-300 transform hover:-translate-y-1">
                    <h3 class="text-lg font-semibold mb-2">Setoran Bulan Ini</h3>
                    <p class="text-3xl font-bold">{{ $totalSetoranBulanIni }}</p>
                </div>
                <!-- Box Rata-rata -->
                <div class="bg-orange-500 rounded-lg shadow-lg p-6 text-white card-hover transition duration-300 transform hover:-translate-y-1">
                    <h3 class="text-lg font-semibold mb-2">Rata2 Progress</h3>
                    <p class="text-3xl font-bold">{{ $rataProgress }} <span class="text-sm font-normal">Juz</span></p>
                </div>
                <!-- Box Terbaik -->
                <div class="bg-purple-500 rounded-lg shadow-lg p-6 text-white card-hover transition duration-300 transform hover:-translate-y-1">
                    <h3 class="text-lg font-semibold mb-2">Santri Terbaik</h3>
                    <p class="text-xl font-bold truncate" title="{{ $santriTerbaik->santri->nama_lengkap ?? '-' }}">{{ $santriTerbaik->santri->nama_lengkap ?? '-' }}</p>
                    <p class="text-sm mt-1">{{ $santriTerbaik->target_juz ?? 0 }} Juz</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Bar Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6" x-data="{
                    init() {
                        const style = getComputedStyle(document.documentElement);
                        const primary = style.getPropertyValue('--primary').trim() || '#3b82f6';
                        
                        Chart.defaults.color = '#9ca3af';
                        Chart.defaults.font.family = 'Figtree, sans-serif';
                        new Chart($refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: {{ Js::from($kelasLabels) }},
                                datasets: [{
                                    label: 'Rata-rata Juz',
                                    data: {{ Js::from($progressPerKelas) }},
                                    backgroundColor: primary,
                                    borderRadius: 4
                                }]
                            },
                            options: { responsive: true, plugins: { legend: { display: false } } }
                        });
                    }
                }">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Progress Hafalan per Kelas</h3>
                    <canvas x-ref="canvas"></canvas>
                </div>
                <!-- Line Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6" x-data="{
                    init() {
                        const style = getComputedStyle(document.documentElement);
                        const primary = style.getPropertyValue('--primary').trim() || '#10b981';

                        new Chart($refs.canvas, {
                            type: 'line',
                            data: {
                                labels: {{ Js::from($weeks) }},
                                datasets: [{
                                    label: 'Setoran',
                                    data: {{ Js::from($trendSetoran) }},
                                    borderColor: primary,
                                    backgroundColor: primary + '33',
                                    tension: 0.4,
                                    fill: true
                                }]
                            },
                            options: { responsive: true, plugins: { legend: { display: false } } }
                        });
                    }
                }">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Trend Setoran Minggu Ini</h3>
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Pie Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6" x-data="{
                    init() {
                        new Chart($refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: ['0-1 Juz', '2-5 Juz', '6-10 Juz', '11-20 Juz', '21-30 Juz'],
                                datasets: [{
                                    data: [
                                        {{ $distHafalan['0_1'] }},
                                        {{ $distHafalan['2_5'] }},
                                        {{ $distHafalan['6_10'] }},
                                        {{ $distHafalan['11_20'] }},
                                        {{ $distHafalan['21_30'] }}
                                    ],
                                    backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#10b981'],
                                    borderWidth: 0,
                                }]
                            },
                            options: { responsive: true, cutout: '65%' }
                        });
                    }
                }">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Distribusi Hafalan</h3>
                    <canvas x-ref="canvas"></canvas>
                </div>

                <!-- Top 10 Terbaik -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 col-span-1 lg:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Top 10 Santri Terbaik</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Rank</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Kelas</th>
                                    <th class="px-4 py-3">Juz</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top10Terbaik as $index => $th)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full text-[var(--primary)] flex items-center justify-center font-bold" style="background-color: rgba(var(--primary-rgb), 0.2)">
                                                {{ substr($th->santri->nama_lengkap ?? 'A', 0, 1) }}
                                            </div>
                                            <span>{{ $th->santri->nama_lengkap ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $th->santri->kelas ?? '-' }} {{ $th->santri->kelas_halaqah ?? '' }}</td>
                                    <td class="px-4 py-3 font-bold text-green-500">{{ $th->target_juz }} Juz</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top 10 Perhatian -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Top 10 Butuh Perhatian (Progress < 5 Juz)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-red-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Kelas</th>
                                <th class="px-4 py-3">Ustadz</th>
                                <th class="px-4 py-3">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($top10Perhatian as $th)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $th->santri->nama_lengkap ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $th->santri->kelas ?? '-' }} {{ $th->santri->kelas_halaqah ?? '' }}</td>
                                <td class="px-4 py-3">{{ $th->santri->ustadz->nama_lengkap ?? '-' }}</td>
                                <td class="px-4 py-3 font-bold text-red-500">{{ $th->target_juz }} Juz</td>
                            </tr>
                            @endforeach
                            @if(count($top10Perhatian) == 0)
                            <tr><td colspan="4" class="px-4 py-3 text-center">Tidak ada santri yang butuh perhatian khusus.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
