<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg mb-6 max-w-3xl mx-auto overflow-hidden">
                <div class="bg-indigo-600 dark:bg-indigo-900 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Konfigurasi Sistem Pesantren</h3>
                </div>

                <div class="p-6 md:p-8">
                    @if (session()->has('message'))
                        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="store" class="space-y-8">
                        
                        <!-- App Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Pesantren / Aplikasi</label>
                            <input type="text" wire:model="appName" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-lg px-4 py-3">
                            @error('appName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Theme Selection -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Pilihan Tema Warna Utama</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($themes as $theme)
                                <label class="cursor-pointer relative">
                                    <input type="radio" wire:model="themeColor" value="{{ $theme['value'] }}" class="peer sr-only">
                                    <div class="rounded-lg border-2 border-gray-200 dark:border-gray-600 px-4 py-3 flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition peer-checked:border-{{ $theme['value'] }}-500 peer-checked:bg-{{ $theme['value'] }}-50 dark:peer-checked:bg-gray-700">
                                        <div class="w-6 h-6 rounded-full bg-{{ $theme['value'] }}-500 shadow-inner"></div>
                                        <span class="font-medium text-gray-900 dark:text-gray-200">{{ $theme['name'] }}</span>
                                    </div>
                                    <div class="absolute inset-y-0 right-4 flex items-center hidden peer-checked:block text-{{ $theme['value'] }}-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 bg-gray-50 dark:bg-gray-900/50">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Logo Aplikasi</label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <div class="w-32 h-32 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm flex items-center justify-center overflow-hidden shrink-0">
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" class="max-w-full max-h-full object-contain">
                                    @elseif ($currentLogo)
                                        <img src="{{ Storage::url($currentLogo) }}" class="max-w-full max-h-full object-contain">
                                    @else
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                
                                <div class="flex-1 w-full">
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                                <label for="logo-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-1">
                                                    <span>Upload a file</span>
                                                    <input id="logo-upload" wire:model="logo" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                    </div>
                                    <div wire:loading wire:target="logo" class="text-sm text-indigo-600 mt-2 font-medium">Biasa proses upload gambar sementara...</div>
                                    @error('logo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 text-white font-bold py-3 px-8 rounded-lg shadow-lg flex items-center transition duration-150 transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
