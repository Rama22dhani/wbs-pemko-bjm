<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Manajemen Pelanggaran dan Pelaporan Pegawai</title>

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
<body class="font-sans antialiased bg-bjm-dark min-h-screen flex flex-col items-center justify-center py-4 sm:py-6 px-4 relative overflow-x-hidden selection:bg-bjm-gold selection:text-white">
    <a href="{{ url('/') }}" class="absolute top-4 left-4 sm:top-6 sm:left-6 flex items-center gap-2 text-slate-300 hover:text-white bg-white/10 hover:bg-white/20 border border-white/10 px-3.5 py-2 rounded-xl transition font-medium text-xs sm:text-sm z-50 backdrop-blur-sm shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>

    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-bjm-gold/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden relative z-10 my-auto">
        <div class="h-1.5 w-full bg-gradient-to-r from-yellow-400 via-bjm-gold to-amber-600"></div>
        
        <div class="bg-slate-50/50 px-6 sm:px-8 pt-5 pb-3 text-center border-b border-slate-100">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Banjarmasin" class="w-12 sm:w-14 h-auto mx-auto drop-shadow-md mb-2">
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Akun Pelapor</h2>
            <p class="text-xs text-slate-500 mt-1 font-medium">Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai<br>Pemerintah Kota Banjarmasin</p>
        </div>

        <div class="px-6 sm:px-8 py-4 sm:py-5">
            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                <div>
                    <label for="name" class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-bjm-gold focus:ring-2 focus:ring-bjm-gold/20 outline-none transition-all placeholder:text-slate-400"
                        placeholder="Masukkan nama lengkap Anda">
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label for="email" class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-bjm-gold focus:ring-2 focus:ring-bjm-gold/20 outline-none transition-all placeholder:text-slate-400"
                        placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label for="password" class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-bjm-gold focus:ring-2 focus:ring-bjm-gold/20 outline-none transition-all placeholder:text-slate-400"
                        placeholder="Minimal 8 karakter">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs sm:text-sm font-bold text-slate-700 mb-0.5">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-bjm-gold focus:ring-2 focus:ring-bjm-gold/20 outline-none transition-all placeholder:text-slate-400"
                        placeholder="Ulangi kata sandi Anda">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-bjm-dark hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-bjm-dark focus:ring-offset-2">
                        Daftarkan Akun Baru
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </button>
                </div>

                <p class="text-center text-xs text-slate-500 font-medium pt-2 border-t border-slate-100 mt-3">
                    Sudah memiliki akun pelapor? 
                    <a href="{{ route('login') }}" class="text-bjm-gold font-bold hover:text-amber-700 transition ms-1">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>