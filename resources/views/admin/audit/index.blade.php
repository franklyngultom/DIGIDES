<x-layouts.app>
    <x-slot:title>Audit Trail & Log Aktivitas</x-slot:title>

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Audit Trail</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Audit Trail & Log Aktivitas Sistem</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Rekaman riwayat autentikasi, mutasi data, dan perubahan konfigurasi sistem internal</p>
        </div>

        <x-badge variant="emerald" class="px-4 py-1.5 text-xs flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Proteksi Audit Aktif</span>
        </x-badge>
    </div>

    <!-- Filters & Table -->
    <x-card class="space-y-4">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.audit.index') }}" class="flex flex-wrap gap-3 items-center justify-between">
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari isi log aktivitas..." 
                           class="w-full pl-10 pr-4 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="log_name" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    <option value="">Semua Modul</option>
                    @foreach ($logNames as $name)
                        <option value="{{ $name }}" {{ $logName === $name ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <select name="causer_id" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    <option value="">Semua Petugas</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ $causerId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>

                <select name="event" class="px-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    <option value="">Semua Event</option>
                    <option value="created" {{ $event === 'created' ? 'selected' : '' }}>Created (Baru)</option>
                    <option value="updated" {{ $event === 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                    <option value="deleted" {{ $event === 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white rounded-full text-xs font-bold transition-all cursor-pointer">
                    Filter
                </button>

                @if($search || $logName || $causerId || $event)
                <a href="{{ route('admin.audit.index') }}" class="px-3 py-2 text-xs text-rose-600 hover:underline">
                    Reset
                </a>
                @endif
            </div>
        </form>

        <!-- Log Table -->
        <div class="overflow-x-auto rounded-2xl border border-[#e1ede8]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f7faf9] text-[#64748b] font-bold uppercase border-b border-[#e1ede8]">
                    <tr>
                        <th class="py-3.5 px-4">Waktu (WIB)</th>
                        <th class="py-3.5 px-4">Petugas / Aktor</th>
                        <th class="py-3.5 px-4">Modul</th>
                        <th class="py-3.5 px-4">Event</th>
                        <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-4 text-center">Payload / Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8]" x-data="{ expandedRow: null }">
                    @forelse ($activities as $act)
                    <tr class="hover:bg-[#f7faf9] transition-colors">
                        <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 font-mono">
                            {{ $act->created_at->format('d M Y, H:i:s') }}
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if ($act->causer)
                                <span class="font-bold text-[#0c3837] block">{{ $act->causer->name }}</span>
                                <span class="text-[10px] text-[#64748b]">{{ $act->causer->email }}</span>
                            @else
                                <span class="font-bold text-slate-500">Sistem Otomatis</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <x-badge variant="emerald">{{ $act->log_name ?? 'system' }}</x-badge>
                        </td>

                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @php
                                $eventVariant = match($act->event) {
                                    'created' => 'lime',
                                    'updated' => 'emerald',
                                    'deleted' => 'rose',
                                    default => 'slate'
                                };
                            @endphp
                            <x-badge :variant="$eventVariant">{{ strtoupper($act->event ?? 'LOG') }}</x-badge>
                        </td>

                        <td class="py-3.5 px-4 text-[#0f172a] max-w-md">
                            {{ $act->description }}
                        </td>

                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($act->properties && count($act->properties) > 0)
                            <button @click="expandedRow = expandedRow === {{ $act->id }} ? null : {{ $act->id }}"
                                    class="px-2.5 py-1 rounded-full bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] text-[11px] font-bold transition-colors cursor-pointer">
                                <span x-text="expandedRow === {{ $act->id }} ? 'Tutup' : 'Lihat Data'"></span>
                            </button>
                            @else
                            <span class="text-slate-400 text-[11px]">-</span>
                            @endif
                        </td>
                    </tr>

                    @if($act->properties && count($act->properties) > 0)
                    <tr x-show="expandedRow === {{ $act->id }}" class="bg-[#f0f7f5]" style="display: none;">
                        <td colspan="6" class="p-4">
                            <div class="p-3 bg-slate-900 text-[#d4ed31] rounded-2xl font-mono text-[11px] overflow-x-auto max-h-48">
                                <pre>{{ json_encode($act->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#64748b]">
                            Tidak ada catatan aktivitas audit trail.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $activities->links() }}
        </div>
    </x-card>
</x-layouts.app>
