<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Resmi' }} - {{ ucwords($desa->nama_desa ?? config('app.name', 'DIGIDES')) }}</title>
    <meta name="description" content="Website Resmi {{ ucwords($desa->nama_desa ?? 'Pemerintah Desa') }} - Sumber informasi terbaru tentang pemerintahan di {{ ucwords($desa->nama_desa ?? 'Desa') }}.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ mobileNavOpen: false, pengaduanModalOpen: false, visitorStatsOpen: false, scrolled: false }"
      @scroll.window="scrolled = (window.pageYOffset > 30)"
      @open-pengaduan.window="pengaduanModalOpen = true"
      class="font-sans antialiased bg-[#f7faf9] text-[#0f172a] selection:bg-[#10b981] selection:text-white flex flex-col min-h-screen">
    @php
        $desa = $desa ?? \App\Models\DesaProfile::current();
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        $isHome = request()->routeIs('public.home');
    @endphp

    <!-- ========================================================================= -->
    <!-- 1. TOP NAVBAR (Reference Exact Match: Home, Profil, Infografis, Listing, IDM, Berita, Belanja, PPID) -->
    <!-- ========================================================================= -->
    <header :class="{
                'fixed top-0 inset-x-0 z-50 transition-all duration-300': {{ $isHome ? 'true' : 'false' }},
                'sticky top-0 z-50 bg-[#0c3837] text-white border-b border-[#1b5e5c]/50 shadow-sm': {{ $isHome ? 'false' : 'true' }},
                'bg-[#0c3837]/95 backdrop-blur-md shadow-md border-b border-[#1b5e5c]/50': {{ $isHome ? 'true' : 'false' }} && scrolled,
                'bg-gradient-to-b from-black/80 via-black/40 to-transparent border-transparent': {{ $isHome ? 'true' : 'false' }} && !scrolled
            }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand / Logo -->
                <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md p-1.5 border border-white/20 overflow-hidden shrink-0 flex items-center justify-center transition-transform group-hover:scale-105 shadow-sm">
                        @if(!empty($desa->logo_url))
                            <img src="{{ $desa->logo_url }}" alt="Logo {{ $desa->nama_desa }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#10b981] to-[#0c3837] p-1 flex items-center justify-center rounded-xl border border-emerald-400/30">
                                <svg class="w-5 h-5 text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm0 4.5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5zm4 9.5H8v-1.25c0-1.5 2.67-2.25 4-2.25s4 .75 4 2.25V16z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="block text-base sm:text-lg font-black tracking-tight text-white leading-tight drop-shadow-sm group-hover:text-emerald-300 transition-colors">
                            {{ ucwords($desa->nama_desa ?? 'Desa Balam Sempurna') }}
                        </span>
                        <span class="block text-[11px] font-medium text-emerald-100/90 tracking-wide drop-shadow-xs">
                            {{ !empty($desa->kabupaten) ? 'Kabupaten ' . ucwords($desa->kabupaten) : 'Kabupaten Rokan Hilir' }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                    <a href="{{ route('public.home') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.home' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Home
                    </a>
                    <a href="{{ route('public.ajukan-surat') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ in_array($currentRoute, ['public.ajukan-surat', 'public.layanan', 'public.lacak-surat']) ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Ajukan Surat
                    </a>
                    <a href="{{ route('public.profil') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.profil' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Profil Desa
                    </a>
                    <a href="{{ route('public.infografis') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.infografis' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Infografis
                    </a>
                    <a href="{{ route('public.listing') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.listing' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Listing
                    </a>
                    <a href="{{ route('public.idm') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.idm' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        IDM
                    </a>
                    <a href="{{ route('public.berita') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ str_starts_with($currentRoute, 'public.berita') ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Berita
                    </a>
                    <a href="{{ route('public.belanja') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.belanja' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        Belanja
                    </a>
                    <a href="{{ route('public.ppid') }}" 
                       class="relative px-3.5 py-2 text-sm font-semibold tracking-wide transition-all rounded-full {{ $currentRoute === 'public.ppid' ? 'text-emerald-300 font-bold bg-white/15 border border-white/20 shadow-xs' : 'text-white/85 hover:text-white hover:bg-white/10' }}">
                        PPID
                    </a>
                </nav>

                <!-- Action Button / Portal -->
                <div class="hidden lg:flex items-center gap-2.5">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20 text-white font-bold text-xs shadow-sm transition-all hover:scale-102">
                        <span>Masuk</span>
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center px-5 py-2 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-sm transition-all hover:scale-102">
                        <span>Daftar</span>
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileNavOpen = !mobileNavOpen" 
                            type="button" 
                            class="p-2.5 rounded-2xl bg-white/15 backdrop-blur-md text-white hover:bg-white/25 focus:outline-none transition-colors border border-white/20 cursor-pointer"
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

        <!-- Mobile Drawer Menu (with max-height and scrolling to prevent overflow) -->
        <div x-show="mobileNavOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden bg-[#0c3837]/98 backdrop-blur-xl border-b border-[#1b5e5c]/60 px-5 pt-3 pb-6 space-y-1.5 shadow-2xl max-h-[calc(100vh-5rem)] overflow-y-auto">
            <a href="{{ route('public.home') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.home' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Home
            </a>
            <a href="{{ route('public.ajukan-surat') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ in_array($currentRoute, ['public.ajukan-surat', 'public.layanan', 'public.lacak-surat']) ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Ajukan Surat Online
            </a>
            <a href="{{ route('public.profil') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.profil' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Profil Desa
            </a>
            <a href="{{ route('public.infografis') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.infografis' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Infografis
            </a>
            <a href="{{ route('public.listing') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.listing' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Listing
            </a>
            <a href="{{ route('public.idm') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.idm' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                IDM
            </a>
            <a href="{{ route('public.berita') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ str_starts_with($currentRoute, 'public.berita') ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Berita
            </a>
            <a href="{{ route('public.belanja') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.belanja' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                Belanja
            </a>
            <a href="{{ route('public.ppid') }}" 
               class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ $currentRoute === 'public.ppid' ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30' : 'text-white/85 hover:bg-white/10' }}">
                PPID
            </a>
            <div class="pt-3 border-t border-white/15 flex gap-2">
                <a href="{{ route('login') }}" 
                   class="flex-1 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-white font-bold text-xs text-center">
                    Masuk
                </a>
                <a href="{{ route('register') }}" 
                   class="flex-1 py-2.5 rounded-xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs text-center">
                    Daftar
                </a>
            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 2. MAIN CONTENT AREA                                                      -->
    <!-- ========================================================================= -->
    <main class="flex-1">
        @if(session('success'))
            <div class="max-w-4xl mx-auto px-4 mt-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg font-bold">&times;</button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- ========================================================================= -->
    <!-- 3. GLOBAL PENGADUAN MODAL                                                 -->
    <!-- ========================================================================= -->
    <div x-show="pengaduanModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="pengaduanModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="pengaduanModalOpen = false" 
                 class="fixed inset-0 bg-[#082424]/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="pengaduanModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-[#e1ede8]">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#0c3837] to-[#114443] px-6 py-5 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white flex items-center justify-center shadow-xs border border-white/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold leading-tight">Pengaduan & Aspirasi Warga</h3>
                            <p class="text-xs text-[#8bc3b8]">Pemerintah {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }}</p>
                        </div>
                    </div>
                    <button @click="pengaduanModalOpen = false" type="button" class="text-white/70 hover:text-white p-1 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form action="{{ route('public.pengaduan.submit') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1">Nama Lengkap Pelapor</label>
                        <input type="text" name="nama_pelapor" required placeholder="Contoh: Budi Santoso"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#e1ede8] text-sm focus:outline-none focus:border-[#10b981] bg-[#f7faf9]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                            <input type="tel" name="kontak" required placeholder="0812xxxxxxxx"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#e1ede8] text-sm focus:outline-none focus:border-[#10b981] bg-[#f7faf9]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1">Kategori Pengaduan</label>
                            <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl border border-[#e1ede8] text-sm focus:outline-none focus:border-[#10b981] bg-[#f7faf9]">
                                <option value="Pelayanan Publik">Pelayanan Publik / Surat</option>
                                <option value="Infrastruktur & Jalan">Infrastruktur & Jalan</option>
                                <option value="Bantuan Sosial">Bantuan Sosial & Kesejahteraan</option>
                                <option value="Keamanan Lingkungan">Keamanan & Ketertiban</option>
                                <option value="Saran & Masukan">Saran & Masukan Pembangunan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1">Uraian Masalah / Aspirasi</label>
                        <textarea name="isi_laporan" rows="4" required placeholder="Jelaskan secara singkat dan jelas laporan yang ingin Anda sampaikan..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-[#e1ede8] text-sm focus:outline-none focus:border-[#10b981] bg-[#f7faf9] resize-none"></textarea>
                    </div>

                    <div class="pt-2 flex items-center justify-between gap-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $desa->telepon_desa ?? '6281234567890') }}?text=Halo%20Admin%20Desa,%20saya%20ingin%20menyampaikan%20pengaduan" 
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition-colors">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.073-1.119-.064-.265-.086-.607-.202-1.047-.393-1.859-.806-3.071-2.686-3.164-2.81-.093-.124-.757-1.009-.757-1.924 0-.916.48-1.365.651-1.549.171-.184.373-.23.498-.23.125 0 .25.002.359.007.115.006.27-.044.422.323.156.376.533 1.303.58 1.398.047.094.078.204.016.329-.063.125-.094.204-.187.313-.094.11-.197.245-.282.329-.093.094-.191.196-.082.383.109.187.484.798 1.039 1.292.715.637 1.318.834 1.505.928.188.094.297.078.406-.047.11-.125.469-.547.594-.735.125-.187.25-.156.422-.093.172.062 1.094.516 1.281.609.188.094.313.141.359.219.047.078.047.453-.097.858z"/>
                            </svg>
                            <span>Lapor via WhatsApp</span>
                        </a>

                        <button type="submit" 
                                class="px-6 py-2.5 rounded-xl bg-[#0c3837] hover:bg-[#082424] text-white font-bold text-xs shadow-md transition-all">
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 4. FOOTER (Deep Pine Rich Aesthetic)                                      -->
    <!-- ========================================================================= -->
    <footer class="bg-gradient-to-b from-[#0c3837] to-[#082424] text-white mt-20 border-t border-[#1b5e5c]/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Col 1: About Village -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center bg-white/10 p-1 border border-white/20">
                            @if(!empty($desa->logo_url))
                                <img src="{{ $desa->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <svg class="w-5 h-5 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-black tracking-tight text-white">{{ $desa->nama_desa ?? 'Desa Balam Sempurna' }}</h3>
                    </div>
                    <p class="text-sm text-[#8bc3b8] leading-relaxed">
                        Mewujudkan tata kelola pemerintahan desa yang transparan, profesional, dan melayani masyarakat berbasis teknologi informasi terpadu.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-semibold text-emerald-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Portal Resmi Desa 24/7
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-white/10 pb-2">Navigasi Utama</h4>
                    <ul class="space-y-2 text-sm text-[#8bc3b8]">
                        <li><a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Home (Beranda)</a></li>
                        <li><a href="{{ route('public.profil') }}" class="hover:text-white transition-colors">Profil & Struktur Desa</a></li>
                        <li><a href="{{ route('public.infografis') }}" class="hover:text-white transition-colors">Infografis Kependudukan & APBDes</a></li>
                        <li><a href="{{ route('public.listing') }}" class="hover:text-white transition-colors">Listing Potensi & Fasilitas</a></li>
                        <li><a href="{{ route('public.idm') }}" class="hover:text-white transition-colors">Indeks Desa Membangun (IDM)</a></li>
                        <li><a href="{{ route('public.berita') }}" class="hover:text-white transition-colors">Warta & Berita Terkini</a></li>
                        <li><a href="{{ route('public.belanja') }}" class="hover:text-white transition-colors">Lapak Belanja UMKM & BUMDes</a></li>
                        <li><a href="{{ route('public.ppid') }}" class="hover:text-white transition-colors">Keterbukaan Informasi (PPID)</a></li>
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
                            <span class="font-bold text-white">08.00 - 14.30 WIB</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-white/5">
                            <span>Sabtu - Minggu</span>
                            <span class="text-emerald-200/80 font-semibold">Pelayanan Online Mandiri 24 Jam</span>
                        </div>
                        <div class="pt-2">
                            <button @click="pengaduanModalOpen = true" 
                                    class="w-full py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                                <span>Lapor Pengaduan Warga</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Contact & Office -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-white/10 pb-2">Kontak Kantor Desa</h4>
                    <div class="space-y-3 text-sm text-[#8bc3b8]">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center shrink-0 border border-white/10 shadow-xs mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="leading-relaxed">{{ $desa->alamat_kantor ?? 'Jl. Lintas Riau-Sumut KM 24, Balai Jaya, Kab. Rokan Hilir' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center shrink-0 border border-white/10 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <span>{{ $desa->telepon_desa ?? '0761-889911' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white/10 text-emerald-300 flex items-center justify-center shrink-0 border border-white/10 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span>{{ $desa->email_desa ?? 'kontak@balamsempurna.desa.id' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Copyright -->
            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between text-xs text-[#8bc3b8] gap-4">
                <p>&copy; {{ date('Y') }} {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }}. Didukung oleh <strong>DIGIDES v3</strong> - Sistem Informasi & Pelayanan Desa Terpadu.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('public.lacak-surat') }}" class="hover:text-white transition-colors">Lacak Surat</a>
                    <a href="{{ route('public.layanan') }}" class="hover:text-white transition-colors">Katalog Layanan Surat</a>
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors">Akses Pegawai</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- 5. FLOATING QUICK ACTION BUTTON (Lapor Pengaduan Warga)                   -->
    <!-- ========================================================================= -->
    <div class="fixed bottom-6 right-6 z-40">
        <button @click="pengaduanModalOpen = true" 
                type="button" 
                class="group flex items-center gap-2.5 px-4 sm:px-5 py-3 sm:py-3.5 rounded-full bg-gradient-to-r from-emerald-600 via-teal-600 to-[#0c3837] hover:from-emerald-500 hover:to-[#114443] text-white shadow-xl shadow-emerald-950/40 hover:shadow-2xl hover:shadow-emerald-950/50 transition-all duration-300 hover:scale-105 active:scale-95 border border-white/30 backdrop-blur-md cursor-pointer"
                title="Kirim Pengaduan & Aspirasi Warga">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0 group-hover:rotate-12 transition-transform">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
            </div>
            <span class="text-xs sm:text-sm font-bold tracking-wide">Pengaduan Warga</span>
            <span class="flex h-2.5 w-2.5 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-300"></span>
            </span>
        </button>
    </div>
</body>
</html>
