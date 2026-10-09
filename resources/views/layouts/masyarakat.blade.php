<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Layanan Mandiri' }} - DIGIDES Warga</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased bg-[#f7faf9] text-[#0f172a] selection:bg-[#10b981] selection:text-white flex flex-col min-h-screen">
    @php
        $desa = $desa ?? \App\Models\DesaProfile::current();
        $currentUser = Auth::user();
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        $profile = $currentUser?->citizenProfile;
    @endphp

    <!-- ========================================================================= -->
    <!-- CITIZEN TOP HEADER                                                        -->
    <!-- ========================================================================= -->
    <header class="bg-white border-b border-[#e1ede8] sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('masyarakat.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#0c3837] to-[#114443] text-[#d4ed31] flex items-center justify-center shadow-sm p-1.5 transition-transform group-hover:scale-105">
                            @if(!empty($desa->logo_url))
                                <img src="{{ $desa->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <svg class="w-6 h-6 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <span class="block text-base font-black tracking-tight text-[#0c3837] leading-tight">
                                Portal Layanan Warga
                            </span>
                            <span class="block text-[11px] font-semibold text-[#10b981]">
                                {{ $desa->nama_desa }}
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Center Navigation Pills -->
                <nav class="hidden md:flex items-center gap-1 bg-[#f7faf9] p-1.5 rounded-full border border-[#e1ede8]">
                    <a href="{{ route('masyarakat.dashboard') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all {{ $currentRoute === 'masyarakat.dashboard' ? 'bg-[#0c3837] text-white shadow-xs' : 'text-[#64748b] hover:text-[#0c3837]' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('public.layanan') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all text-[#64748b] hover:text-[#0c3837]">
                        Katalog Surat
                    </a>
                    <a href="{{ route('masyarakat.profil') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all {{ $currentRoute === 'masyarakat.profil' ? 'bg-[#0c3837] text-white shadow-xs' : 'text-[#64748b] hover:text-[#0c3837]' }}">
                        Profil Saya
                    </a>
                    <a href="{{ route('public.home') }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold text-[#94a3b8] hover:text-[#0c3837]" target="_blank">
                        Web Publik &nearr;
                    </a>
                </nav>

                <!-- Right User Account & Logout -->
                <div class="flex items-center gap-3" x-data="{ userMenuOpen: false }">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center gap-2.5 p-1.5 pr-3 rounded-full hover:bg-[#f7faf9] border border-transparent hover:border-[#e1ede8] transition-all cursor-pointer">
                            <img src="{{ $currentUser->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-[#10b981]">
                            <div class="text-left hidden sm:block">
                                <span class="block text-xs font-bold text-[#0c3837] leading-tight">{{ $currentUser->name }}</span>
                                <span class="block text-[10px] text-[#64748b] font-medium">NIK: {{ $profile->nik ?? '-' }}</span>
                            </div>
                            <svg class="w-4 h-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" 
                             @click.away="userMenuOpen = false" 
                             x-cloak
                             x-transition
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-[#e1ede8] py-2 z-50">
                            
                            <div class="px-4 py-2 border-b border-[#e1ede8]">
                                <p class="text-xs font-bold text-[#0c3837]">{{ $currentUser->name }}</p>
                                <p class="text-[10px] text-[#64748b] truncate">{{ $currentUser->email }}</p>
                                @if($profile?->isVerified())
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-[#e2f0ed] text-[#0c3837] text-[10px] font-bold">
                                        &check; Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold">
                                        &bull; Menunggu Validasi
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('masyarakat.profil') }}" class="block px-4 py-2 text-xs font-semibold text-[#0c3837] hover:bg-[#f7faf9]">
                                Pengaturan Profil
                            </a>

                            <div class="border-t border-[#e1ede8] mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Keluar Akun</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- FLASH MESSAGES                                                            -->
    <!-- ========================================================================= -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="p-4 rounded-2xl bg-[#e2f0ed] border border-[#34d399] text-[#0c3837] text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MAIN CONTENT                                                              -->
    <!-- ========================================================================= -->
    <main class="flex-1 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-[#e1ede8] py-6 text-center text-xs text-[#64748b]">
        <p>&copy; {{ date('Y') }} {{ $desa->nama_desa }}. Portal Layanan Mandiri Masyarakat.</p>
    </footer>

</body>
</html>
