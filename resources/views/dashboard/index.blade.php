<x-layouts.app>
    <x-slot:title>Dashboard Produktivitas</x-slot:title>

    <!-- Top Workspace Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0c3837] tracking-tight">Working Productivity</h1>
            <p class="text-sm text-[#64748b] mt-0.5">Let's check your village service progress and daily activities</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="relative w-72 sm:w-80">
                <input type="text" placeholder="Cari layanan, surat, atau warga..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-white border border-[#e1ede8] rounded-2xl text-sm focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 shadow-xs transition-all">
                <svg class="w-4 h-4 text-[#94a3b8] absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @can('user.create')
            <x-ui.button href="{{ route('admin.users.create') }}" variant="primary" size="sm">
                <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Staf</span>
            </x-ui.button>
            @endcan
        </div>
    </div>

    @if(auth()->check() && auth()->user()->hasRole('Staff Desa'))
    <!-- Staff Profile & Quick Actions Banner -->
    <div class="bg-gradient-to-r from-[#114443] via-[#0c3837] to-[#082424] rounded-3xl p-5 md:p-6 text-white border border-[#1b5e5c] shadow-lg relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-5">
        <!-- Background Ambient Glow -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#10b981]/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute left-1/3 -top-10 w-36 h-36 bg-[#d4ed31]/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-4 relative z-10 w-full md:w-auto">
            <!-- Staff Avatar with hover edit overlay -->
            <div class="relative group shrink-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-full border-2 border-[#d4ed31] p-1 shadow-md bg-[#114443]">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover shadow-inner">
                </div>
                <button type="button" 
                        @click="$dispatch('open-avatar-modal')"
                        class="absolute inset-0 rounded-full bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-200 cursor-pointer text-white"
                        title="Ganti Foto Profil Staff">
                    <svg class="w-5 h-5 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-[8px] font-bold text-[#d4ed31] uppercase">Edit</span>
                </button>
                <span class="absolute bottom-1 right-1 w-3.5 h-3.5 bg-[#10b981] border-2 border-[#0c3837] rounded-full" title="Staff Aktif"></span>
            </div>

            <!-- Staff Info -->
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight truncate">{{ auth()->user()->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#d4ed31] text-[#0c3837]">
                        Staff Pelayanan Desa
                    </span>
                </div>
                <p class="text-xs text-[#8bc3b8] mt-0.5">{{ auth()->user()->email }} &bull; {{ auth()->user()->phone ?? 'Belum ada nomor telepon' }}</p>
                <p class="text-[11px] text-slate-300 mt-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Status Akun Aktif &bull; Sesi Masuk: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y, H:i') : 'Hari ini' }} WIB</span>
                </p>
            </div>
        </div>

        <!-- Action Button to Edit Avatar -->
        <div class="relative z-10 flex items-center gap-3 w-full md:w-auto justify-end">
            <button type="button" 
                    @click="$dispatch('open-avatar-modal')"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-2xl bg-[#d4ed31] hover:bg-[#c2db26] text-[#0c3837] font-bold text-xs flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Ubah Foto Profil Staff</span>
            </button>
        </div>
    </div>
    @endif

    <!-- 1. Metric Cards with Sparklines (Exact Visual Reference Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($productivityStats as $stat)
            <x-ui.stat-card 
                :day="$stat['day']"
                :date="$stat['date']"
                :percentage="$stat['productive_percent'] . '%'"
                label="Productive"
                :productiveTime="$stat['productive_time']"
                :timeAtWork="$stat['work_time']"
                :variant="$stat['theme']"
            />
        @endforeach
    </div>

    <!-- 2. Middle Section: Statistics Ring & Upcoming Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Progress & Statistics Widget -->
        <x-ui.card class="flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-[#0c3837] tracking-tight">Statistik & Ringkasan Sistem</h3>
                        <p class="text-xs text-[#64748b]">Tinjauan kapasitas modul internal desa pada {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                    <x-ui.badge variant="emerald" :dot="true">
                        Bulan Ini
                    </x-ui.badge>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Donut Gauge Indicator Component -->
                    <x-ui.progress-ring :percentage="82" label="Efisiensi" />

                    <!-- Module Status Breakdown -->
                    <div class="space-y-3 flex-1">
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-[#0c3837]">Staf Aktif Terdaftar</span>
                                <span class="text-[#114443] font-bold">{{ $activeUsers }} / {{ $totalUsers }} Staf</span>
                            </div>
                            <div class="w-full bg-[#e2f0ed] h-2 rounded-full overflow-hidden">
                                <div class="bg-[#114443] h-full rounded-full transition-all duration-500" style="width: {{ $totalUsers > 0 ? ($activeUsers / $totalUsers * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-[#0c3837]">Infrastruktur RBAC</span>
                                <span class="text-[#10b981] font-bold">100% Siap</span>
                            </div>
                            <div class="w-full bg-[#e2f0ed] h-2 rounded-full overflow-hidden">
                                <div class="bg-[#10b981] h-full rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-[#0c3837]">Keamanan Audit Log</span>
                                <span class="text-[#34d399] font-bold">Aktif</span>
                            </div>
                            <div class="w-full bg-[#e2f0ed] h-2 rounded-full overflow-hidden">
                                <div class="bg-[#34d399] h-full rounded-full" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-[#e1ede8] flex items-center justify-between text-xs">
                <span class="text-[#64748b]">Terakhir sinkronisasi: <strong class="text-[#0c3837]">{{ now()->format('d M Y, H:i') }} WIB</strong></span>
                <a href="{{ route('admin.audit.index') }}" class="text-[#10b981] font-bold hover:underline">Lihat Log Sistem &rarr;</a>
            </div>
        </x-ui.card>

        <!-- Upcoming Schedule & Service Activities List -->
        <x-ui.activity-timeline 
            title="Upcoming Schedule"
            subtitle="Jadwal layanan dan koordinasi aparatur kantor desa"
            :items="$upcomingSchedules"
        />
    </div>

    <!-- 3. Quick Actions & Core Module Hub Shortcuts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @can('administrasi.view')
        <a href="{{ route('administrasi.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center gap-3.5 transition-all shadow-xs group">
            <div class="w-11 h-11 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Administrasi</span>
                <span class="text-[11px] text-slate-500">9 Buku & Lembaga</span>
            </div>
        </a>
        @endcan

        @can('kependudukan.view')
        <a href="{{ route('kependudukan.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center gap-3.5 transition-all shadow-xs group">
            <div class="w-11 h-11 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Kependudukan</span>
                <span class="text-[11px] text-slate-500">Buku Induk & KK</span>
            </div>
        </a>

        <a href="{{ route('kependudukan.mutasi.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center gap-3.5 transition-all shadow-xs group">
            <div class="w-11 h-11 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Mutasi Penduduk</span>
                <span class="text-[11px] text-slate-500">Mutasi & Perubahan</span>
            </div>
        </a>
        @endcan

        @can('keuangan.view')
        <a href="{{ route('keuangan.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center gap-3.5 transition-all shadow-xs group">
            <div class="w-11 h-11 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Keuangan Desa</span>
                <span class="text-[11px] text-slate-500">APBDes & Kas</span>
            </div>
        </a>
        @endcan

        @can('pembangunan.view')
        <a href="{{ route('pembangunan.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center gap-3.5 transition-all shadow-xs group">
            <div class="w-11 h-11 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Pembangunan</span>
                <span class="text-[11px] text-slate-500">RKP & Kader KPM</span>
            </div>
        </a>
        @endcan

        @can('kependudukan.view')
        <a href="{{ route('kependudukan.duplicates') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center gap-3.5 transition-all shadow-xs group">
            <div class="w-11 h-11 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Duplikasi NIK</span>
                <span class="text-[11px] text-slate-500">Scanner NIK Ganda</span>
            </div>
        </a>
        @endcan
    </div>

    <!-- 4. Recent Activity Log Feed -->
    <x-ui.card>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-[#0c3837]">Riwayat Aktivitas Staf Terkini (Audit Trail)</h3>
                <p class="text-xs text-[#64748b]">Pencatatan mutasi dan aksi autentikasi staf secara otomatis</p>
            </div>
            @can('audit.view')
            <a href="{{ route('admin.audit.index') }}" class="px-4 py-1.5 bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] rounded-full text-xs font-bold transition-colors">
                Lihat Semua Log &rarr;
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-[#e1ede8] text-[#64748b] font-bold uppercase">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Petugas / Aktor</th>
                        <th class="py-3 px-4">Modul</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8]/60">
                    @forelse ($recentActivities as $activity)
                    <tr class="hover:bg-[#f7faf9] transition-colors">
                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                            {{ $activity->created_at->format('d M Y, H:i:s') }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-[#0c3837] whitespace-nowrap">
                            {{ $activity->causer?->name ?? 'Sistem' }}
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <x-ui.badge variant="emerald">{{ $activity->log_name }}</x-ui.badge>
                        </td>
                        <td class="py-3 px-4 text-[#0f172a]">
                            {{ $activity->description }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-[#64748b]">Belum ada aktivitas tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</x-layouts.app>
