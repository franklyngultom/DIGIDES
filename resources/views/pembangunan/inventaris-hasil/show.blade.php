<x-layouts.app :title="'Detail Inventaris: ' . $item->nomor_inventaris">
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('pembangunan.index') }}" class="hover:text-[#114443]">Pembangunan Desa</a>
                    <span>/</span>
                    <a href="{{ route('pembangunan.inventaris-hasil.index', ['tahun' => $item->tahun_anggaran]) }}" class="hover:text-[#114443]">Inventaris Hasil</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">{{ $item->nomor_inventaris }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                        {{ $item->nama_hasil_pembangunan }}
                    </h1>
                    <x-badge :variant="$item->kondisi_variant">
                        Kondisi {{ $item->kondisi_label }}
                    </x-badge>
                </div>
                <p class="text-xs text-[#64748b] mt-0.5 font-mono">Kode Aset: {{ $item->nomor_inventaris }} &bull; Tahun Selesai: {{ $item->tahun_anggaran }}</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('pembangunan.inventaris-hasil.index', ['tahun' => $item->tahun_anggaran]) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                    &larr; Kembali
                </a>

                @can('pembangunan.manage')
                <a href="{{ route('pembangunan.inventaris-hasil.edit', $item) }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Aset</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Meta Highlights Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-card class="p-4 bg-[#0c3837] text-white">
                <span class="text-[11px] text-slate-300 font-medium block">Nilai Perolehan Aset</span>
                <span class="text-xl font-extrabold text-[#d4ed31] block mt-1 font-mono">Rp {{ number_format($item->nilai_aset, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-300 block mt-0.5">Sumber Dana: {{ $item->sumber_dana }}</span>
            </x-card>

            <x-card class="p-4">
                <span class="text-[11px] text-[#64748b] font-medium block">Kategori & Dimensi</span>
                <span class="text-xs font-bold text-[#0c3837] block mt-1">{{ $item->kategori_label }}</span>
                <span class="text-[11px] text-[#64748b] block mt-0.5">Volume: {{ $item->volume }}</span>
            </x-card>

            <x-card class="p-4">
                <span class="text-[11px] text-[#64748b] font-medium block">Lokasi & Titik Bangunan</span>
                <span class="text-xs font-bold text-[#0c3837] block mt-1">{{ $item->lokasi }}</span>
                <span class="text-[10px] text-[#64748b] block mt-0.5">BAST: {{ $item->tanggal_serah_terima ? \Carbon\Carbon::parse($item->tanggal_serah_terima)->format('d F Y') : '-' }}</span>
            </x-card>

            <x-card class="p-4">
                <span class="text-[11px] text-[#64748b] font-medium block">Status Pengelolaan</span>
                <span class="text-xs font-bold text-[#0c3837] block mt-1">{{ $item->status_pengelolaan_label }}</span>
                <span class="text-[10px] text-[#64748b] block mt-0.5">PJ: {{ $item->penanggung_jawab ?? 'Pemerintah Desa' }}</span>
            </x-card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Details & Documentation -->
            <div class="md:col-span-2 space-y-6">
                <!-- Foto Hasil Pembangunan -->
                <x-card class="p-6 space-y-4">
                    <h3 class="text-sm font-bold text-[#0c3837] uppercase tracking-wider">Dokumentasi Fisik Hasil Pembangunan</h3>
                    @if($item->foto_hasil_path)
                        <div class="rounded-2xl overflow-hidden border border-[#e1ede8] bg-slate-50">
                            <img src="{{ asset('storage/' . $item->foto_hasil_path) }}" class="w-full h-80 object-cover" alt="{{ $item->nama_hasil_pembangunan }}">
                        </div>
                    @else
                        <div class="p-8 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] text-center text-slate-400">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-medium">Belum ada foto dokumentasi fisik yang diunggah</span>
                        </div>
                    @endif
                </x-card>

                <!-- Keterangan & Catatan Teknis -->
                @if($item->keterangan)
                <x-card class="p-6 space-y-2">
                    <h3 class="text-sm font-bold text-[#0c3837] uppercase tracking-wider">Spesifikasi Teknis & Catatan Pemeliharaan</h3>
                    <p class="text-xs text-[#64748b] leading-relaxed">{{ $item->keterangan }}</p>
                </x-card>
                @endif
            </div>

            <!-- Right Col: Asal Proyek & Berkas BAST -->
            <div class="space-y-6">
                <!-- Asal Proyek Kegiatan -->
                <x-card class="p-6 space-y-3">
                    <h3 class="text-xs font-bold text-[#0c3837] uppercase tracking-wider">Asal Kegiatan Pembangunan</h3>
                    @if($item->proyek)
                        <div class="p-3.5 rounded-2xl bg-[#e2f0ed] border border-[#10b981]/20 space-y-1">
                            <span class="text-[10px] font-bold text-[#114443] uppercase block">Proyek Terhubung:</span>
                            <a href="{{ route('pembangunan.proyek.show', $item->proyek) }}" class="text-xs font-bold text-[#0c3837] hover:text-[#10b981] underline block">
                                {{ $item->proyek->nama_kegiatan }}
                            </a>
                            <span class="text-[11px] text-[#64748b] block">TPK: {{ $item->proyek->pelaksana_tpk }}</span>
                            <span class="text-[11px] text-[#64748b] block">Anggaran: Rp {{ number_format($item->proyek->anggaran_biaya, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="text-xs text-slate-400">
                            Dicatat secara mandiri / hibah aset dari pihak luar.
                        </div>
                    @endif
                </x-card>

                <!-- Berkas Berita Acara Serah Terima (BAST) -->
                <x-card class="p-6 space-y-3">
                    <h3 class="text-xs font-bold text-[#0c3837] uppercase tracking-wider">Dokumen Serah Terima (BAST)</h3>
                    @if($item->file_bast_path)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-[#e1ede8] space-y-2">
                            <div class="flex items-center gap-2 text-xs text-[#0c3837] font-bold">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>Berkas BAST Resmi</span>
                            </div>
                            <a href="{{ asset('storage/' . $item->file_bast_path) }}" target="_blank" class="w-full py-2 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Buka / Unduh Berkas</span>
                            </a>
                        </div>
                    @else
                        <p class="text-xs text-slate-400">Belum ada lampiran berkas BAST PDF yang diunggah.</p>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
