<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'DIGIDES') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-[#f7faf9] text-[#0f172a] selection:bg-[#d4ed31] selection:text-[#0c3837]">
    @php
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        $desaProfile = \App\Models\DesaProfile::current();
        $currentUser = Auth::user();
    @endphp

    <div x-data="{ 
            sidebarExpanded: localStorage.getItem('digides_sidebar_expanded') === 'true',
            mobileSidebarOpen: false,
            mobileProfileOpen: false,
            toggleSidebar() {
                this.sidebarExpanded = !this.sidebarExpanded;
                localStorage.setItem('digides_sidebar_expanded', this.sidebarExpanded);
            }
         }" 
         class="min-h-screen flex flex-col lg:flex-row bg-[#f7faf9]">

        <!-- ================================================================= -->
        <!-- A. MOBILE TOP NAVIGATION HEADER (Visible only on < lg screens)   -->
        <!-- ================================================================= -->
        <header class="lg:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-[#e1ede8] px-3.5 py-2.5 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5 min-w-0">
                <!-- Mobile Hamburger Toggle Button -->
                <button @click="mobileSidebarOpen = true" 
                        type="button" 
                        class="w-10 h-10 rounded-2xl bg-[#f7faf9] hover:bg-[#e2f0ed] text-[#114443] flex items-center justify-center border border-[#e1ede8] shadow-xs active:scale-95 transition-all cursor-pointer"
                        aria-label="Buka Menu Navigasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Village Brand Logo & Name -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0 group">
                    <div class="w-9 h-9 rounded-xl {{ !empty($desaProfile->logo_url) ? 'bg-white border border-[#e1ede8] shadow-xs' : 'bg-gradient-to-br from-[#0c3837] to-[#114443] text-[#d4ed31] border border-[#1b5e5c]/30' }} flex items-center justify-center shrink-0 p-1">
                        @if(!empty($desaProfile->logo_url))
                            <img src="{{ $desaProfile->logo_url }}" alt="Logo {{ $desaProfile->nama_desa }}" class="w-full h-full object-contain">
                        @else
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="font-extrabold text-[#0c3837] text-xs tracking-tight truncate leading-tight">
                            {{ $desaProfile->nama_desa ? 'Desa ' . $desaProfile->nama_desa : 'DIGIDES' }}
                        </span>
                        <span class="text-[9px] font-semibold text-[#10b981] uppercase tracking-wider truncate leading-tight">
                            {{ $desaProfile->kecamatan ? 'Kec. ' . $desaProfile->kecamatan : 'Sistem Informasi Desa' }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Right Controls: Profile / Hours Trigger -->
            <div class="flex items-center gap-2 shrink-0">
                <button @click="mobileProfileOpen = true" 
                        type="button"
                        class="px-2.5 py-1.5 rounded-full bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] border border-[#10b981]/30 flex items-center gap-1.5 transition-all text-[11px] font-bold cursor-pointer"
                        title="Informasi Desa & Jam Pelayanan">
                    <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                    <span class="hidden sm:inline">Info Desa</span>
                    <svg class="w-3.5 h-3.5 text-[#114443]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>

                <!-- User Avatar Quick Trigger -->
                <button @click="mobileProfileOpen = true" 
                        type="button"
                        class="w-9 h-9 rounded-full border-2 border-[#10b981] p-0.5 shadow-xs overflow-hidden cursor-pointer"
                        title="{{ $currentUser?->name }}">
                    <img src="{{ $currentUser?->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=114443&color=d4ed31' }}" 
                         class="w-full h-full rounded-full object-cover" 
                         alt="{{ $currentUser?->name }}">
                </button>
            </div>
        </header>

        <!-- ================================================================= -->
        <!-- B. MOBILE SLIDE-OUT NAVIGATION DRAWER (OFF-CANVAS)                 -->
        <!-- ================================================================= -->
        <div x-show="mobileSidebarOpen" 
             x-cloak
             class="fixed inset-0 z-50 lg:hidden flex" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop Overlay -->
            <div x-show="mobileSidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-[#082424]/60 backdrop-blur-xs"></div>

            <!-- Drawer Container -->
            <div x-show="mobileSidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-[85vw] w-80 bg-white shadow-2xl z-50 h-full">
                
                <!-- Drawer Header -->
                <div class="px-5 py-4 border-b border-[#e1ede8] bg-[#f7faf9] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl {{ !empty($desaProfile->logo_url) ? 'bg-white border border-[#e1ede8] shadow-xs' : 'bg-gradient-to-br from-[#0c3837] to-[#114443] text-[#d4ed31] border border-[#1b5e5c]/30' }} flex items-center justify-center shrink-0 p-1">
                            @if(!empty($desaProfile->logo_url))
                                <img src="{{ $desaProfile->logo_url }}" alt="Logo {{ $desaProfile->nama_desa }}" class="w-full h-full object-contain">
                            @else
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <span class="font-extrabold text-[#0c3837] text-sm block leading-tight">
                                {{ $desaProfile->nama_desa ? 'Desa ' . $desaProfile->nama_desa : 'DIGIDES' }}
                            </span>
                            <span class="text-[10px] font-semibold text-[#10b981] uppercase tracking-wider block">
                                {{ $desaProfile->kecamatan ? 'Kec. ' . $desaProfile->kecamatan : 'Sistem Desa' }}
                            </span>
                        </div>
                    </div>

                    <!-- Close Button -->
                    <button @click="mobileSidebarOpen = false" 
                            type="button" 
                            class="w-9 h-9 rounded-xl bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center border border-[#e1ede8] shadow-2xs transition-colors cursor-pointer"
                            aria-label="Tutup Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Navigation Links -->
                <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden">
                    <!-- 1. Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'dashboard') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    @can('administrasi.view')
                    <!-- 2. Administrasi Umum -->
                    <a href="{{ route('administrasi.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'administrasi.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Administrasi Umum</span>
                    </a>
                    @endcan

                    @can('persuratan.view')
                    <!-- Antrean Persuratan & Walk-in -->
                    <a href="{{ route('persuratan.antrean.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'persuratan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Antrean Persuratan</span>
                    </a>
                    @endcan

                    @can('kependudukan.view')
                    <!-- 3. Kependudukan -->
                    <a href="{{ route('kependudukan.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'kependudukan.') && !str_contains($currentRoute, 'mutasi') && !str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kependudukan</span>
                    </a>

                    <!-- 4. Mutasi Penduduk -->
                    <a href="{{ route('kependudukan.mutasi.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_contains($currentRoute, 'mutasi') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Mutasi Penduduk</span>
                    </a>
                    @endcan

                    @can('keuangan.view')
                    <!-- 5. Keuangan -->
                    <a href="{{ route('keuangan.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'keuangan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Keuangan APBDes</span>
                    </a>
                    @endcan

                    @can('pembangunan.view')
                    <!-- 6. Pembangunan -->
                    <a href="{{ route('pembangunan.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'pembangunan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Pembangunan</span>
                    </a>
                    @endcan

                    @can('kependudukan.view')
                    <!-- 7. Duplikasi NIK -->
                    <a href="{{ route('kependudukan.duplicates') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Duplikasi NIK</span>
                    </a>
                    @endcan

                    @can('desa.view')
                    <!-- 8. Profil Desa -->
                    <a href="{{ route('admin.desa.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'admin.desa') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Profil Desa</span>
                    </a>
                    @endcan

                    @can('audit.view')
                    <!-- 9. Audit Trail -->
                    <a href="{{ route('admin.audit.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'admin.audit') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Audit Trail</span>
                    </a>
                    @endcan

                    @can('user.view')
                    <!-- 10. Manajemen Staff -->
                    <a href="{{ route('admin.users.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Manajemen Staff</span>
                    </a>
                    @endcan

                    @can('backup.manage')
                    <!-- 11. Backup Data -->
                    <a href="{{ route('admin.backup.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all text-xs font-semibold {{ str_starts_with($currentRoute, 'admin.backup') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        <span>Backup Data</span>
                    </a>
                    @endcan
                </nav>

                <!-- Drawer Footer: User Profile & Logout -->
                <div class="p-4 border-t border-[#e1ede8] bg-[#f7faf9] space-y-3 mt-auto">
                    <div class="flex items-center gap-3">
                        <img src="{{ $currentUser?->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=114443&color=d4ed31' }}" 
                             class="w-10 h-10 rounded-full object-cover border border-[#e1ede8] shadow-xs" 
                             alt="{{ $currentUser?->name }}">
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-[#0c3837] block truncate">{{ $currentUser?->name }}</span>
                            <span class="text-[10px] text-[#64748b] block truncate">{{ $currentUser?->role_name ?? 'Staf' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs flex items-center justify-center gap-2 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- C. MOBILE RIGHT INTELLIGENCE SLIDE-OVER DRAWER                    -->
        <!-- ================================================================= -->
        <div x-show="mobileProfileOpen" 
             x-cloak
             class="fixed inset-0 z-50 lg:hidden flex justify-end" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop Overlay -->
            <div x-show="mobileProfileOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileProfileOpen = false"
                 class="fixed inset-0 bg-[#082424]/60 backdrop-blur-xs"></div>

            <!-- Slide-over Container -->
            <div x-show="mobileProfileOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="relative max-w-[90vw] w-84 bg-gradient-to-b from-[#114443] to-[#0c3837] text-white shadow-2xl z-50 h-full flex flex-col p-5 overflow-y-auto space-y-5">
                
                <!-- Close Button -->
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <span class="text-xs font-bold text-[#d4ed31] uppercase tracking-wider">Profil & Status Desa</span>
                    <button @click="mobileProfileOpen = false" 
                            type="button" 
                            class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer"
                            aria-label="Tutup Panel Info">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- User Profile Card -->
                <x-ui.profile-widget :user="$currentUser" :desa="$desaProfile" class="pt-1" />

                <!-- Operational Hours -->
                <div x-data="{
                    isOpen: false,
                    checkOperationalHours() {
                        const now = new Date();
                        const hours = now.getHours();
                        const minutes = now.getMinutes();
                        const currentMinutes = (hours * 60) + minutes;
                        const startMinutes = 7 * 60; // 07:00 WIB
                        const endMinutes = 17 * 60;  // 17:00 WIB
                        this.isOpen = currentMinutes >= startMinutes && currentMinutes < endMinutes;
                    },
                    init() {
                        this.checkOperationalHours();
                        setInterval(() => this.checkOperationalHours(), 30000);
                    }
                }" class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 shadow-sm">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#8bc3b8] font-semibold">Jam Operasional Pelayanan</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider transition-colors"
                                  :class="isOpen ? 'bg-[#10b981]/20 text-[#a7f3d0] border border-[#10b981]/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'"
                                  x-text="isOpen ? 'Buka' : 'Tutup'"></span>
                        </div>
                        <div class="relative flex items-center justify-center w-3 h-3">
                            <span class="w-2.5 h-2.5 rounded-full transition-colors duration-300"
                                  :class="isOpen ? 'bg-[#10b981]' : 'bg-rose-500'"></span>
                            <span class="absolute w-2.5 h-2.5 rounded-full animate-ping opacity-75 transition-colors duration-300"
                                  :class="isOpen ? 'bg-[#10b981]' : 'bg-rose-500'"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-white/15 rounded-xl py-2 px-2 border border-white/5">
                            <span class="text-[10px] text-[#8bc3b8] block uppercase font-bold tracking-wider">Mulai</span>
                            <span class="text-sm font-extrabold text-white">07:00 WIB</span>
                        </div>
                        <div class="bg-white/15 rounded-xl py-2 px-2 border border-white/5">
                            <span class="text-[10px] text-[#8bc3b8] block uppercase font-bold tracking-wider">Selesai</span>
                            <span class="text-sm font-extrabold text-white">17:00 WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Region Info -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 space-y-2">
                    <span class="text-xs text-[#8bc3b8] font-semibold block">Informasi Wilayah</span>
                    <div class="text-xs space-y-1 text-white/90">
                        <div class="flex justify-between">
                            <span class="text-white/60">Kecamatan:</span>
                            <span class="font-medium">{{ $desaProfile->kecamatan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/60">Kabupaten:</span>
                            <span class="font-medium">{{ $desaProfile->kabupaten }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/60">Kode Wilayah:</span>
                            <span class="font-medium font-mono text-[#d4ed31]">{{ $desaProfile->kode_desa ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Scenic Artwork -->
                <x-ui.scenic-card :desa="$desaProfile" class="mt-2" />
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- D. 1. DESKTOP LEFT SIDEBAR (COLLAPSIBLE, HIDDEN ON MOBILE < lg)   -->
        <!-- ================================================================= -->
        <aside :class="sidebarExpanded ? 'w-72' : 'w-24'" 
               class="bg-white border-r border-[#e1ede8] hidden lg:flex flex-col h-screen sticky top-0 shrink-0 z-30 overflow-hidden select-none transition-all duration-300 shadow-xs">
            
            <!-- Village Logo Brand Header (Permanently Pinned at Top) -->
            <div :class="sidebarExpanded ? 'px-4 py-4 border-b border-[#e1ede8]' : 'px-2 py-3 border-b border-[#e1ede8]'" 
                 class="shrink-0 bg-white transition-all duration-300 w-full">
                <div class="flex items-center w-full" :class="sidebarExpanded ? 'justify-between gap-3' : 'flex-col justify-center gap-2.5'">
                    <a href="{{ route('dashboard') }}" 
                       title="{{ $desaProfile->nama_desa ? 'Desa ' . $desaProfile->nama_desa . ' - Kec. ' . $desaProfile->kecamatan : 'DIGIDES - Sistem Informasi Desa' }}"
                       class="flex items-center gap-3 overflow-hidden group min-w-0"
                       :class="sidebarExpanded ? 'flex-1' : 'justify-center'">
                        <!-- Village Logo Brand Icon -->
                        <div class="w-11 h-11 rounded-2xl {{ !empty($desaProfile->logo_url) ? 'bg-white border border-[#e1ede8] shadow-xs' : 'bg-gradient-to-br from-[#0c3837] to-[#114443] text-[#d4ed31] border border-[#1b5e5c]/30 shadow-md' }} flex items-center justify-center group-hover:scale-105 transition-all shrink-0 p-1 overflow-hidden">
                            @if(!empty($desaProfile->logo_url))
                                <img src="{{ $desaProfile->logo_url }}" alt="Logo {{ $desaProfile->nama_desa }}" class="w-full h-full object-contain">
                            @else
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            @endif
                        </div>

                        <!-- Keterangan Judul & Sub Judul Logo -->
                        <div x-show="sidebarExpanded" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 -translate-x-2" 
                             x-transition:enter-end="opacity-100 translate-x-0"
                             class="flex flex-col min-w-0 flex-1">
                            <span class="font-extrabold text-[#0c3837] text-sm tracking-tight truncate leading-tight">
                                {{ $desaProfile->nama_desa ? 'Desa ' . $desaProfile->nama_desa : 'DIGIDES' }}
                            </span>
                            <span class="text-[10px] font-semibold text-[#10b981] uppercase tracking-wider truncate leading-tight mt-0.5">
                                {{ $desaProfile->kecamatan ? 'Kec. ' . $desaProfile->kecamatan : 'Sistem Informasi Desa' }}
                            </span>
                        </div>
                    </a>

                    <!-- Toggle Button to Hide / Show Subtitle & Sidebar Text -->
                    <button @click="toggleSidebar()" 
                            type="button"
                            :title="sidebarExpanded ? 'Sembunyikan Keterangan (Mode Ringkas)' : 'Tampilkan Keterangan & Sub Judul'"
                            class="shrink-0 w-8 h-8 rounded-xl bg-[#f7faf9] hover:bg-[#e2f0ed] text-[#114443] flex items-center justify-center transition-all border border-[#e1ede8] cursor-pointer shadow-xs hover:scale-105">
                        <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation Links (Scrollable middle container) -->
            <nav class="flex flex-col gap-1.5 flex-1 min-h-0 w-full px-3 py-3 overflow-y-auto overflow-x-hidden">
                <!-- 1. Dashboard Produktivitas -->
                <a href="{{ route('dashboard') }}" 
                   title="Dashboard Produktivitas"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'dashboard') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'dashboard') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Dashboard
                    </span>
                </a>

                @can('administrasi.view')
                <!-- 2. Administrasi Umum -->
                <a href="{{ route('administrasi.index') }}" 
                   title="Administrasi Umum"
                   id="nav-administrasi"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'administrasi.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'administrasi.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Administrasi
                    </span>
                </a>
                @endcan

                @can('persuratan.view')
                <!-- Persuratan & Antrean Online -->
                <a href="{{ route('persuratan.antrean.index') }}" 
                   title="Antrean Persuratan Online"
                   id="nav-persuratan"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'persuratan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'persuratan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Persuratan
                    </span>
                </a>
                @endcan

                @can('kependudukan.view')
                <!-- 3. Kependudukan (Buku Induk) -->
                <a href="{{ route('kependudukan.index') }}" 
                   title="Kependudukan"
                   id="nav-kependudukan"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'kependudukan.') && !str_contains($currentRoute, 'mutasi') && !str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'kependudukan.') && !str_contains($currentRoute, 'mutasi') && !str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Kependudukan
                    </span>
                </a>

                <!-- 4. Mutasi Penduduk -->
                <a href="{{ route('kependudukan.mutasi.index') }}" 
                   title="Mutasi Penduduk"
                   id="nav-mutasi"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_contains($currentRoute, 'mutasi') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_contains($currentRoute, 'mutasi') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Mutasi Penduduk
                    </span>
                </a>
                @endcan

                @can('keuangan.view')
                <!-- 5. Keuangan -->
                <a href="{{ route('keuangan.index') }}" 
                   title="Keuangan Desa (APBDes & Kas)"
                   id="nav-keuangan"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'keuangan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'keuangan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Keuangan APBDes
                    </span>
                </a>
                @endcan

                @can('pembangunan.view')
                <!-- 6. Pembangunan -->
                <a href="{{ route('pembangunan.index') }}" 
                   title="Pembangunan & KPM Desa"
                   id="nav-pembangunan"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'pembangunan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'pembangunan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Pembangunan
                    </span>
                </a>
                @endcan

                @can('kependudukan.view')
                <!-- 7. Duplikasi NIK -->
                <a href="{{ route('kependudukan.duplicates') }}" 
                   title="Pemindai Duplikasi NIK"
                   id="nav-nik-scanner"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Duplikasi NIK
                    </span>
                </a>
                @endcan

                @can('desa.view')
                <!-- 8. Profil dan Identitas Desa -->
                <a href="{{ route('admin.desa.index') }}" 
                   title="Profil & Identitas Desa"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.desa') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.desa') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Profil Desa
                    </span>
                </a>
                @endcan

                @can('audit.view')
                <!-- 10. Riwayat Aktivitas (Audit Trail) -->
                <a href="{{ route('admin.audit.index') }}" 
                   title="Riwayat Aktivitas (Audit Trail)"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.audit') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.audit') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Audit Trail
                    </span>
                </a>
                @endcan

                @can('user.view')
                <!-- 11. Management Staff / Pengguna -->
                <a href="{{ route('admin.users.index') }}" 
                   title="Management Staff & Pengguna"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Manajemen Staff
                    </span>
                </a>
                @endcan

                @can('backup.manage')
                <!-- 12. Cadangan Database -->
                <a href="{{ route('admin.backup.index') }}" 
                   title="Cadangan Database"
                   :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl flex items-center gap-3 transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.backup') ? 'bg-[#114443] text-[#d4ed31] shadow-sm font-bold' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}' : 'w-12 h-12 mx-auto rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.backup') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                    </svg>
                    <span x-show="sidebarExpanded" 
                          x-transition:enter="transition ease-out duration-150" 
                          x-transition:enter-start="opacity-0 -translate-x-2" 
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="text-xs truncate">
                        Backup Data
                    </span>
                </a>
                @endcan
            </nav>

            <!-- Bottom Actions: Logout (Permanently Pinned at Bottom) -->
            <div class="shrink-0 px-3 py-3 border-t border-[#e1ede8] bg-white mt-auto">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" 
                            title="Keluar dari Sistem" 
                            :class="sidebarExpanded ? 'w-full px-3.5 py-2.5 rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-600 flex items-center gap-3 transition-colors cursor-pointer' : 'w-12 h-12 mx-auto rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer'">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span x-show="sidebarExpanded" 
                              x-transition:enter="transition ease-out duration-150" 
                              x-transition:enter-start="opacity-0 -translate-x-2" 
                              x-transition:enter-end="opacity-100 translate-x-0"
                              class="text-xs font-semibold text-rose-600 truncate">
                            Keluar Sistem
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ================================================================= -->
        <!-- E. 2. CENTER MAIN WORKSPACE (FEED & PAGES)                        -->
        <!-- ================================================================= -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            <main class="flex-1 p-3.5 sm:p-5 md:p-8 overflow-y-auto overflow-x-hidden space-y-5 sm:space-y-6 min-w-0 w-full max-w-full">
                {!! $slot ?? $__env->yieldContent('content') !!}
            </main>
        </div>

        <!-- ================================================================= -->
        <!-- F. 3. DESKTOP RIGHT INTELLIGENCE PANEL (HIDDEN ON < xl)           -->
        <!-- ================================================================= -->
        <aside class="w-80 lg:w-88 bg-gradient-to-b from-[#114443] to-[#0c3837] text-white p-6 flex-col justify-between shrink-0 sticky top-0 h-screen hidden xl:flex border-l border-[#1b5e5c]/40 overflow-y-auto">
            <div class="space-y-6">
                <!-- User Profile Card -->
                <x-ui.profile-widget :user="$currentUser" :desa="$desaProfile" class="pt-2" />

                <!-- Working Hours Widget with Dynamic Real-time Status -->
                <div x-data="{
                    isOpen: false,
                    checkOperationalHours() {
                        const now = new Date();
                        const hours = now.getHours();
                        const minutes = now.getMinutes();
                        const currentMinutes = (hours * 60) + minutes;
                        const startMinutes = 7 * 60; // 07:00 WIB
                        const endMinutes = 17 * 60;  // 17:00 WIB
                        this.isOpen = currentMinutes >= startMinutes && currentMinutes < endMinutes;
                    },
                    init() {
                        this.checkOperationalHours();
                        setInterval(() => this.checkOperationalHours(), 30000);
                    }
                }" class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 shadow-sm">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#8bc3b8] font-semibold">Jam Operasional Pelayanan</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider transition-colors"
                                  :class="isOpen ? 'bg-[#10b981]/20 text-[#a7f3d0] border border-[#10b981]/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'"
                                  x-text="isOpen ? 'Buka' : 'Tutup'"></span>
                        </div>
                        <div class="relative flex items-center justify-center w-3 h-3" :title="isOpen ? 'Sedang Beroperasi (07:00 - 17:00 WIB)' : 'Di Luar Jam Operasional (Tutup)'">
                            <span class="w-2.5 h-2.5 rounded-full transition-colors duration-300"
                                  :class="isOpen ? 'bg-[#10b981]' : 'bg-rose-500'"></span>
                            <span class="absolute w-2.5 h-2.5 rounded-full animate-ping opacity-75 transition-colors duration-300"
                                  :class="isOpen ? 'bg-[#10b981]' : 'bg-rose-500'"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-white/15 rounded-xl py-2 px-2 border border-white/5">
                            <span class="text-[10px] text-[#8bc3b8] block uppercase font-bold tracking-wider">Mulai</span>
                            <span class="text-sm font-extrabold text-white">07:00 WIB</span>
                        </div>
                        <div class="bg-white/15 rounded-xl py-2 px-2 border border-white/5">
                            <span class="text-[10px] text-[#8bc3b8] block uppercase font-bold tracking-wider">Selesai</span>
                            <span class="text-sm font-extrabold text-white">17:00 WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Live Quick System Status -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 space-y-2">
                    <span class="text-xs text-[#8bc3b8] font-semibold block">Informasi Wilayah</span>
                    <div class="text-xs space-y-1 text-white/90">
                        <div class="flex justify-between">
                            <span class="text-white/60">Kecamatan:</span>
                            <span class="font-medium">{{ $desaProfile->kecamatan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/60">Kabupaten:</span>
                            <span class="font-medium">{{ $desaProfile->kabupaten }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/60">Kode Wilayah:</span>
                            <span class="font-medium font-mono text-[#d4ed31]">{{ $desaProfile->kode_desa ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scenic Landscape Artwork Card -->
            <x-ui.scenic-card :desa="$desaProfile" class="mt-4" />
        </aside>
    </div>

    <!-- Staff Avatar Modal -->
    <x-staff-avatar-modal />

    <!-- Toast Notifications -->
    <x-toast />

    @stack('scripts')
</body>
</html>
