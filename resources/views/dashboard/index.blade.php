<x-layouts.app>
    <x-slot:title>Dashboard Produktivitas</x-slot:title>

    <!-- Top Workspace Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Working Productivity</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Pantau kinerja pelayanan desa dan koordinasi kegiatan terkini</p>
        </div>
        
        <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 sm:gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-72 md:w-80">
                <input type="text" placeholder="Cari layanan, surat, atau warga..." 
                       class="w-full px-4 py-2.5 bg-white border border-[#e1ede8] rounded-2xl text-xs sm:text-sm focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 shadow-xs transition-all">
            </div>
            @can('user.create')
            <x-ui.button href="{{ route('admin.users.create') }}" variant="primary" size="sm" class="shrink-0 w-full sm:w-auto justify-center">
                <span>Tambah Staf</span>
            </x-ui.button>
            @endcan
        </div>
    </div>

    @if(auth()->check() && auth()->user()->hasRole('Staff Desa'))
    <!-- Staff Profile & Quick Actions Banner -->
    <div class="bg-gradient-to-r from-[#114443] via-[#0c3837] to-[#082424] rounded-3xl p-5 md:p-6 text-white border border-[#1b5e5c] shadow-lg relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-5">
        <!-- Background Ambient Subtle Accent -->
        <div class="absolute right-0 top-0 w-80 h-full bg-gradient-to-l from-emerald-500/10 to-transparent pointer-events-none"></div>

        <div class="flex items-center gap-4 relative z-10 w-full md:w-auto min-w-0 flex-1">
            <!-- Staff Avatar with hover edit overlay -->
            <div class="relative group shrink-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-full border-2 border-emerald-400 p-1 shadow-md bg-[#114443]">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover shadow-inner">
                </div>
                <button type="button" 
                        @click="$dispatch('open-avatar-modal')"
                        class="absolute inset-0 rounded-full bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-200 cursor-pointer text-white"
                        title="Ganti Foto Profil Staff">
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-[8px] font-bold text-emerald-300 uppercase">Edit</span>
                </button>
                <span class="absolute bottom-1 right-1 w-3.5 h-3.5 bg-[#10b981] border-2 border-[#0c3837] rounded-full" title="Staff Aktif"></span>
            </div>

            <!-- Staff Info -->
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight truncate">{{ auth()->user()->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-400 text-[#0c3837]">
                        Staff Pelayanan Desa
                    </span>
                </div>
                <p class="text-xs text-[#8bc3b8] mt-0.5 truncate">{{ auth()->user()->email }} &bull; {{ auth()->user()->phone ?? 'Belum ada nomor telepon' }}</p>
                <p class="text-[11px] text-slate-300 mt-1 flex items-center gap-1.5 flex-wrap">
                    <span>Status Akun Aktif &bull; Sesi Masuk: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y, H:i') : 'Hari ini' }} WIB</span>
                </p>
            </div>
        </div>

        <!-- Action Button to Edit Avatar -->
        <div class="relative z-10 flex items-center gap-3 w-full md:w-auto justify-end shrink-0">
            <button type="button" 
                    @click="$dispatch('open-avatar-modal')"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-2xl bg-emerald-400 hover:bg-emerald-300 text-[#0c3837] font-bold text-xs flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer">
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

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 sm:gap-6">
                    <!-- Donut Gauge Indicator Component -->
                    <div class="shrink-0">
                        <x-ui.progress-ring :percentage="82" label="Efisiensi" />
                    </div>

                    <!-- Module Status Breakdown -->
                    <div class="space-y-3 flex-1 w-full">
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

            <div class="mt-6 pt-4 border-t border-[#e1ede8] flex flex-wrap items-center justify-between gap-2 text-xs">
                <span class="text-[#64748b]">Terakhir sinkronisasi: <strong class="text-[#0c3837]">{{ now()->format('d M Y, H:i') }} WIB</strong></span>
                @can('audit.view')
                <a href="{{ route('admin.audit.index') }}" class="text-[#10b981] font-bold hover:underline">Lihat Log Sistem &rarr;</a>
                @endcan
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
    @php
        $hasFullModuleShortcuts = auth()->check() && (auth()->user()->can('keuangan.view') || auth()->user()->can('pembangunan.view'));
    @endphp
    <div :class="sidebarExpanded ? 'grid-cols-1 sm:grid-cols-2 {{ $hasFullModuleShortcuts ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }}' : 'grid-cols-1 sm:grid-cols-2 {{ $hasFullModuleShortcuts ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }}'"
         class="grid grid-cols-1 sm:grid-cols-2 {{ $hasFullModuleShortcuts ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }} gap-3 sm:gap-4">
        @can('administrasi.view')
        <a href="{{ route('administrasi.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center justify-between gap-3 transition-all shadow-xs group min-w-0">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981] leading-snug truncate">Administrasi</span>
                <span class="text-[11px] text-slate-500 block leading-snug truncate mt-0.5">9 Buku & Lembaga</span>
            </div>
        </a>
        @endcan

        @can('kependudukan.view')
        <a href="{{ route('kependudukan.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center justify-between gap-3 transition-all shadow-xs group min-w-0">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981] leading-snug truncate">Kependudukan</span>
                <span class="text-[11px] text-slate-500 block leading-snug truncate mt-0.5">Buku Induk & KK</span>
            </div>
        </a>

        <a href="{{ route('kependudukan.mutasi.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center justify-between gap-3 transition-all shadow-xs group min-w-0">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981] leading-snug truncate">Mutasi Penduduk</span>
                <span class="text-[11px] text-slate-500 block leading-snug truncate mt-0.5">Mutasi & Perubahan</span>
            </div>
        </a>
        @endcan

        @can('keuangan.view')
        <a href="{{ route('keuangan.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center justify-between gap-3 transition-all shadow-xs group min-w-0">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981] leading-snug truncate">Keuangan Desa</span>
                <span class="text-[11px] text-slate-500 block leading-snug truncate mt-0.5">APBDes & Kas</span>
            </div>
        </a>
        @endcan

        @can('pembangunan.view')
        <a href="{{ route('pembangunan.index') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center justify-between gap-3 transition-all shadow-xs group min-w-0">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981] leading-snug truncate">Pembangunan</span>
                <span class="text-[11px] text-slate-500 block leading-snug truncate mt-0.5">RKP & Kader KPM</span>
            </div>
        </a>
        @endcan

        @can('kependudukan.view')
        <a href="{{ route('kependudukan.duplicates') }}" class="p-4 rounded-3xl bg-white hover:bg-[#edf5f2] border border-[#e1ede8] flex items-center justify-between gap-3 transition-all shadow-xs group min-w-0">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981] leading-snug truncate">Duplikasi NIK</span>
                <span class="text-[11px] text-slate-500 block leading-snug truncate mt-0.5">Scanner NIK Ganda</span>
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
