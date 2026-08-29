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
        $currentRoute = request()->route()->getName();
        $desaProfile = \App\Models\DesaProfile::current();
        $currentUser = Auth::user();
    @endphp

    <div class="min-h-screen flex">
        <!-- 1. LEFT MINI SIDEBAR (SLIM ICON NAVIGATION) -->
        <aside class="w-20 bg-white border-r border-[#e1ede8] flex flex-col items-center py-6 gap-6 shrink-0 z-30 sticky top-0 h-screen">
            <!-- Village Logo Brand Icon -->
            <a href="{{ route('dashboard') }}" title="{{ $desaProfile->nama_desa }}" 
               class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0c3837] to-[#114443] text-[#d4ed31] flex items-center justify-center shadow-md hover:scale-105 transition-all">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </a>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-4 items-center flex-1 w-full px-2 mt-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   title="Dashboard Produktivitas"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'dashboard') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'dashboard'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>

                @can('user.view')
                <!-- User Management & RBAC -->
                <a href="{{ route('admin.users.index') }}" 
                   title="Manajemen Pengguna & Staf"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'admin.users'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan

                @can('kependudukan.view')
                <!-- Buku Induk Kependudukan -->
                <a href="{{ route('kependudukan.index') }}" 
                   title="Buku Induk Kependudukan"
                   id="nav-kependudukan"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'kependudukan.') && !str_contains($currentRoute, 'mutasi') && !str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'kependudukan.') && !str_contains($currentRoute, 'mutasi') && !str_contains($currentRoute, 'duplicates'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>

                <!-- Register Mutasi Penduduk -->
                <a href="{{ route('kependudukan.mutasi.index') }}" 
                   title="Buku Register Mutasi Penduduk"
                   id="nav-mutasi"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_contains($currentRoute, 'mutasi') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    @if(str_contains($currentRoute, 'mutasi'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>

                <!-- Pemindai Duplikasi NIK -->
                <a href="{{ route('kependudukan.duplicates') }}" 
                   title="Pemindai Duplikasi NIK"
                   id="nav-duplicates"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_contains($currentRoute, 'duplicates') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    @if(str_contains($currentRoute, 'duplicates'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan

                @can('desa.view')
                <!-- Profil & Identitas Desa -->
                <a href="{{ route('admin.desa.index') }}" 
                   title="Profil & Identitas Desa"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.desa') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'admin.desa'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan

                @can('audit.view')
                <!-- Audit Trail (Activity Logs) -->
                <a href="{{ route('admin.audit.index') }}" 
                   title="Audit Trail & Riwayat Aktivitas"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.audit') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'admin.audit'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan

                @can('backup.manage')
                <!-- Database Backup -->
                <a href="{{ route('admin.backup.index') }}" 
                   title="Cadangan Database"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'admin.backup') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'admin.backup'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan
                @can('administrasi.view')
                <!-- Administrasi Umum (8 Buku Register) -->
                <a href="{{ route('administrasi.index') }}" 
                   title="Buku Register Administrasi Umum"
                   id="nav-administrasi"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'administrasi.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'administrasi.'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan

                @can('keuangan.view')
                <!-- Keuangan Desa (APBDes & Kas) -->
                <a href="{{ route('keuangan.index') }}" 
                   title="Keuangan Desa (APBDes & Kas)"
                   id="nav-keuangan"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'keuangan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'keuangan.'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan

                @can('pembangunan.view')
                <!-- Pembangunan Desa (RKP & Proyek Fisik) -->
                <a href="{{ route('pembangunan.index') }}" 
                   title="Pembangunan & KPM Desa"
                   id="nav-pembangunan"
                   class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-200 relative group {{ str_starts_with($currentRoute, 'pembangunan.') ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    @if(str_starts_with($currentRoute, 'pembangunan.'))
                        <span class="absolute -left-2 w-1.5 h-6 bg-[#d4ed31] rounded-r-full"></span>
                    @endif
                </a>
                @endcan
            </nav>

            <!-- Bottom Actions: Logout -->
            <div class="flex flex-col gap-3 items-center">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" title="Keluar dari Sistem" 
                            class="w-12 h-12 rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- 2. CENTER MAIN WORKSPACE (FEED & PAGES) -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto space-y-6 max-w-full">
            {{ $slot }}
        </main>

        <!-- 3. RIGHT INTELLIGENCE & PROFILE PANEL -->
        <aside class="w-80 lg:w-88 bg-gradient-to-b from-[#114443] to-[#0c3837] text-white p-6 flex flex-col justify-between shrink-0 sticky top-0 h-screen hidden xl:flex border-l border-[#1b5e5c]/40 overflow-y-auto">
            <div class="space-y-6">
                <!-- User Profile Card -->
                <x-ui.profile-widget :user="$currentUser" :desa="$desaProfile" class="pt-2" />

                <!-- Working Hours Widget -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 shadow-sm">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs text-[#8bc3b8] font-semibold">Jam Operasional Pelayanan</span>
                        <span class="w-2 h-2 rounded-full bg-[#d4ed31] animate-ping"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-white/15 rounded-xl py-2 px-2 border border-white/5">
                            <span class="text-[10px] text-[#8bc3b8] block uppercase font-bold tracking-wider">Mulai</span>
                            <span class="text-sm font-extrabold text-white">09:00 WIB</span>
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

    <!-- Toast Notifications -->
    <x-toast />
</body>
</html>
