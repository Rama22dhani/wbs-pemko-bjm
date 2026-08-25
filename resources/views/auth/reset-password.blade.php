<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Sandi - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Memanggil Tailwind -->
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
<body class="font-sans antialiased bg-bjm-dark min-h-screen flex items-center justify-center p-4 py-12 relative overflow-x-hidden overflow-y-auto selection:bg-bjm-gold selection:text-white">
    
    <!-- Efek Cahaya Latar Belakang -->
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] bg-bjm-gold/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden relative z-10 border border-white/20 transform transition-all">
        
        <!-- Aksen Garis Emas -->
        <div class="h-2 w-full bg-gradient-to-r from-yellow-400 via-bjm-gold to-amber-600"></div>

        <div class="px-8 pt-10 pb-6 text-center">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Banjarmasin" class="w-20 h-auto mx-auto drop-shadow-md mb-5">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-1">Buat Sandi Baru</h2>
            <p class="text-xs text-slate-500 font-medium leading-relaxed px-4">
                Silakan buat kata sandi baru untuk mengamankan kembali akun pelapor Anda.
            </p>
        </div>

        <div class="px-8 pb-10">
            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-bold text-slate-700">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                            class="w-full bg-slate-100 border border-slate-200 text-slate-500 rounded-xl pl-11 pr-4 py-3 outline-none" readonly>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-bold text-slate-700">Kata Sandi Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-11 pr-4 py-3 focus:bg-white focus:border-bjm-gold focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all duration-300 placeholder:text-slate-400"
                            placeholder="Minimal 8 karakter">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Konfirmasi Sandi Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-11 pr-4 py-3 focus:bg-white focus:border-bjm-gold focus:ring-4 focus:ring-bjm-gold/10 outline-none transition-all duration-300 placeholder:text-slate-400"
                            placeholder="Ketik ulang sandi baru">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="group w-full flex justify-center items-center gap-2 bg-bjm-dark hover:bg-slate-800 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-slate-800/30 mt-2">
                    Simpan Sandi Baru & Masuk
                    <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
    </div>
</body>
</html>