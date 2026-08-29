<x-layouts.app>
    <x-slot:title>Cadangan Basis Data</x-slot:title>

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Cadangan Basis Data</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Cadangan Basis Data (Database Backup)</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Buat snapshot instan data kependudukan, arsip, dan administrasi desa</p>
        </div>

        <!-- Create Backup Button -->
        <form method="POST" action="{{ route('admin.backup.store') }}">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-md flex items-center gap-2 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>Buat Cadangan Sekarang</span>
            </button>
        </form>
    </div>

    <!-- Storage Usage Banner -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-card class="p-5 bg-gradient-to-br from-[#114443] to-[#0c3837] text-white border-0">
            <span class="text-xs text-[#8bc3b8] font-medium block">Total Ukuran Cadangan</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-white">
                    @if($totalSize >= 1048576)
                        {{ number_format($totalSize / 1048576, 2) }} MB
                    @elseif($totalSize >= 1024)
                        {{ number_format($totalSize / 1024, 2) }} KB
                    @else
                        {{ $totalSize }} Bytes
                    @endif
                </span>
                <span class="text-xs text-[#d4ed31] font-semibold">Tersimpan di Server</span>
            </div>
        </x-card>

        <x-card class="p-5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Total File Cadangan</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ $backups->total() }} Arsip</span>
            </div>
        </x-card>

        <x-card class="p-5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Format Cadangan</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ strtoupper(config('database.default')) }} Snapshot</span>
            </div>
        </x-card>
    </div>

    <!-- Backup List Table -->
    <x-card class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-[#0c3837]">Riwayat Berkas Cadangan</h3>
                <p class="text-xs text-[#64748b]">Daftar file cadangan yang tersedia untuk diunduh</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-[#e1ede8]">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f7faf9] text-[#64748b] font-bold uppercase border-b border-[#e1ede8]">
                    <tr>
                        <th class="py-3.5 px-4">Nama Berkas</th>
                        <th class="py-3.5 px-4">Ukuran</th>
                        <th class="py-3.5 px-4">Tipe Cadangan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Dibuat Oleh</th>
                        <th class="py-3.5 px-4">Waktu Pembuatan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8]">
                    @forelse ($backups as $b)
                    <tr class="hover:bg-[#f7faf9] transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-[#0c3837]">
                            {{ $b->filename }}
                        </td>

                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            {{ $b->formatted_size }}
                        </td>

                        <td class="py-3.5 px-4 text-slate-500">
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-mono text-[10px]">
                                {{ $b->backup_type }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4">
                            @if($b->status === 'success')
                                <x-badge variant="emerald">Berhasil</x-badge>
                            @else
                                <x-badge variant="rose">Gagal</x-badge>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-slate-700">
                            {{ $b->user?->name ?? 'Sistem' }}
                        </td>

                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                            {{ $b->created_at->format('d M Y, H:i') }} WIB
                        </td>

                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                @if($b->status === 'success')
                                <a href="{{ route('admin.backup.download', $b) }}" 
                                   class="px-3 py-1 bg-[#114443] hover:bg-[#0c3837] text-white rounded-full text-[11px] font-bold transition-all flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span>Unduh</span>
                                </a>
                                @endif

                                <form method="POST" action="{{ route('admin.backup.destroy', $b) }}" onsubmit="return confirm('Hapus file cadangan ini dari server?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Hapus Berkas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-[#64748b]">
                            Belum ada berkas cadangan basis data yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $backups->links() }}
        </div>
    </x-card>
</x-layouts.app>
