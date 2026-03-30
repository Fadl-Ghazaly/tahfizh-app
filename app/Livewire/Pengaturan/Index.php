<?php

namespace App\Livewire\Pengaturan;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class Index extends Component
{
    use WithFileUploads;

    public $appName;
    public $themeColor;
    public $logo; // new upload
    public $currentLogo; // from db

    public $themes = [
        ['name' => 'Blue Primary', 'value' => 'blue'],
        ['name' => 'Green Nature', 'value' => 'green'],
        ['name' => 'Orange Sunset', 'value' => 'orange'],
        ['name' => 'Purple Royal', 'value' => 'purple'],
        ['name' => 'Red Rose', 'value' => 'red'],
        ['name' => 'Teal Ocean', 'value' => 'teal'],
    ];

    public function mount()
    {
        // Single query instead of 3 separate queries
        $settings = Setting::whereIn('key', ['app_name', 'theme_color', 'app_logo'])
                           ->pluck('value', 'key');

        $this->appName     = $settings->get('app_name', 'Tahfidz Darul Ilmi');
        $this->themeColor  = $settings->get('theme_color', 'blue');
        $this->currentLogo = $settings->get('app_logo');
    }

    public function store()
    {
        $this->validate([
            'appName' => 'required|string|max:255',
            'themeColor' => 'required|string',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        Setting::updateOrCreate(['key' => 'app_name'], ['value' => $this->appName]);
        Setting::updateOrCreate(['key' => 'theme_color'], ['value' => $this->themeColor]);

        if ($this->logo) {
            if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
                Storage::disk('public')->delete($this->currentLogo);
            }
            $path = $this->logo->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
            $this->currentLogo = $path;
        }

        session()->flash('message', 'Pengaturan berhasil disimpan.');

        // Clear settings cache so AppServiceProvider picks up the new theme immediately
        Cache::forget('app_settings');

        return redirect(route('pengaturan.index'));
    }

    public function render()
    {
        return view('livewire.pengaturan.index')
               ->layout('layouts.app', ['header' => __('Pengaturan Aplikasi')]);
    }
}
