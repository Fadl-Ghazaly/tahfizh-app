<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <button wire:click="setTab('sabaq')" class="flex-1 py-4 px-6 text-center text-sm font-medium focus:outline-none transition-colors duration-200 {{ $tab === 'sabaq' ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Sabaq (Hafalan Baru)
                    </button>
                    <button wire:click="setTab('sabqi')" class="flex-1 py-4 px-6 text-center text-sm font-medium focus:outline-none transition-colors duration-200 {{ $tab === 'sabqi' ? 'border-b-2 border-green-500 text-green-600 dark:text-green-400 bg-white dark:bg-gray-800' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Sabqi (Ulang Hafalan Baru)
                    </button>
                    <button wire:click="setTab('manzil')" class="flex-1 py-4 px-6 text-center text-sm font-medium focus:outline-none transition-colors duration-200 {{ $tab === 'manzil' ? 'border-b-2 border-purple-500 text-purple-600 dark:text-purple-400 bg-white dark:bg-gray-800' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Manzil (Murajaah Lama)
                    </button>
                </div>

                <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-8">
                    <!-- Form Area -->
                    <div class="flex-1">
                        @if (session()->has('message'))
                            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                                <span class="block sm:inline">{{ session('message') }}</span>
                            </div>
                        @endif

                        <form wire:submit.prevent="store" class="space-y-6">
                            
                            <!-- Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Kelas</label>
                                    <select wire:model.live="filterKelas" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Semua Kelas</option>
                                        @for($i=7; $i<=12; $i++) <option value="{{ $i }}">Kelas {{ $i }}</option> @endfor
                                    </select>
                                </div>
                                @if(Auth::user()->role === 'admin')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Ustadz</label>
                                    <select wire:model.live="filterUstadz" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Semua Ustadz</option>
                                        @foreach($ustadzs as $u) <option value="{{ $u->id }}">{{ $u->nama_lengkap }}</option> @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>

                            <!-- Main Input -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Santri <span class="text-red-500">*</span></label>
                                    <select wire:model="santri_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Santri --</option>
                                        @foreach($santris as $s)
                                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (Kls {{ $s->kelas }})</option>
                                        @endforeach
                                    </select>
                                    @error('santri_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="tanggal" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('tanggal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kehadiran <span class="text-red-500">*</span></label>
                                    <div class="mt-2 flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="kehadiran" value="hadir" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Hadir</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="kehadiran" value="izin" class="text-yellow-500 focus:ring-yellow-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Izin</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="kehadiran" value="sakit" class="text-blue-500 focus:ring-blue-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sakit</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="kehadiran" value="alpha" class="text-red-600 focus:ring-red-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Alpha</span>
                                        </label>
                                    </div>
                                    @error('kehadiran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div x-data="{ open: true }" x-show="$wire.kehadiran === 'hadir'" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-600 rounded-md">
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Juz <span class="text-red-500">*</span></label>
                                        <input type="number" wire:model="juz" min="1" max="30" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('juz') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Surat <span class="text-red-500">*</span></label>
                                        <div class="mt-1 flex rounded-md shadow-sm relative">
                                            <select wire:model="nama_surat" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option value="">-- Pilih Surat --</option>
                                                @foreach($suratList as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                                            </select>
                                        </div>
                                        @error('nama_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ayat Mulai</label>
                                            <input type="number" wire:model.live.debounce.500ms="ayat_mulai" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ayat Selesai</label>
                                            <input type="number" wire:model.live.debounce.500ms="ayat_selesai" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Baris</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="number" wire:model="jumlah_baris" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Otomatis / Manual">
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm bg-transparent" wire:loading wire:target="calculateBaris">...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nilai Kelancaran</label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input type="number" wire:model="nilai_kelancaran" min="0" max="100" class="flex-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <button type="button" wire:click="kurangiNilai" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-red-50 dark:bg-red-900 text-red-600 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-800 transition">
                                                    -5 Poin
                                                </button>
                                            </div>
                                            @error('nilai_kelancaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                                        <textarea wire:model="catatan" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 shadow-md flex items-center text-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Simpan Setoran
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Recent Setoran Sidebar -->
                    <div class="w-full lg:w-1/3 mt-8 lg:mt-0 lg:border-l lg:border-gray-200 dark:lg:border-gray-700 lg:pl-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex justify-between items-center">
                            <span>Riwayat Hari Ini</span>
                            <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 text-xs px-2 py-1 rounded-full uppercase">{{ $tab }}</span>
                        </h3>
                        
                        <div class="space-y-4">
                            @forelse($recentSetoran as $setoran)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-600 flex items-start space-x-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-{{ $setoran->kehadiran == 'hadir' ? 'green' : 'red' }}-100 flex items-center justify-center font-bold text-{{ $setoran->kehadiran == 'hadir' ? 'green' : 'red' }}-600 mt-1">
                                    {{ substr($setoran->santri->nama_lengkap ?? 'A', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $setoran->santri->nama_lengkap ?? 'Siswa Terhapus' }}
                                    </p>
                                    @if($setoran->kehadiran == 'hadir')
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Juz {{ $setoran->juz }} - {{ $setoran->nama_surat }} (Ayat {{ $setoran->ayat_mulai }}-{{ $setoran->ayat_selesai }})
                                    </p>
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-1">Nilai: {{ $setoran->nilai_kelancaran }}</p>
                                    @else
                                    <p class="text-sm font-bold text-red-500 uppercase mt-1">{{ $setoran->kehadiran }}</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-6 text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                                Belum ada input hari ini.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
