<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg mb-6">
                <!-- Header and Actions -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex flex-col md:flex-row md:space-x-4 w-full md:w-auto">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama / NISN..." class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <select wire:model.live="filterKelas" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-2 md:mt-0">
                            <option value="">Semua Kelas</option>
                            <option value="7">Kelas 7</option>
                            <option value="8">Kelas 8</option>
                            <option value="9">Kelas 9</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 shadow flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Santri
                    </button>
                </div>

                <!-- Messages -->
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-4 rounded shadow-sm relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <!-- Table -->
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Nama Santri</th>
                                <th class="px-4 py-3">L/P</th>
                                <th class="px-4 py-3">Kelas</th>
                                <th class="px-4 py-3">NISN</th>
                                <th class="px-4 py-3">Ustadz Pengampu</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santris as $santri)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4">{{ $loop->iteration + $santris->firstItem() - 1 }}</td>
                                <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ $santri->nama_lengkap }}</td>
                                <td class="px-4 py-4">{{ $santri->jenis_kelamin }}</td>
                                <td class="px-4 py-4">{{ $santri->kelas }} {{ $santri->kelas_halaqah }}</td>
                                <td class="px-4 py-4">{{ $santri->nisn ?? '-' }}</td>
                                <td class="px-4 py-4">{{ $santri->ustadz->nama_lengkap ?? '-' }}</td>
                                <td class="px-4 py-4 text-right flex justify-end space-x-2">
                                    <button wire:click="edit({{ $santri->id }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                                    <button wire:click="delete({{ $santri->id }})" wire:confirm="Yakin ingin menghapus santri ini?" class="text-red-500 hover:text-red-700 font-medium whitespace-nowrap">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-4 py-4 text-center">Tidak ada data santri.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $santris->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="store">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                {{ $santri_id ? 'Edit Santri' : 'Tambah Santri' }}
                            </h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                                <input type="text" wire:model="nama_lengkap" id="nama_lengkap" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                                <select wire:model="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NISN</label>
                                <input type="text" wire:model="nisn" id="nisn" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select wire:model="kelas" id="kelas" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Pilih Kelas</option>
                                    @for($i=7; $i<=12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('kelas') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kelas_halaqah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Halaqah (Huruf)</label>
                                <input type="text" wire:model="kelas_halaqah" id="kelas_halaqah" placeholder="A/B/C" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('kelas_halaqah') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-span-2">
                                <label for="ustadz_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ustadz Pengampu</label>
                                <select wire:model="ustadz_id" id="ustadz_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Pilih Ustadz</option>
                                    @foreach($ustadzs as $u)
                                        <option value="{{ $u->id }}">{{ $u->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                                @error('ustadz_id') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nama_orangtua" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Orangtua</label>
                                <input type="text" wire:model="nama_orangtua" id="nama_orangtua" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="wa_orangtua" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WA Orangtua</label>
                                <input type="text" wire:model="wa_orangtua" id="wa_orangtua" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
