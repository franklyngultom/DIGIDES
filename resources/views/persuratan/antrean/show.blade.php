<x-layouts.app>
    <x-slot:title>Proses Pengajuan {{ $pengajuan->nomor_pengajuan }}</x-slot:title>

    <div class="space-y-6 max-w-7xl mx-auto">
        {{-- Breadcrumbs & Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1 text-xs text-[#64748b]">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#10b981]">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('persuratan.antrean.index') }}" class="hover:text-[#10b981]">Antrean Persuratan</a>
                    <span>/</span>
                    <span class="font-bold font-mono text-[#0c3837]">{{ $pengajuan->nomor_pengajuan }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Verifikasi & Pemrosesan Pengajuan</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $pengajuan->statusColor() }}">
                        {{ $pengajuan->statusLabel() }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Diajukan pada {{ $pengajuan->created_at->format('d M Y, H:i') }} ({{ $pengajuan->created_at->diffForHumans() }})</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('persuratan.antrean.index') }}" 
                   class="px-4 py-2.5 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Antrean</span>
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Kolom Kiri: Data Pemohon & Rincian Pengajuan (7 cols) --}}
            <div class="lg:col-span-7 space-y-6">
                {{-- Data Pemohon --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                        <h2 class="text-sm font-bold text-[#0c3837] flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Identitas Pemohon</span>
                        </h2>
                        @if($pengajuan->citizenProfile?->isVerified())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                NIK Terverifikasi Sah
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200">
                                Belum Terverifikasi
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-[#64748b] block text-[11px]">Nama Lengkap</span>
                            <span class="font-bold text-[#0c3837] text-sm">{{ $pengajuan->pemohon_nama }}</span>
                        </div>
                        <div>
                            <span class="text-[#64748b] block text-[11px]">Nomor Induk Kependudukan (NIK)</span>
                            <span class="font-bold font-mono text-[#0c3837] text-sm">{{ $pengajuan->pemohon_nik }}</span>
                        </div>
                        <div>
                            <span class="text-[#64748b] block text-[11px]">Nomor Kartu Keluarga (KK)</span>
                            <span class="font-bold font-mono text-[#0c3837]">{{ $pengajuan->pemohon_no_kk ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[#64748b] block text-[11px]">Akun Terdaftar</span>
                            <span class="font-semibold text-[#0c3837]">{{ $pengajuan->user?->name ?? '-' }} ({{ $pengajuan->user?->email ?? '-' }})</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-[#64748b] block text-[11px]">Alamat Lengkap</span>
                            <span class="font-semibold text-[#0c3837] leading-relaxed">{{ $pengajuan->pemohon_alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Detail Surat yang Dimohonkan --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Rincian Dokumen Surat</span>
                    </h2>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-[#64748b] block text-[11px]">Jenis Surat</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="px-2.5 py-0.5 rounded-full bg-[#e2f0ed] text-[10px] font-bold text-[#0c3837]">
                                    {{ $pengajuan->suratTemplate->kode_surat ?? '-' }}
                                </span>
                                <span class="font-bold text-[#0c3837] text-sm">{{ $pengajuan->suratTemplate->nama_surat ?? 'Template Dihapus' }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-[#64748b] block text-[11px]">Keperluan / Alasan Pengajuan</span>
                            <div class="p-3.5 bg-[#f7faf9] rounded-2xl border border-[#e1ede8] font-semibold text-[#0c3837] mt-1 leading-relaxed">
                                {{ $pengajuan->keperluan }}
                            </div>
                        </div>

                        {{-- Dynamic Form Data (JSON) jika ada --}}
                        @if(!empty($pengajuan->form_data_json))
                            <div>
                                <span class="text-[#64748b] block text-[11px] mb-1">Data Tambahan Formulir</span>
                                <div class="bg-[#f7faf9] rounded-2xl border border-[#e1ede8] p-3.5 space-y-2">
                                    @foreach($pengajuan->form_data_json as $key => $val)
                                        <div class="flex justify-between py-1 border-b border-[#e1ede8]/50 last:border-0">
                                            <span class="text-[#64748b] capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                            <span class="font-bold text-[#0c3837]">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Dokumen Lampiran Pendukung --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span>Dokumen Lampiran Pendukung (KTP/KK/Surat RT/RW)</span>
                    </h2>

                    @php
                        $docs = is_array($pengajuan->dokumen_path_json) ? $pengajuan->dokumen_path_json : [];
                    @endphp

                    @if(count($docs) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($docs as $index => $doc)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] hover:border-[#10b981] transition-all">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-[#0c3837] truncate">{{ $doc['original_name'] ?? 'Dokumen '.$index }}</div>
                                            <div class="text-[10px] text-[#94a3b8]">{{ isset($doc['size']) ? number_format($doc['size'] / 1024, 1).' KB' : 'Berkas' }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('persuratan.antrean.dokumen.download', [$pengajuan, $index]) }}" 
                                       target="_blank"
                                       class="px-3 py-1.5 bg-[#0c3837] hover:bg-[#114443] text-white text-[11px] font-bold rounded-xl flex items-center gap-1 transition-all shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span>Unduh</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-[#f7faf9] rounded-2xl border border-dashed border-[#e1ede8] text-xs text-[#64748b]">
                            Pemohon tidak melampirkan berkas dokumen digital tambahan.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Panel Aksi Petugas & Audit Trail (5 cols) --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Form Pemrosesan Status (Action Box) --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Tindakan Petugas Desa</span>
                    </h2>

                    @if(in_array($pengajuan->status, ['selesai', 'ditolak']))
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 text-xs">
                            <div class="font-bold">Permohonan ini telah berstatus terminal ({{ $pengajuan->statusLabel() }}).</div>
                            <p class="text-[11px] text-[#64748b] mt-1">Tidak ada tindakan lanjutan yang dapat dilakukan untuk pengajuan yang telah selesai atau ditolak.</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('persuratan.antrean.status', $pengajuan) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] mb-1.5">Pilih Tindakan / Status Baru <span class="text-rose-500">*</span></label>
                                <select name="action" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">
                                    <option value="">-- Pilih Status Selanjutnya --</option>
                                    @if($pengajuan->status === 'menunggu')
                                        <option value="diproses">Proses Permohonan (Verifikasi Berkas)</option>
                                        <option value="perlu_perbaikan">Minta Perbaikan Berkas ke Pemohon</option>
                                        <option value="ditolak">Tolak Permohonan</option>
                                    @elseif($pengajuan->status === 'diproses')
                                        <option value="disetujui">Setujui Permohonan (Siap Terbit)</option>
                                        <option value="perlu_perbaikan">Minta Perbaikan Berkas</option>
                                        <option value="ditolak">Tolak Permohonan</option>
                                    @elseif($pengajuan->status === 'perlu_perbaikan')
                                        <option value="diproses">Lanjutkan Proses (Setelah Koreksi Warga)</option>
                                        <option value="ditolak">Tolak Permohonan</option>
                                    @elseif($pengajuan->status === 'disetujui')
                                        <option value="selesai">Selesaikan & Tandai Dokumen Telah Diambil/Terbit</option>
                                        <option value="ditolak">Batalkan / Tolak Permohonan</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] mb-1.5">Pesan untuk Warga / Pemohon</label>
                                <textarea name="pesan_ke_pemohon" 
                                          rows="3" 
                                          placeholder="Contoh: Berkas KTP kurang jelas, mohon unggah ulang foto yang terang..."
                                          class="w-full px-3.5 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">{{ old('pesan_ke_pemohon', $pengajuan->pesan_ke_pemohon) }}</textarea>
                                <span class="text-[10px] text-[#94a3b8]">Pesan ini akan tampil di dashboard portal warga.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] mb-1.5">Catatan Internal Petugas (Audit Desa)</label>
                                <textarea name="catatan_petugas" 
                                          rows="2" 
                                          placeholder="Catatan verifikasi data untuk rekaman staf desa..."
                                          class="w-full px-3.5 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">{{ old('catatan_petugas', $pengajuan->catatan_petugas) }}</textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full py-3 bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold rounded-2xl flex items-center justify-center gap-2 transition-all shadow-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Simpan & Perbarui Status Pengajuan</span>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Status Audit Trail / Riwayat Perubahan --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Riwayat Aktivitas & Audit Trail</span>
                    </h2>

                    <div class="relative pl-6 space-y-5 before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#e1ede8]">
                        @forelse($pengajuan->logs as $log)
                            <div class="relative">
                                <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-[#10b981] border-2 border-white"></div>
                                <div class="text-xs font-bold text-[#0c3837]">
                                    {{ ucfirst(str_replace('_', ' ', $log->status_sesudah)) }}
                                    @if($log->status_sebelum)
                                        <span class="text-[11px] font-normal text-[#94a3b8]">(sebelumnya: {{ $log->status_sebelum }})</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-[#64748b] mt-0.5">
                                    Oleh <strong class="text-[#0c3837]">{{ $log->user?->name ?? 'Sistem' }}</strong> &bull; {{ $log->created_at->format('d M Y H:i') }}
                                </div>
                                @if($log->catatan)
                                    <div class="p-2.5 bg-[#f7faf9] rounded-xl border border-[#e1ede8] text-[11px] text-[#0f172a] mt-1.5 italic">
                                        "{{ $log->catatan }}"
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-xs text-[#94a3b8]">Belum ada riwayat perubahan status.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
