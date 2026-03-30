<x-guest-layout>
    <style>
        .auth-session-alert {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            color: #6ee7b7; border-radius: 10px;
            padding: 0.75rem 1rem; font-size: 0.85rem;
            margin-bottom: 1.25rem;
        }
        .auth-form-group { margin-bottom: 1.25rem; }
        .auth-label {
            display: block; font-size: 0.825rem; font-weight: 600;
            color: #94a3b8; margin-bottom: 0.45rem;
        }
        .auth-input {
            width: 100%; padding: 0.75rem 1rem;
            background: rgba(15,23,42,0.7);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; color: #f1f5f9;
            font-size: 0.925rem; font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .auth-input:focus {
            border-color: rgba(16,185,129,0.6);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
        }
        .auth-input::placeholder { color: #475569; }
        .auth-error { color: #f87171; font-size: 0.8rem; margin-top: 0.35rem; }
        .auth-remember {
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .auth-remember input[type="checkbox"] {
            width: 16px; height: 16px; border-radius: 4px;
            accent-color: #10b981; cursor: pointer;
        }
        .auth-remember label {
            font-size: 0.85rem; color: #64748b; cursor: pointer;
        }
        .auth-footer {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 0.75rem;
        }
        .auth-forgot {
            font-size: 0.825rem; color: #64748b; text-decoration: none;
            transition: color 0.2s;
        }
        .auth-forgot:hover { color: #10b981; }
        .auth-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; border: none; border-radius: 10px;
            font-size: 0.95rem; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: all 0.2s;
            box-shadow: 0 4px 20px rgba(16,185,129,0.35);
        }
        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(16,185,129,0.5);
        }
        .auth-btn:active { transform: translateY(0); }
    </style>

    {{-- Session status (e.g. "password reset link sent") --}}
    @if (session('status'))
        <div class="auth-session-alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="auth-form-group">
            <label for="email" class="auth-label">Email</label>
            <input
                id="email" type="email" name="email"
                value="{{ old('email') }}"
                class="auth-input"
                placeholder="nama@pesantren.com"
                required autofocus autocomplete="username"
            >
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="auth-form-group">
            <label for="password" class="auth-label">Password</label>
            <div style="position:relative;">
                <input
                    id="password" type="password" name="password"
                    class="auth-input"
                    placeholder="••••••••"
                    style="padding-right: 3rem;"
                    required autocomplete="current-password"
                >
                <button
                    type="button"
                    onclick="
                        const inp = document.getElementById('password');
                        const eyeOn = document.getElementById('eye-on');
                        const eyeOff = document.getElementById('eye-off');
                        if (inp.type === 'password') {
                            inp.type = 'text';
                            eyeOn.style.display = 'none';
                            eyeOff.style.display = 'block';
                        } else {
                            inp.type = 'password';
                            eyeOn.style.display = 'block';
                            eyeOff.style.display = 'none';
                        }
                    "
                    style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#64748b;padding:0;display:flex;align-items:center;"
                    aria-label="Toggle password visibility"
                >
                    {{-- Eye icon (password hidden) --}}
                    <svg id="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{-- Eye-off icon (password visible) --}}
                    <svg id="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="auth-remember">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Ingat saya</label>
        </div>

        {{-- Footer actions --}}
        <div class="auth-footer">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-forgot">
                    Lupa password?
                </a>
            @endif

            <button type="submit" class="auth-btn">
                Masuk
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>
