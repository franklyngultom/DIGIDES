<x-layouts.app :title="'Detail RAB: ' . $rab->nomor_rab">
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                    <span>/</span>
                    <a href="{{ route('keuangan.rab.index', ['tahun' => $rab->tahun_anggaran]) }}" class="hover:text-[#114443]">RAB Desa</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">{{ $rab->nomor_rab }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                        {{ $rab->nama_kegiatan }}
                    </h1>
                    <x-badge :variant="$rab->status_variant">
                        {{ $rab->status_label }}
                    </x-badge>
                </div>
                <p class="text-xs text-[#64748b] mt-0.5 font-mono">Nomor Registrasi: {{ $rab->nomor_rab }} &bull; Tahun Anggaran: {{ $rab->tahun_anggaran }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('keuangan.rab.export-pdf', $rab) }}" target="_blank" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak RAB (PDF)</span>
                </a>

                @can('keuangan.manage')
                <!-- Quick Status Change Form -->
                <form method="POST" action="{{ route('keuangan.rab.update-status', $rab) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981] cursor-pointer" title="Ubah status persetujuan">
                        <option value="draft" {{ $rab->status === 'draft' ? 'selected' : '' }}>Set: Draft</option>
                        <option value="disetujui" {{ $rab->status === 'disetujui' ? 'selected' : '' }}>Set: Disetujui</option>
                        <option value="direalisasikan" {{ $rab->status === 'direalisasikan' ? 'selected' : '' }}>Set: Direalisasikan</option>
                    </select>
                </form>

                <a href="{{ route('keuangan.rab.edit', $rab) }}" class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit RAB</span>
                </a>
                @endcan
            </div>
        </div>

        <!-- Meta Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-card class="p-4">
                <span class="text-[11px] text-[#64748b] font-medium block">Bidang & Urusan</span>
                <span class="text-xs font-bold text-[#0c3837] block mt-1">{{ $rab->bidang }}</span>
                @if($rab->sub_bidang)
                    <span class="text-[10px] text-slate-400 block mt-0.5">{{ $rab->sub_bidang }}</span>
                @endif
            </x-card>

            <x-card class="p-4">
                <span class="text-[11px] text-[#64748b] font-medium block">Lokasi & Waktu Pelaksanaan</span>
                <span class="text-xs font-bold text-[#0c3837] block mt-1">{{ $rab->lokasi }}</span>
                <span class="text-[10px] text-[#64748b] block mt-0.5">{{ $rab->waktu_pelaksanaan }}</span>
            </x-card>

            <x-card class="p-4">
                <span class="text-[11px] text-[#64748b] font-medium block">Sumber Dana & PPKD</span>
                <span class="text-xs font-bold text-[#0c3837] block mt-1">Sumber: <span class="px-2 py-0.5 bg-[#e2f0ed] text-[#114443] rounded-md font-extrabold">{{ $rab->sumber_dana }}</span></span>
                <span class="text-[10px] text-[#64748b] block mt-0.5">{{ $rab->nama_ppkd ?? 'Kaur / Kasi Teknis' }} ({{ $rab->jabatan_ppkd ?? 'PPKD' }})</span>
            </x-card>

            <x-card class="p-4 bg-[#0c3837] text-white">
                <span class="text-[11px] text-slate-300 font-medium block">Total Anggaran RAB</span>
                <span class="text-lg font-extrabold text-[#d4ed31] block mt-1 font-mono">Rp {{ number_format($rab->total_anggaran, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-300 block mt-0.5">{{ $rab->items->count() }} Komponen Item</span>
            </x-card>
        </div>

        <!-- 4 Category Breakdown Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-card class="p-4 border-l-4 border-l-emerald-500">
                <span class="text-[11px] text-[#64748b] font-medium block">Bahan & Material</span>
                <span class="text-sm font-extrabold text-emerald-800 block mt-0.5 font-mono">Rp {{ number_format($categoryTotals['bahan_material'], 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">
                    {{ $rab->total_anggaran > 0 ? number_format(($categoryTotals['bahan_material'] / $rab->total_anggaran) * 100, 1) : 0 }}% dari Total
                </span>
            </x-card>

            <x-card class="p-4 border-l-4 border-l-blue-500">
                <span class="text-[11px] text-[#64748b] font-medium block">Upah Tenaga Kerja (HOK)</span>
                <span class="text-sm font-extrabold text-blue-800 block mt-0.5 font-mono">Rp {{ number_format($categoryTotals['upah_tenaga_kerja'], 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">
                    {{ $rab->total_anggaran > 0 ? number_format(($categoryTotals['upah_tenaga_kerja'] / $rab->total_anggaran) * 100, 1) : 0 }}% dari Total
                </span>
            </x-card>

            <x-card class="p-4 border-l-4 border-l-amber-500">
                <span class="text-[11px] text-[#64748b] font-medium block">Sewa Peralatan</span>
                <span class="text-sm font-extrabold text-amber-800 block mt-0.5 font-mono">Rp {{ number_format($categoryTotals['sewa_alat'], 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">
                    {{ $rab->total_anggaran > 0 ? number_format(($categoryTotals['sewa_alat'] / $rab->total_anggaran) * 100, 1) : 0 }}% dari Total
                </span>
            </x-card>

            <x-card class="p-4 border-l-4 border-l-purple-500">
                <span class="text-[11px] text-[#64748b] font-medium block">Operasional / Lainnya</span>
                <span class="text-sm font-extrabold text-purple-800 block mt-0.5 font-mono">Rp {{ number_format($categoryTotals['operasional'], 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">
                    {{ $rab->total_anggaran > 0 ? number_format(($categoryTotals['operasional'] / $rab->total_anggaran) * 100, 1) : 0 }}% dari Total
                </span>
            </x-card>
        </div>

        <!-- Detailed Breakdown Table -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-[#e1ede8] flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#0c3837]">Rincian Item Anggaran Biaya</h3>
                    <p class="text-xs text-[#64748b]">Pengelompokan rincian belanja berdasarkan kategori teknis pelaksanaan</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] border-b border-[#e1ede8] text-[#0c3837] font-bold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Kode Rek.</th>
                            <th class="px-4 py-3.5">Uraian Kebutuhan & Spesifikasi</th>
                            <th class="px-4 py-3.5 text-center">Volume</th>
                            <th class="px-4 py-3.5 text-center">Satuan</th>
                            <th class="px-4 py-3.5 text-right">Harga Satuan (Rp)</th>
                            <th class="px-5 py-3.5 text-right">Total Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]/60">
                        @php $globalNo = 1; @endphp

                        @foreach(['bahan_material' => '1. KELOMPOK BAHAN & MATERIAL', 'upah_tenaga_kerja' => '2. KELOMPOK UPAH TENAGA KERJA (HOK)', 'sewa_alat' => '3. KELOMPOK SEWA PERALATAN', 'operasional' => '4. KELOMPOK BIAYA OPERASIONAL & LAINNYA'] as $catKey => $catLabel)
                            @if(isset($groupedItems[$catKey]) && count($groupedItems[$catKey]) > 0)
                                <tr class="bg-[#f0f7f5] font-bold text-[#0c3837]">
                                    <td colspan="6" class="px-4 py-2.5 uppercase tracking-wide text-xs">
                                        {{ $catLabel }}
                                    </td>
                                    <td class="px-5 py-2.5 text-right font-extrabold text-[#114443]">
                                        Subtotal: Rp {{ number_format($categoryTotals[$catKey], 0, ',', '.') }}
                                    </td>
                                </tr>

                                @foreach($groupedItems[$catKey] as $item)
                                    <tr class="hover:bg-[#f7faf9] transition-colors">
                                        <td class="px-4 py-3.5 text-center font-bold text-[#64748b]">
                                            {{ $globalNo++ }}
                                        </td>
                                        <td class="px-4 py-3.5 font-mono text-[#64748b]">
                                            {{ $item->kode_rekening ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="font-bold text-[#0c3837] block">{{ $item->uraian }}</span>
                                            @if($item->keterangan)
                                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $item->keterangan }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-bold text-[#0c3837]">
                                            {{ number_format($item->volume, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center text-[#64748b]">
                                            {{ $item->satuan }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-medium text-[#0c3837]">
                                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-bold text-emerald-800">
                                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach

                        <!-- Grand Total Footer -->
                        <tr class="bg-[#0c3837] text-white font-bold text-xs">
                            <td colspan="6" class="px-6 py-4 text-right uppercase tracking-wider text-sm font-extrabold">
                                TOTAL ANGGARAN BIAYA (RAB) :
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-[#d4ed31] text-sm font-mono">
                                Rp {{ number_format($rab->total_anggaran, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($rab->keterangan)
            <x-card class="p-6">
                <h4 class="text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Catatan Teknis & Justifikasi</h4>
                <p class="text-xs text-[#64748b] leading-relaxed">{{ $rab->keterangan }}</p>
            </x-card>
        @endif
    </div>
</x-layouts.app>
