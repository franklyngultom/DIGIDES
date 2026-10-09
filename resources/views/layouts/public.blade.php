<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Resmi' }} - {{ $desa->nama_desa ?? config('app.name', 'DIGIDES') }}</title>
    <meta name="description" content="Website Resmi {{ $desa->nama_desa ?? 'Pemerintah Desa' }} - Layanan Administrasi & Informasi Kependudukan Digital Terpadu.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-[#f7faf9] text-[#0f172a] selection:bg-[#10b981] selection:text-white flex flex-col min-h-screen">
    @php
        $desa = $desa ?? \App\Models\DesaProfile::current();
        $currentRoute = request()->route() ? request()->route()->getName() : '';
    @endphp

    <!-- ========================================================================= -->
    <!-- 1. TOP NAVBAR (Sticky, Blur & Clean Aesthetic)                           -->
    <!-- ========================================================================= -->
    <header x-data="{ mobileNavOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-[#e1ede8]' : 'bg-white border-b border-[#e1ede8]/60'"
            class="sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand / Logo -->
                <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#0c3837] to-[#114443] text-[#d4ed31] flex items-center justify-center shadow-sm p-1.5 transition-transform group-hover:scale-105">
                        @if(!empty($desa->logo_url))
                            <img src="{{ $desa->logo_url }}" alt="Logo {{ $desa->nama_desa }}" class="w-full h-full object-contain">
                        @else
                            <svg class="w-6 h-6 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <span class="block text-lg font-black tracking-tight text-[#0c3837] group-hover:text-[#10b981] transition-colors leading-tight">
                            {{ $desa->nama_desa ?? 'Desa Sukamaju' }}
                        </span>
                        <span class="block text-[11px] font-semibold text-[#64748b] tracking-wider uppercase">
                            Kec. {{ $desa->kecamatan ?? 'Cikole' }}, {{ $desa->kabupaten ?? 'Sukabumi' }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1.5 lg:gap-2">
                    <a href="{{ route('public.home') }}" 
                       class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $currentRoute === 'public.home' ? 'bg-[#e2f0ed] text-[#0c3837] shadow-xs' : 'text-[#475569] hover:text-[#0c3837] hover:bg-[#f1f5f4]' }}">
                        Beranda
                    </a>
                    <a href="{{ route('public.profil') }}" 
                       class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $currentRoute === 'public.profil' ? 'bg-[#e2f0ed] text-[#0c3837] shadow-xs' : 'text-[#475569] hover:text-[#0c3837] hover:bg-[#f1f5f4]' }}">
                        Profil Desa
                    </a>
                    <a href="{{ route('public.layanan') }}" 
                       class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $currentRoute === 'public.layanan' ? 'bg-[#e2f0ed] text-[#0c3837] shadow-xs' : 'text-[#475569] hover:text-[#0c3837] hover:bg-[#f1f5f4]' }}">
                        Layanan Surat
                    </a>
                    <a href="{{ route('public.berita') }}" 
                       class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ str_starts_with($currentRoute, 'public.berita') ? 'bg-[#e2f0ed] text-[#0c3837] shadow-xs' : 'text-[#475569] hover:text-[#0c3837] hover:bg-[#f1f5f4]' }}">
                        Berita & Info
                    </a>
                    <a href="{{ route('public.kontak') }}" 
                       class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $currentRoute === 'public.kontak' ? 'bg-[#e2f0ed] text-[#0c3837] shadow-xs' : 'text-[#475569] hover:text-[#0c3837] hover:bg-[#f1f5f4]' }}">
                        Kontak
                    </a>
                </nav>

                <!-- Action Button / Login -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#114443] hover:bg-[#0c3837] text-white font-bold text-sm shadow-sm transition-all hover:scale-102">
                            <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard Desa</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-sm shadow-sm transition-all hover:scale-102">
                            <span>Masuk / Portal</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileNavOpen = !mobileNavOpen" 
                            type="button" 
                            class="p-2.5 rounded-2xl bg-[#f7faf9] text-[#114443] hover:bg-[#e2f0ed] focus:outline-none transition-colors"
                            aria-label="Toggle Navigation">
                        <svg x-show="!mobileNavOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileNavOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileNavOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-b border-[#e1ede8] px-4 pt-2 pb-6 space-y-2 shadow-lg">
            <a href="{{ route('public.home') }}" 
               class="block px-4 py-3 rounded-2xl text-sm font-semibold {{ $currentRoute === 'public.home' ? 'bg-[#e2f0ed] text-[#0c3837]' : 'text-slate-700 hover:bg-[#f7faf9]' }}">
                Beranda
            </a>
            <a href="{{ route('public.profil') }}" 
               class="block px-4 py-3 rounded-2xl text-sm font-semibold {{ $currentRoute === 'public.profil' ? 'bg-[#e2f0ed] text-[#0c3837]' : 'text-slate-700 hover:bg-[#f7faf9]' }}">
                Profil Desa
            </a>
            <a href="{{ route('public.layanan') }}" 
               class="block px-4 py-3 rounded-2xl text-sm font-semibold {{ $currentRoute === 'public.layanan' ? 'bg-[#e2f0ed] text-[#0c3837]' : 'text-slate-700 hover:bg-[#f7faf9]' }}">
                Layanan Surat
            </a>
            <a href="{{ route('public.berita') }}" 
               class="block px-4 py-3 rounded-2xl text-sm font-semibold {{ str_starts_with($currentRoute, 'public.berita') ? 'bg-[#e2f0ed] text-[#0c3837]' : 'text-slate-700 hover:bg-[#f7faf9]' }}">
                Berita & Info
            </a>
            <a href="{{ route('public.kontak') }}" 
               class="block px-4 py-3 rounded-2xl text-sm font-semibold {{ $currentRoute === 'public.kontak' ? 'bg-[#e2f0ed] text-[#0c3837]' : 'text-slate-700 hover:bg-[#f7faf9]' }}">
                Kontak
            </a>
            <div class="pt-3 border-t border-[#e1ede8]">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-[#114443] text-white font-bold text-sm">
                        Dashboard Desa
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-[#10b981] text-white font-bold text-sm shadow-sm">
                        Masuk / Portal Layanan
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 2. MAIN CONTENT AREA                                                      -->
    <!-- ========================================================================= -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- ========================================================================= -->
    <!-- 3. FOOTER (Deep Pine, Warm & Rich Aesthetics)                             -->
    <!-- ========================================================================= -->
    <footer class="bg-gradient-to-b from-[#0c3837] to-[#082424] text-white mt-20 border-t border-[#1b5e5c]/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Col 1: About Village -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 p-1 border border-white/20 flex items-center justify-center">
                            @if(!empty($desa->logo_url))
                                <img src="{{ $desa->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <svg class="w-5 h-5 text-[#d4ed31]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-black tracking-tight text-white">{{ $desa->nama_desa }}</h3>
                    </div>
                    <p class="text-sm text-[#8bc3b8] leading-relaxed">
                        Mewujudkan tata kelola pemerintahan desa yang transparan, profesional, dan melayani masyarakat berbasis teknologi informasi terpadu.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-semibold text-[#d4ed31]">
                            <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                            Portal Aktif 24/7
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-white/10 pb-2">Tautan Cepat</h4>
                    <ul class="space-y-2.5 text-sm text-[#8bc3b8]">
                        <li><a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Beranda Utama</a></li>
                        <li><a href="{{ route('public.profil') }}" class="hover:text-white transition-colors">Profil & Sejarah Desa</a></li>
                        <li><a href="{{ route('public.layanan') }}" class="hover:text-white transition-colors">Katalog Layanan Surat</a></li>
                        <li><a href="{{ route('public.berita') }}" class="hover:text-white transition-colors">Warta & Pengumuman</a></li>
                        <li><a href="{{ route('public.lacak-surat') }}" class="hover:text-white transition-colors">Cek Status Pengajuan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Operating Hours -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-white/10 pb-2">Jam Pelayanan Kantor</h4>
                    <div class="space-y-3 text-sm text-[#8bc3b8]">
                        <div class="flex justify-between items-center py-1 border-b border-white/5">
                            <span>Senin - Kamis</span>
                            <span class="font-bold text-white">08.00 - 15.30 WIB</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-white/5">
                            <span>Jumat</span>
                            <span class="font-bold text-white">08.00 - 14.00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-white/5">
                            <span>Sabtu - Minggu</span>
                            <span class="text-rose-300 font-semibold">Libur / Pelayanan Online</span>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Contact & Office -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-white/10 pb-2">Kontak Kantor Desa</h4>
                    <div class="space-y-2.5 text-sm text-[#8bc3b8]">
                        <p class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#d4ed31] shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $desa->alamat_kantor }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#d4ed31] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $desa->telepon_desa ?? '0266-221144' }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#d4ed31] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $desa->email_desa ?? 'kontak@desa.id' }}</span>
                        </p>
                    </div>
                </div>

            </div>

            <!-- Copyright -->
            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between text-xs text-[#8bc3b8] gap-4">
                <p>&copy; {{ date('Y') }} {{ $desa->nama_desa }}. Didukung oleh <strong>DIGIDES v3</strong> - Sistem Administrasi Desa Digital.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors">Akses Internal Petugas</a>
                    <a href="{{ route('public.profil') }}" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
