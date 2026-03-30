<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appSettings['name'] ?? 'Tahfidz Darul Ilmi' }} – Sistem Manajemen Tahfidz</title>
    <meta name="description" content="Sistem digital modern untuk memantau progress hafalan Al-Qur'an santri Pesantren Darul Ilmi secara real-time.">

    {{-- Prefetch login page so clicking the button is instant --}}
    <link rel="prefetch" href="{{ route('login') }}">

    {{-- Non-blocking Google Fonts (doesn't delay first paint) --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
          rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #f8fafc; min-height: 100vh; overflow-x: hidden; }

        /* ── Animated background ── */
        .bg-mesh {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(16,185,129,0.20) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 110%, rgba(59,130,246,0.15) 0%, transparent 60%),
                #0f172a;
        }

        /* ── Navbar ── */
        .navbar {
            position: fixed; top: 0; width: 100%; z-index: 50;
            padding: 1rem 2rem;
            backdrop-filter: blur(16px);
            background: rgba(15,23,42,0.75);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar-logo { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .navbar-logo-icon {
            width: 40px; height: 40px; border-radius: 10px; overflow: hidden;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .navbar-logo-text { font-size: 1.1rem; font-weight: 700; color: #f8fafc; }

        /* ── Hero ── */
        .hero {
            position: relative; z-index: 10;
            min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; padding: 7rem 1.5rem 4rem;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.35rem 1rem; border-radius: 999px;
            background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3);
            color: #6ee7b7; font-size: 0.8rem; font-weight: 600;
            margin-bottom: 1.75rem; letter-spacing: 0.05em;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 900; line-height: 1.1;
            background: linear-gradient(135deg, #f8fafc 30%, #6ee7b7 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.25rem; max-width: 820px;
        }
        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: #94a3b8; max-width: 580px; line-height: 1.75;
            margin-bottom: 2.5rem;
        }
        .btn-login {
            display: inline-flex; align-items: center; gap: 0.6rem;
            padding: 0.9rem 2.2rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; text-decoration: none;
            border-radius: 12px; font-weight: 700; font-size: 1.05rem;
            box-shadow: 0 4px 30px rgba(16,185,129,0.4);
            transition: all 0.25s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(16,185,129,0.55);
        }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 0.6rem;
            padding: 0.9rem 2rem;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            color: #cbd5e1; text-decoration: none;
            border-radius: 12px; font-weight: 600; font-size: 1rem;
            transition: all 0.25s ease;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; }

        /* ── Stats bar ── */
        .stats-bar {
            position: relative; z-index: 10;
            display: flex; flex-wrap: wrap; justify-content: center; gap: 1px;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px; overflow: hidden;
            max-width: 800px; margin: 0 auto 5rem; padding: 0;
        }
        .stat-item {
            flex: 1; min-width: 150px;
            padding: 1.5rem 2rem; text-align: center;
            background: rgba(15,23,42,0.6);
        }
        .stat-item:not(:last-child) { border-right: 1px solid rgba(255,255,255,0.06); }
        .stat-number { font-size: 2rem; font-weight: 800; color: #10b981; }
        .stat-label { font-size: 0.8rem; color: #64748b; font-weight: 500; margin-top: 0.25rem; }

        /* ── Features ── */
        .section { position: relative; z-index: 10; padding: 5rem 1.5rem; max-width: 1100px; margin: 0 auto; }
        .section-label {
            text-align: center; color: #10b981; font-size: 0.85rem;
            font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .section h2 {
            text-align: center; font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 800;
            color: #f1f5f9; margin-bottom: 1rem;
        }
        .section-desc { text-align: center; color: #64748b; max-width: 560px; margin: 0 auto 3.5rem; line-height: 1.7; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .feature-card {
            background: rgba(30,41,59,0.6); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px; padding: 1.75rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }
        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(16,185,129,0.3);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(5,150,105,0.1));
            border: 1px solid rgba(16,185,129,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1rem;
        }
        .feature-card h3 { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.5rem; }
        .feature-card p { font-size: 0.9rem; color: #64748b; line-height: 1.6; }

        /* ── CTA Section ── */
        .cta-section {
            position: relative; z-index: 10;
            padding: 5rem 1.5rem;
            text-align: center;
        }
        .cta-inner {
            max-width: 700px; margin: 0 auto;
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(5,150,105,0.05));
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 24px; padding: 3.5rem 2rem;
        }
        .cta-inner h2 { font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 800; margin-bottom: 0.75rem; }
        .cta-inner p { color: #94a3b8; margin-bottom: 2rem; }

        /* ── Footer ── */
        .footer {
            position: relative; z-index: 10;
            text-align: center; padding: 2rem;
            color: #334155; font-size: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        @media (max-width: 640px) {
            .navbar { padding: 1rem; }
            .stat-item:not(:last-child) { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.06); }
        }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>

    {{-- Navbar --}}
    <nav class="navbar">
        <a href="{{ url('/') }}" class="navbar-logo">
            <div class="navbar-logo-icon">🕌</div>
            <span class="navbar-logo-text">{{ $appSettings['name'] ?? 'Tahfidz Darul Ilmi' }}</span>
        </a>
        <a href="{{ route('login') }}" class="btn-login" style="padding: 0.6rem 1.4rem; font-size: 0.9rem;">
            Masuk
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </nav>

    {{-- Hero --}}
    <section class="hero">
        <div class="hero-badge">
            <span>✨</span>
            <span>Sistem Manajemen Tahfidz Digital</span>
        </div>
        <h1>Pantau Progress Hafalan<br>Al-Qur'an Santri Anda</h1>
        <p>Platform digital modern untuk Pesantren memantau, mengelola, dan menganalisis progres hafalan seluruh santri secara real-time dan akurat.</p>
        <div class="hero-actions">
            <a href="{{ route('login') }}" class="btn-login">
                Masuk ke Sistem
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="#fitur" class="btn-secondary">
                Lihat Fitur
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </section>

    {{-- Stats --}}
    <div style="position:relative;z-index:10;padding:0 1.5rem;">
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number">30</div>
                <div class="stat-label">Juz Dipantau</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Real-time</div>
                <div class="stat-label">Update Progress</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">PDF</div>
                <div class="stat-label">Laporan Otomatis</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Multi</div>
                <div class="stat-label">Peran Pengguna</div>
            </div>
        </div>
    </div>

    {{-- Features --}}
    <section class="section" id="fitur">
        <p class="section-label">Fitur Unggulan</p>
        <h2>Semua Yang Anda Butuhkan</h2>
        <p class="section-desc">Dirancang khusus untuk manajemen tahfidz yang efisien, transparan, dan mudah digunakan oleh seluruh pengelola pesantren.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Dashboard Analitik</h3>
                <p>Visualisasi data hafalan dalam bentuk grafik interaktif. Pantau distribusi hafalan, tren setoran, dan ranking santri secara sekilas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h3>Input Setoran</h3>
                <p>Catat setoran hafalan Sabaq, Sabqi, dan Manzil dengan mudah beserta penilaian kelancaran dari ustadz pembimbing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Data Santri & Ustadz</h3>
                <p>Kelola profil lengkap santri dan ustadz, termasuk kelas halaqah, kontak wali, dan penugasan pembimbing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📑</div>
                <h3>Laporan & Export PDF</h3>
                <p>Generate laporan bulanan lengkap dengan ranking, statistik, dan progress target 30 Juz. Export langsung ke PDF.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏆</div>
                <h3>Sistem Ranking</h3>
                <p>Peringkat otomatis berdasarkan capaian juz. Identifikasi santri terbaik dan santri yang membutuhkan perhatian khusus.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎨</div>
                <h3>Pengaturan Tampilan</h3>
                <p>Kustomisasi nama aplikasi, logo, dan warna tema sesuai identitas pesantren Anda dengan mudah.</p>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta-section">
        <div class="cta-inner">
            <h2>Siap Memulai?</h2>
            <p>Masuk ke sistem dan mulai pantau progress hafalan santri Anda hari ini.</p>
            <a href="{{ route('login') }}" class="btn-login" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                Masuk ke Sistem
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>

    <footer class="footer">
        <p>© {{ date('Y') }} {{ $appSettings['name'] ?? 'Tahfidz Darul Ilmi' }} — Sistem Manajemen Tahfidz Digital</p>
    </footer>
</body>
</html>
