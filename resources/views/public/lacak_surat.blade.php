@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Header Title -->
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Transparansi & Unduh Dokumen Mandiri
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-[#0c3837] tracking-tight">
            Lacak Status & Unduh Surat
        </h1>
        <p class="text-xs sm:text-sm text-[#64748b]">
            Masukkan <strong>Nomor Registrasi Pengajuan (PGJ-...)</strong>, <strong>16 Digit NIK Anda</strong>, atau <strong>Nomor Surat Resmi</strong> untuk memantau proses verifikasi dan mengunduh dokumen surat yang telah selesai diterbitkan.
        </p>
        <div class="pt-1">
            <a href="{{ route('public.ajukan-surat') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold shadow-xs transition-all">
                <span>+ Ajukan Permohonan Surat Baru</span>
            </a>
        </div>
    </div>

    {{-- Bukti Tanda Terima Pengajuan Surat Baru --}}
    @if(session('pengajuan_sukses'))
        @php $sukses = session('pengajuan_sukses'); @endphp
        <div class="bg-[#f0fdf4] border-2 border-[#10b981] rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-emerald-200">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#10b981]">Permohonan Berhasil Dikirim</span>
                    <h2 class="text-lg sm:text-xl font-black text-[#0c3837]">Bukti Tanda Terima Pengajuan Surat Online</h2>
                </div>
                <span class="px-3.5 py-1.5 rounded-full bg-[#10b981] text-white text-xs font-mono font-extrabold">
                    {{ $sukses['nomor_pengajuan'] ?? '' }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-3.5 rounded-2xl bg-white border border-emerald-200">
                    <span class="text-[#64748b] block text-[11px]">Nomor Registrasi (Simpan Kode Ini)</span>
                    <span class="font-mono font-black text-sm text-[#0c3837] mt-0.5 block">{{ $sukses['nomor_pengajuan'] ?? '-' }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-emerald-200">
                    <span class="text-[#64748b] block text-[11px]">Jenis Layanan Surat</span>
                    <span class="font-bold text-sm text-[#0c3837] mt-0.5 block">{{ $sukses['nama_surat'] ?? '-' }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-emerald-200">
                    <span class="text-[#64748b] block text-[11px]">Pemohon</span>
                    <span class="font-bold text-sm text-[#0c3837] mt-0.5 block">{{ $sukses['pemohon_nama'] ?? '-' }} ({{ $sukses['pemohon_nik'] ?? '' }})</span>
                </div>
            </div>
            <p class="text-xs text-[#0c3837] leading-relaxed">
                Permohonan Anda telah masuk ke antrean pelayanan petugas desa. Anda dapat melacak status permohonan kapan saja di halaman ini menggunakan <strong>Nomor Registrasi ({{ $sukses['nomor_pengajuan'] ?? '' }})</strong> atau <strong>16 Digit NIK Anda</strong>, serta mengunduh PDF surat resmi setelah diterbitkan tanpa harus datang ke kantor desa.
            </p>
        </div>
    @endif

    <!-- Tracker Search Card -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-[#e1ede8] shadow-sm space-y-6">
        <form action="{{ route('public.lacak-surat') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">
                    Nomor Registrasi (PGJ-...) / 16 Digit NIK / Nomor Surat Resmi
                </label>
                <div class="relative flex items-center">
                    <input type="text" 
                           name="nomor" 
                           value="{{ $keyword ?? '' }}" 
                           placeholder="Contoh: PGJ-{{ date('Y') }}-000001 atau 16 digit NIK Anda..." 
                           class="w-full px-4 py-3.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]" 
                           required>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-sm shadow-sm transition-colors cursor-pointer">
                Periksa Status Dokumen
            </button>
        </form>

        @if(!empty($keyword))
            <div class="pt-6 border-t border-[#e1ede8] space-y-6">
                @if($result)
                    <div class="p-6 rounded-2xl bg-[#e2f0ed] border border-[#34d399]/40 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#0c3837] uppercase tracking-wider">Status Dokumen Resmi</span>
                            <span class="px-3 py-1 rounded-full bg-[#10b981] text-white text-xs font-extrabold uppercase">
                                {{ ucfirst($result->status ?? 'Terbit') }}
                            </span>
                        </div>
                        <div class="text-xs space-y-1.5 text-[#0c3837]">
                            <p><strong>Nomor Surat:</strong> {{ $result->nomor_surat }}</p>
                            <p><strong>Jenis Dokumen:</strong> {{ $result->template->nama_surat ?? '-' }}</p>
                            <p><strong>Nama Pemohon:</strong> {{ $result->penduduk?->nama_lengkap ?? $result->payload_data['pemohon_nama'] ?? '-' }}</p>
                            <p><strong>Tanggal Terbit:</strong> {{ $result->tanggal_terbit ? \Carbon\Carbon::parse($result->tanggal_terbit)->isoFormat('D MMMM Y') : '-' }}</p>
                        </div>
                        <div class="pt-2 text-[11px] text-[#0c3837] font-semibold">
                            &check; Dokumen sah dan terverifikasi dalam arsip register persuratan resmi Pemerintah {{ $desa->nama_desa }}.
                        </div>
                    </div>
                @elseif($pengajuanResult)
                    <div class="p-6 rounded-3xl bg-[#f7faf9] border border-[#e1ede8] space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-[#e1ede8]">
                            <div>
                                <span class="text-[10px] font-bold text-[#64748b] uppercase tracking-wider block">Nomor Registrasi Permohonan</span>
                                <span class="text-lg font-black font-mono text-[#0c3837]">{{ $pengajuanResult->nomor_pengajuan }}</span>
                            </div>
                            <span class="px-3.5 py-1 rounded-full text-xs font-extrabold uppercase self-start sm:self-auto {{ $pengajuanResult->status === 'selesai' ? 'bg-[#10b981] text-white' : ($pengajuanResult->status === 'ditolak' ? 'bg-rose-500 text-white' : 'bg-amber-500 text-white') }}">
                                {{ $pengajuanResult->statusLabel() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-[#0c3837]">
                            <div>
                                <span class="text-[#64748b] block text-[11px]">Jenis Layanan Surat</span>
                                <span class="font-bold text-sm">{{ $pengajuanResult->suratTemplate->nama_surat ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[#64748b] block text-[11px]">Nama Pemohon & NIK</span>
                                <span class="font-bold text-sm">{{ $pengajuanResult->pemohon_nama }}</span>
                                <span class="text-[#64748b] font-mono block">{{ $pengajuanResult->pemohon_nik }}</span>
                            </div>
                            <div>
                                <span class="text-[#64748b] block text-[11px]">Waktu Pengajuan Online</span>
                                <span class="font-semibold">{{ $pengajuanResult->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
                            </div>
                            <div>
                                <span class="text-[#64748b] block text-[11px]">Keperluan Pengajuan</span>
                                <span class="font-semibold">{{ $pengajuanResult->keperluan }}</span>
                            </div>
                        </div>

                        @if($pengajuanResult->pesan_ke_pemohon)
                            <div class="p-4 rounded-2xl bg-white border border-[#e1ede8] text-xs">
                                <span class="font-bold text-[#0c3837] block mb-1">Pesan / Keterangan Petugas Pelayanan Desa:</span>
                                <p class="text-[#475569] leading-relaxed">{{ $pengajuanResult->pesan_ke_pemohon }}</p>
                            </div>
                        @endif

                        @if($pengajuanResult->status === 'selesai' && $pengajuanResult->suratArsip)
                            <div class="p-5 rounded-2xl bg-[#0c3837] text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300">Dokumen Surat Resmi Telah Terbit</span>
                                    <p class="text-sm font-extrabold font-mono">{{ $pengajuanResult->suratArsip->nomor_surat }}</p>
                                    <p class="text-xs text-white/80">Surat Anda telah selesai diverifikasi dan ditandatangani. Silakan unduh dokumen PDF resmi di samping.</p>
                                </div>
                                <a href="{{ route('public.lacak-surat.download', $pengajuanResult) }}"
                                   class="px-6 py-3 rounded-full bg-[#10b981] hover:bg-[#059669] text-white text-xs font-extrabold shadow-md transition-all text-center shrink-0">
                                    Unduh Surat Resmi (PDF)
                                </a>
                            </div>
                        @else
                            <div class="pt-2 text-xs text-[#64748b] font-medium">
                                @if($pengajuanResult->status === 'diproses' || $pengajuanResult->status === 'menunggu')
                                    &bull; Berkas permohonan Anda sedang dalam antrean verifikasi petugas pelayanan desa. Setelah diterbitkan, tombol <strong>Unduh Surat Resmi (PDF)</strong> akan otomatis muncul di halaman ini.
                                @elseif($pengajuanResult->status === 'perlu_perbaikan')
                                    &bull; Petugas memerlukan kelengkapan tambahan. Silakan ajukan ulang atau perbarui melalui portal warga.
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Jika pencarian menggunakan 16 digit NIK dan ada lebih dari 1 riwayat pengajuan --}}
                    @if(isset($pengajuanList) && $pengajuanList->count() > 1)
                        <div class="space-y-3 pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#0c3837]">
                                Seluruh Riwayat Pengajuan Surat untuk NIK {{ $keyword }} ({{ $pengajuanList->count() }} Permohonan)
                            </h3>
                            <div class="space-y-2.5">
                                @foreach($pengajuanList as $item)
                                    <div class="p-4 rounded-2xl bg-white border border-[#e1ede8] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-[#0c3837]">{{ $item->nomor_pengajuan }}</span>
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $item->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                    {{ $item->statusLabel() }}
                                                </span>
                                            </div>
                                            <p class="font-bold text-[#0c3837] mt-1">{{ $item->suratTemplate->nama_surat ?? '-' }}</p>
                                            <p class="text-[11px] text-[#64748b]">Diajukan: {{ $item->created_at->isoFormat('D MMM Y, HH:mm') }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <a href="{{ route('public.lacak-surat', ['nomor' => $item->nomor_pengajuan]) }}"
                                               class="px-4 py-2 rounded-full bg-[#f7faf9] hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] font-bold text-xs transition-all">
                                                Lihat Detail
                                            </a>
                                            @if($item->status === 'selesai' && $item->suratArsip)
                                                <a href="{{ route('public.lacak-surat.download', $item) }}"
                                                   class="px-4 py-2 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs transition-all">
                                                    Unduh PDF
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-center text-rose-700 text-xs space-y-2">
                        <p class="font-bold">Data permohonan atau surat dengan kata kunci "{{ $keyword }}" belum ditemukan.</p>
                        <p class="text-[11px] text-rose-600">Pastikan Nomor Registrasi (PGJ-...) atau 16 digit NIK yang Anda masukkan sudah benar.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
