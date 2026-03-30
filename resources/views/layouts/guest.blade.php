<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appSettings['name'] ?? config('app.name', 'Tahfidz Darul Ilmi') }}</title>

    {{-- Non-blocking Google Fonts --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-bg {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 70% 50% at 15% 0%, rgba(16,185,129,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 85% 100%, rgba(59,130,246,0.12) 0%, transparent 60%),
                #0f172a;
        }
        .auth-container {
            position: relative; z-index: 10;
            width: 100%; max-width: 440px;
            padding: 1.5rem;
        }
        .auth-logo {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 2rem; text-decoration: none;
        }
        .auth-logo-icon {
            width: 64px; height: 64px; border-radius: 18px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin-bottom: 0.75rem;
            box-shadow: 0 8px 32px rgba(16,185,129,0.35);
        }
        .auth-logo-name {
            font-size: 1.25rem; font-weight: 800;
            background: linear-gradient(135deg, #f8fafc 30%, #6ee7b7);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .auth-logo-sub { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }
        .auth-card {
            background: rgba(30,41,59,0.7);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 2.25rem 2rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }
        .auth-card h2 {
            font-size: 1.4rem; font-weight: 800; color: #f1f5f9;
            margin-bottom: 0.3rem;
        }
        .auth-card .subtitle {
            color: #64748b; font-size: 0.875rem; margin-bottom: 1.75rem;
        }
        .back-link {
            display: flex; align-items: center; gap: 0.35rem;
            color: #64748b; font-size: 0.8rem; text-decoration: none;
            margin-bottom: 1.5rem; transition: color 0.2s;
        }
        .back-link:hover { color: #10b981; }
    </style>
</head>
<body>
    <div class="auth-bg"></div>
    <div class="auth-container">

        {{-- Back link --}}
        <a href="{{ url('/') }}" class="back-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke beranda
        </a>

        {{-- Logo --}}
        <div class="auth-logo">
            <div class="auth-logo-icon">🕌</div>
            <div class="auth-logo-name">{{ $appSettings['name'] ?? config('app.name') }}</div>
            <div class="auth-logo-sub">Sistem Manajemen Tahfidz Digital</div>
        </div>

        {{-- Card --}}
        <div class="auth-card">
            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk untuk melanjutkan ke dashboard</p>

            {{ $slot }}
        </div>
    </div>
</body>
</html>
