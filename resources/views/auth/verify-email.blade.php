<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - Aplikasi Manajemen Pelanggaran dan Pelaporan Pegawai</title>

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
<body class="font-sans antialiased bg-bjm-dark min-h-screen flex items-center justify-center p-4 py-12 relative overflow-x-hidden overflow-y-auto selection:bg-bjm-gold selection:text-white">
    
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] bg-bjm-gold/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden relative z-10 border border-white/20 transform transition-all">
        
        <div class="h-2 w-full bg-gradient-to-r from-yellow-400 via-bjm-gold to-amber-600"></div>

        <div class="px-8 pt-10 pb-6 text-center">
            <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Banjarmasin" class="w-20 h-auto mx-auto drop-shadow-md mb-5">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-1">Verifikasi Email Anda</h2>
            <p class="text-xs text-slate-500 font-medium leading-relaxed px-4">
                Satu langkah lagi untuk mengaktifkan akun pelapor Anda di sistem kami.
            </p>
        </div>

        <div class="px-8 pb-10">
            
            <div class="mb-6 text-sm text-slate-600 leading-relaxed text-center bg-slate-50 p-4 rounded-xl border border-slate-100">
                Terima kasih telah mendaftar! Sebelum memulai pengaduan, mohon verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke kotak masuk Anda.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 bg-emerald-50 text-emerald-600 p-3 rounded-xl text-sm font-bold border border-emerald-200 text-center flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tautan verifikasi baru telah dikirim!
                </div>
            @endif

            <div class="flex flex-col gap-4 mt-2">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="group w-full flex justify-center items-center gap-2 bg-bjm-dark hover:bg-slate-800 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-slate-800/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center text-sm font-bold text-slate-500 hover:text-red-600 transition-colors py-2">
                        Batal dan Keluar
                    </button>
                </form>
            </div>

        </div>
    </div>
</body>
</html>