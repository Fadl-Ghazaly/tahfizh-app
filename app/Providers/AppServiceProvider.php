<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global Settings & Themes (cached for 60 seconds to avoid per-request DB hits)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {

                $allSettings = \Illuminate\Support\Facades\Cache::remember('app_settings', 60, function () {
                    return \App\Models\Setting::pluck('value', 'key')->toArray();
                });

                $themes = [
                    'blue'   => ['primary' => '#3b82f6', 'primary_rgb' => '59, 130, 246',  'sidebar' => '#1e3a8a', 'accent' => '#60a5fa', 'accent_rgb' => '96, 165, 250',  'hover' => '#2563eb'],
                    'green'  => ['primary' => '#10b981', 'primary_rgb' => '16, 185, 129',  'sidebar' => '#1a4a38', 'accent' => '#275d49', 'accent_rgb' => '39, 93, 73',    'hover' => '#059669'],
                    'orange' => ['primary' => '#f59e0b', 'primary_rgb' => '245, 158, 11',  'sidebar' => '#7c2d12', 'accent' => '#9a3412', 'accent_rgb' => '154, 52, 18',   'hover' => '#d97706'],
                    'purple' => ['primary' => '#8b5cf6', 'primary_rgb' => '139, 92, 246',  'sidebar' => '#4c1d95', 'accent' => '#5b21b6', 'accent_rgb' => '91, 33, 182',   'hover' => '#7c3aed'],
                    'red'    => ['primary' => '#ef4444', 'primary_rgb' => '239, 68, 68',   'sidebar' => '#7f1d1d', 'accent' => '#991b1b', 'accent_rgb' => '153, 27, 27',   'hover' => '#dc2626'],
                    'teal'   => ['primary' => '#14b8a6', 'primary_rgb' => '20, 184, 166',  'sidebar' => '#134e4a', 'accent' => '#115e59', 'accent_rgb' => '17, 94, 89',    'hover' => '#0d9488'],
                ];

                $activeTheme = $allSettings['theme_color'] ?? 'green';
                $theme       = $themes[$activeTheme] ?? $themes['green'];
                $appSettings = [
                    'name'       => $allSettings['app_name'] ?? 'Tahfidz App',
                    'logo'       => $allSettings['app_logo'] ?? null,
                    'theme'      => $theme,
                    'themeName'  => $activeTheme,
                    'primary_rgb'=> $theme['primary_rgb'],
                    'accent_rgb' => $theme['accent_rgb'],
                ];

                view()->share('appSettings', $appSettings);
            }
        } catch (\Exception $e) {
            // Silence if migration not run yet
        }
    }
}
