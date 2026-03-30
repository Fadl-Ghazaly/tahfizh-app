<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/setoran', \App\Livewire\Setoran\Input::class)->name('setoran.index');
    Route::get('/laporan', \App\Livewire\Laporan\Index::class)->name('laporan.index');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/santri', \App\Livewire\Santri\Index::class)->name('santri.index');
        Route::get('/ustadz', \App\Livewire\Ustadz\Index::class)->name('ustadz.index');
        Route::get('/pengaturan', \App\Livewire\Pengaturan\Index::class)->name('pengaturan.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
