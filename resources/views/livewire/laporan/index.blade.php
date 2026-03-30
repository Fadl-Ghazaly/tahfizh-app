<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden p-6 md:p-8">
                
                <!-- Filter & Actions -->
                <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full md:w-3/4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                            <input type="month" wire:model.live="filterBulan" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Kelas</label>
                            <select wire:model.live="filterKelas" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Semua Kelas</option>
                                @for($i=7; $i<=12; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                            </select>
                        </div>
                        @if(Auth::user()->role === 'admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Ustadz</label>
                            <select wire:model.live="filterUstadz" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Semua Ustadz</option>
                                @foreach($ustadzs as $u) <option value="{{ $u->id }}">{{ $u->nama_lengkap }}</option> @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="w-full md:w-1/4 flex justify-end">
                        <!-- We use wire:click.prevent and return the download directly from component -->
                        <button wire:click="downloadPdf" class="bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white font-bold py-2 px-6 rounded-lg shadow-md flex items-center transition duration-150 w-full justify-center md:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export PDF
                        </button>
                    </div>
                </div>

                <!-- Stats 4 Boxes -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 border border-gray-200 dark:border-gray-600 border-l-4 border-l-[var(--primary)]">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Santri Aktif</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalSantri'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 border border-gray-200 dark:border-gray-600 border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Setoran</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['totalSetoran'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 border border-gray-200 dark:border-gray-600 border-l-4 border-l-yellow-500">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Nilai</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['rataNilai'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 border border-gray-200 dark:border-gray-600 border-l-4 border-l-purple-500 flex flex-col justify-center">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Progress Tercepat</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1 truncate" title="{{ $stats['tercepat']->santri->nama_lengkap ?? '-' }}">
                            {{ $stats['tercepat']->santri->nama_lengkap ?? '-' }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold">{{ $stats['tercepat']->target_juz ?? 0 }} Juz</p>
                    </div>
                </div>

                <!-- Ranking & Progress Table -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-900 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4">Rank</th>
                                <th class="px-6 py-4">Nama Santri</th>
                                <th class="px-6 py-4">Kelas</th>
                                <th class="px-6 py-4">Ustadz</th>
                                <th class="px-6 py-4 w-1/3">Progress Target 30 Juz</th>
                                <th class="px-6 py-4 text-right">Juz</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['ranking'] as $index => $target)
                            @php $pct = min(100, round(($target->target_juz / 30) * 100)); @endphp
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 font-bold {{ $index < 3 && (!($stats['ranking'] instanceof \Illuminate\Pagination\LengthAwarePaginator) || $stats['ranking']->currentPage() == 1) ? 'text-[var(--primary)]' : 'text-gray-900 dark:text-white' }}">
                                    #{{ ($stats['ranking'] instanceof \Illuminate\Pagination\LengthAwarePaginator ? $stats['ranking']->firstItem() + $index : $index + 1) }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $target->santri->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $target->santri->kelas ?? '-' }} {{ $target->santri->kelas_halaqah ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $target->santri->ustadz->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center w-full">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2">
                                            <div class="bg-[var(--primary)] h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 w-10 text-right">{{ $pct }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-right">
                                    {{ $target->target_juz }} / 30
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data santri untuk kriteria ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($stats['ranking'] instanceof \Illuminate\Pagination\LengthAwarePaginator && $stats['ranking']->hasPages())
                <div class="mt-6 px-2">
                    {{ $stats['ranking']->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
