@extends('layouts.masyarakat')

@section('title', 'Detail Pengajuan ' . $pengajuan->nomor_pengajuan)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-[#64748b]">
        <a href="{{ route('masyarakat.dashboard') }}" class="hover:text-[#10b981]">Dashboard</a>
        <span>/</span>
        <a href="{{ route('masyarakat.pengajuan.index') }}" class="hover:text-[#10b981]">Pengajuan</a>
        <span>/</span>
        <span class="text-[#0c3837] font-semibold font-mono">{{ $pengajuan->nomor_pengajuan }}</span>
    </nav>

    {{-- Status Hero --}}
    @php
        $statusConfig = [
            'menunggu'        => ['bg' => 'from-amber-500 to-amber-600', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu Review Petugas'],
            'diproses'        => ['bg' => 'from-blue-500 to-blue-700', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => 'Sedang Diproses Petugas'],
            'perlu_perbaikan' => ['bg' => 'from-orange-500 to-orange-600', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Perlu Perbaikan Data'],
            'disetujui'       => ['bg' => 'from-teal-500 to-teal-700', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Disetujui & Sedang Diterbitkan'],
            'selesai'         => ['bg' => 'from-emerald-500 to-[#0c3837]', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'label' => 'Selesai — Surat Telah Diterbitkan'],
            'ditolak'         => ['bg' => 'from-red-500 to-red-700', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Pengajuan Ditolak'],
        ];
        $cfg = $statusConfig[$pengajuan->status] ?? $statusConfig['menunggu'];
    @endphp

    <div class="bg-gradient-to-r {{ $cfg['bg'] }} rounded-3xl p-6 text-white shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider opacity-70">Status Pengajuan</p>
                <h2 class="text-xl font-black">{{ $cfg['label'] }}</h2>
                <p class="text-xs opacity-80 mt-0.5 font-mono">{{ $pengajuan->nomor_pengajuan }} · Diajukan {{ $pengajuan->created_at->diffForHumans() }}</p>
            </div>
        </div>

        @if($pengajuan->pesan_ke_pemohon)
            <div class="mt-4 p-4 bg-white/15 rounded-2xl text-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider opacity-70 mb-1">Pesan dari Petugas:</p>
                <p class="leading-relaxed">{{ $pengajuan->pesan_ke_pemohon }}</p>
            </div>
        @endif
    </div>

    {{-- Official Issued Letter Banner & Download --}}
    @if($pengajuan->suratArsip)
    <div class="bg-gradient-to-r from-[#0c3837] to-[#114443] rounded-3xl p-6 text-white shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-[#d4ed31] text-[10px] font-bold uppercase tracking-wider">
                &check; Dokumen Resmi Sah Telah Terbit
            </span>
            <h3 class="text-base sm:text-lg font-black tracking-tight text-white font-mono">No. {{ $pengajuan->suratArsip->nomor_surat }}</h3>
            <p class="text-xs text-white/80">
                Diterbitkan pada {{ $pengajuan->suratArsip->tanggal_terbit ? \Carbon\Carbon::parse($pengajuan->suratArsip->tanggal_terbit)->isoFormat('D MMMM Y') : '-' }} &bull; Terdaftar di Register Desa
            </p>
        </div>

        <a href="{{ route('masyarakat.pengajuan.surat.download', $pengajuan) }}"
           target="_blank"
           class="px-5 py-3 rounded-2xl bg-[#10b981] hover:bg-[#059669] text-white text-xs font-black shadow-md transition-all flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Unduh Surat Resmi (PDF)</span>
        </a>
    </div>
    @endif

    {{-- Detail Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        {{-- Applicant Data --}}
        <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#0c3837] border-b border-[#f1f5f4] pb-3">Data Pemohon</h3>
            <dl class="space-y-2.5 text-xs">
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Nama</dt>
                    <dd class="font-bold text-[#0c3837] text-right">{{ $pengajuan->pemohon_nama }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">NIK</dt>
                    <dd class="font-bold text-[#0c3837] font-mono">{{ $pengajuan->pemohon_nik }}</dd>
                </div>
                @if($pengajuan->pemohon_no_kk)
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">No. KK</dt>
                    <dd class="font-bold text-[#0c3837] font-mono">{{ $pengajuan->pemohon_no_kk }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Alamat</dt>
                    <dd class="font-semibold text-[#0c3837] text-right max-w-[60%]">{{ $pengajuan->pemohon_alamat ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Letter Info --}}
        <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#0c3837] border-b border-[#f1f5f4] pb-3">Info Surat</h3>
            <dl class="space-y-2.5 text-xs">
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Jenis Surat</dt>
                    <dd class="font-bold text-[#0c3837] text-right max-w-[60%]">{{ $pengajuan->suratTemplate->nama_surat ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Kode</dt>
                    <dd class="font-bold text-[#0c3837]">{{ $pengajuan->suratTemplate->kode_surat ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Tgl. Pengajuan</dt>
                    <dd class="font-bold text-[#0c3837]">{{ $pengajuan->created_at->format('d M Y H:i') }}</dd>
                </div>
                @if($pengajuan->selesai_pada)
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Tgl. Selesai</dt>
                    <dd class="font-bold text-[#0c3837]">{{ $pengajuan->selesai_pada->format('d M Y H:i') }}</dd>
                </div>
                @endif
                @if($pengajuan->suratArsip)
                <div class="flex justify-between">
                    <dt class="text-[#64748b] font-semibold">Nomor Surat</dt>
                    <dd class="font-bold font-mono text-[#10b981]">{{ $pengajuan->suratArsip->nomor_surat }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Keperluan / Form Data --}}
    @if($pengajuan->form_data_json)
    <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-[#0c3837]">Isian Formulir Pengajuan</h3>
        <dl class="space-y-2.5 text-xs">
            @foreach($pengajuan->form_data_json as $key => $value)
            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 py-2 border-b border-[#f1f5f4] last:border-0">
                <dt class="text-[#64748b] font-semibold capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                <dd class="font-semibold text-[#0c3837] sm:text-right sm:max-w-[65%]">{{ $value }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
    @endif

    {{-- Supporting Documents --}}
    @if($pengajuan->dokumen_path_json && count($pengajuan->dokumen_path_json) > 0)
    <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-[#0c3837]">Dokumen Pendukung yang Diunggah</h3>
        <div class="space-y-2">
            @foreach($pengajuan->dokumen_path_json as $idx => $dok)
            <div class="flex items-center justify-between p-3 rounded-xl bg-[#f7faf9] border border-[#e1ede8]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#e2f0ed] rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-[#0c3837] truncate max-w-xs">{{ $dok['original_name'] ?? 'Dokumen ' . ($idx + 1) }}</span>
                </div>
                <a href="{{ route('masyarakat.pengajuan.dokumen.download', [$pengajuan, $idx]) }}"
                   class="text-xs font-bold text-[#10b981] hover:text-[#059669] transition-colors">
                    Unduh
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Status History / Audit Trail --}}
    <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-[#0c3837]">Riwayat Status Pengajuan</h3>

        @if($pengajuan->logs->isEmpty())
            <p class="text-xs text-[#64748b]">Belum ada riwayat perubahan status.</p>
        @else
            <ol class="relative border-l-2 border-[#e1ede8] ml-3 space-y-5">
                @foreach($pengajuan->logs as $log)
                    <li class="ml-6">
                        <span class="absolute -left-[9px] w-4 h-4 bg-[#10b981] rounded-full border-2 border-white"></span>
                        <div class="text-xs text-[#64748b]">
                            {{ $log->created_at->format('d M Y H:i') }}
                            @if($log->user)
                                · <span class="font-semibold">{{ $log->user->name }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-[#0c3837] mt-0.5">
                            @if($log->status_sebelum)
                                {{ ucfirst($log->status_sebelum) }} → 
                            @endif
                            {{ ucfirst($log->status_sesudah) }}
                        </p>
                        @if($log->catatan)
                            <p class="text-xs text-[#64748b] mt-0.5 italic">{{ $log->catatan }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    {{-- Back button --}}
    <div class="flex justify-start pb-4">
        <a href="{{ route('masyarakat.pengajuan.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#e1ede8] text-xs font-semibold text-[#64748b] hover:bg-[#f7faf9] transition-colors">
            ← Kembali ke Daftar Pengajuan
        </a>
    </div>

</div>
@endsection
