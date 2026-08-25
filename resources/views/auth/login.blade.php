<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'bjm-dark': '#0f172a',
                        'bjm-gold': '#d97706',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-bjm-dark min-h-screen flex items-center justify-center p-4 py-6 relative overflow-x-hidden overflow-y-auto selection:bg-bjm-gold selection:text-white">
    
    <a href="{{ url('/') }}" class="group absolute top-4 left-4 sm:top-6 sm:left-6 flex items-center gap-2 text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 px-3.5 py-2 rounded-xl transition-all duration-300 font-medium text-xs sm:text-sm z-50 backdrop-blur-md shadow-lg">
        <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Beranda
    </a>

    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] bg-bjm-gold/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden relative z-10 border border-white/20 transform transition-all my-auto">
        
        <div class="h-1.5 w-full bg-gradient-to-r from-yellow-400 via-bjm-gold to-amber-600"></div>

        <div class="px-6 sm:px-8 pt-6 pb-4 text-center">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Banjarmasin" class="w-14 sm:w-16 h-auto mx-auto drop-shadow-md mb-3 hover:scale-105 transition-transform duration-300">
            <h2 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight mb-1">Selamat Datang</h2>
            <p class="text-xs text-slate-500 font-medium leading-relaxed px-2">
                Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai Kota Banjarmasin
            </p>
        </div>

        <div class="px-6 sm:px-8 pb-6 pt-2">
            
            <x-auth-session-status class="mb-4 bg-emerald-50 text-emerald-600 p-2.5 rounded-xl text-xs sm:text-sm font-bold border border-emerald-200 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="email" class="block text-xs sm:text-sm font-bold text-slate-700">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-bjm-gold focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all duration-300 placeholder:text-slate-400 shadow-sm"
                            placeholder="Masukkan email Anda">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-xs sm:text-sm font-bold text-slate-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-bjm-gold hover:text-amber-700 transition-colors">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-bjm-gold focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all duration-300 placeholder:text-slate-400 shadow-sm"
                            placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <div class="flex items-center justify-between pt-0.5 pb-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <div class="relative flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="peer w-4 h-4 rounded border-slate-300 text-bjm-gold focus:ring-bjm-gold/30 transition cursor-pointer">
                        </div>
                        <span class="ms-2 text-xs sm:text-sm font-medium text-slate-500 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="group w-full flex justify-center items-center gap-2 bg-bjm-dark hover:bg-slate-800 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-slate-800/30">
                    Masuk ke Sistem
                    <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>

                @if (Route::has('register'))
                <p class="text-center text-xs sm:text-sm text-slate-500 font-medium mt-5 pt-4 border-t border-slate-100">
                    Belum memiliki akun pelapor?
                    <a href="{{ route('register') }}" class="text-bjm-gold font-bold hover:text-amber-700 hover:underline transition-all ms-1">Daftar sekarang</a>
                </p>
                @endif
            </form>
        </div>
    </div>
</body>
</html>