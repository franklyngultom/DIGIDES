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
            <a href="{{ route('admin.users.create') }}" 
               class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Staf</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- 1. Metric Cards with Sparklines (Exact Visual Reference Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Lime Accent -->
        <div class="bg-gradient-to-br from-[#e2f48f] to-[#f4fce0] p-6 rounded-3xl border border-[#d4ed31]/60 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/90 backdrop-blur-sm px-3.5 py-1.5 rounded-2xl text-center shadow-xs border border-white">
                        <span class="text-[11px] font-bold text-[#64748b] block uppercase">Mon</span>
                        <span class="text-xl font-black text-[#0c3837]">18</span>
                    </div>
                    <span class="px-3 py-1 bg-white/80 backdrop-blur-sm rounded-full text-xs font-extrabold text-[#0c3837] shadow-xs">
                        86% Productive
                    </span>
                </div>

                <!-- Sparkline SVG Curve -->
                <div class="h-14 w-full my-2">
                    <svg class="w-full h-full" viewBox="0 0 100 40" fill="none" preserveAspectRatio="none">
                        <path d="M0 30 Q 25 10, 50 25 T 100 5" stroke="#0c3837" stroke-width="3" stroke-linecap="round" fill="none" />
                        <circle cx="100" cy="5" r="4" fill="#0c3837" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-[#0c3837]/15">
                <div>
                    <span class="text-[11px] text-[#0c3837]/80 block font-semibold">Productive Time</span>
                    <span class="text-base font-black text-[#0c3837]">5h 12m</span>
                </div>
                <div>
                    <span class="text-[11px] text-[#0c3837]/80 block font-semibold">Time at Work</span>
                    <span class="text-base font-black text-[#0c3837]">5h 45m</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Sage Green -->
        <div class="bg-[#4fa394] text-white p-6 rounded-3xl shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 backdrop-blur-sm px-3.5 py-1.5 rounded-2xl text-center border border-white/20">
                        <span class="text-[11px] font-bold text-white/80 block uppercase">Tue</span>
                        <span class="text-xl font-black text-white">19</span>
                    </div>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-extrabold text-white">
                        72% Productive
                    </span>
                </div>

                <!-- Sparkline SVG Curve -->
                <div class="h-14 w-full my-2">
                    <svg class="w-full h-full" viewBox="0 0 100 40" fill="none" preserveAspectRatio="none">
                        <path d="M0 25 Q 30 35, 60 15 T 100 20" stroke="#ffffff" stroke-width="3" stroke-linecap="round" fill="none" />
                        <circle cx="100" cy="20" r="4" fill="#ffffff" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-white/20">
                <div>
                    <span class="text-[11px] text-white/80 block font-semibold">Productive Time</span>
                    <span class="text-base font-black text-white">4h 10m</span>
                </div>
                <div>
                    <span class="text-[11px] text-white/80 block font-semibold">Time at Work</span>
                    <span class="text-base font-black text-white">6h 30m</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Deep Dark Pine -->
        <div class="bg-[#0c3837] text-white p-6 rounded-3xl shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/10 backdrop-blur-sm px-3.5 py-1.5 rounded-2xl text-center border border-white/10">
                        <span class="text-[11px] font-bold text-[#8bc3b8] block uppercase">Wed</span>
                        <span class="text-xl font-black text-white">20</span>
                    </div>
                    <span class="px-3 py-1 bg-[#d4ed31] rounded-full text-xs font-extrabold text-[#0c3837]">
                        90% Productive
                    </span>
                </div>

                <!-- Sparkline SVG Curve -->
                <div class="h-14 w-full my-2">
                    <svg class="w-full h-full" viewBox="0 0 100 40" fill="none" preserveAspectRatio="none">
                        <path d="M0 35 Q 35 30, 70 10 T 100 8" stroke="#d4ed31" stroke-width="3" stroke-linecap="round" fill="none" />
                        <circle cx="100" cy="8" r="4" fill="#d4ed31" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-white/15">
                <div>
                    <span class="text-[11px] text-[#8bc3b8] block font-semibold">Productive Time</span>
                    <span class="text-base font-black text-white">6h 25m</span>
                </div>
                <div>
                    <span class="text-[11px] text-[#8bc3b8] block font-semibold">Time at Work</span>
                    <span class="text-base font-black text-white">7h 10m</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Middle Section: Statistics Ring & Upcoming Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Progress & Statistics Widget -->
        <x-card class="flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-[#0c3837]">Statistik & Ringkasan Sistem</h3>
                        <p class="text-xs text-[#64748b]">Tinjauan kapasitas modul internal desa</p>
                    </div>
                    <span class="px-3 py-1 bg-[#e2f0ed] text-[#114443] rounded-full text-xs font-bold">
                        Bulan Ini
                    </span>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Donut Gauge Indicator -->
                    <div class="relative w-32 h-32 flex items-center justify-center shrink-0">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-[#e2f0ed]" stroke-width="3.5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-[#10b981]" stroke-width="3.5" stroke-dasharray="82, 100" stroke-linecap="round" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center text-center">
                            <span class="text-2xl font-black text-[#0c3837]">82%</span>
                            <span class="text-[9px] font-bold text-[#64748b] uppercase">Efisiensi</span>
                        </div>
                    </div>

                    <!-- Module Status Breakdown -->
                    <div class="space-y-3 flex-1">
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-[#0c3837]">Staf Aktif Terdaftar</span>
                                <span class="text-[#114443]">{{ $activeUsers }} / {{ $totalUsers }} Staf</span>
                            </div>
                            <div class="w-full bg-[#e2f0ed] h-2 rounded-full overflow-hidden">
                                <div class="bg-[#114443] h-full rounded-full" style="width: {{ $totalUsers > 0 ? ($activeUsers / $totalUsers * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-[#0c3837]">Infrastruktur RBAC</span>
                                <span class="text-[#10b981]">100% Siap</span>
                            </div>
                            <div class="w-full bg-[#e2f0ed] h-2 rounded-full overflow-hidden">
                                <div class="bg-[#10b981] h-full rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-[#0c3837]">Keamanan Audit Log</span>
                                <span class="text-[#34d399]">Aktif</span>
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
        </x-card>

        <!-- Quick Actions & Core Infrastructure Status -->
        <x-card class="flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-[#0c3837]">Akses Cepat Pengaturan</h3>
                    <x-badge variant="lime">Fase 1 (P0)</x-badge>
                </div>
                <p class="text-xs text-[#64748b] mb-4">Kelola hak akses, identitas desa, dan cadangan basis data</p>

                <div class="grid grid-cols-2 gap-3">
                    @can('user.view')
                    <a href="{{ route('admin.users.index') }}" class="p-3.5 rounded-2xl bg-[#f7faf9] hover:bg-[#e2f0ed] border border-[#e1ede8] flex items-center gap-3 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Manajemen Staf</span>
                            <span class="text-[11px] text-slate-500">{{ $totalUsers }} Pengguna</span>
                        </div>
                    </a>
                    @endcan

                    @can('desa.view')
                    <a href="{{ route('admin.desa.index') }}" class="p-3.5 rounded-2xl bg-[#f7faf9] hover:bg-[#e2f0ed] border border-[#e1ede8] flex items-center gap-3 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Profil Desa</span>
                            <span class="text-[11px] text-slate-500">{{ $desa->nama_desa }}</span>
                        </div>
                    </a>
                    @endcan

                    @can('audit.view')
                    <a href="{{ route('admin.audit.index') }}" class="p-3.5 rounded-2xl bg-[#f7faf9] hover:bg-[#e2f0ed] border border-[#e1ede8] flex items-center gap-3 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Audit Trail</span>
                            <span class="text-[11px] text-slate-500">Log Aktivitas</span>
                        </div>
                    </a>
                    @endcan

                    @can('backup.manage')
                    <a href="{{ route('admin.backup.index') }}" class="p-3.5 rounded-2xl bg-[#f7faf9] hover:bg-[#e2f0ed] border border-[#e1ede8] flex items-center gap-3 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#0c3837] block group-hover:text-[#10b981]">Cadangan DB</span>
                            <span class="text-[11px] text-slate-500">Snapshot SQLite/SQL</span>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-[#e1ede8] flex justify-between items-center text-xs">
                <span class="text-[#64748b]">Kepala Desa: <strong class="text-[#0c3837]">{{ $desa->nama_kades }}</strong></span>
                <span class="text-emerald-700 font-semibold">Status: Aktif</span>
            </div>
        </x-card>
    </div>

    <!-- 3. Recent Activity Log Feed -->
    <x-card>
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
                            <x-badge variant="emerald">{{ $activity->log_name }}</x-badge>
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
    </x-card>
</x-layouts.app>
