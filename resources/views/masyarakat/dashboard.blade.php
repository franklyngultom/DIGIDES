@extends('layouts.masyarakat')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Welcome Hero Card -->
    <div class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#0c3837] rounded-[2.5rem] p-8 sm:p-10 text-white relative shadow-lg overflow-hidden border border-[#1b5e5c]/40">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            
            <div class="md:col-span-8 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold text-[#d4ed31]">
                    <span>Portal Layanan Mandiri</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black">
                    Halo, {{ $user->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-[#8bc3b8] max-w-xl leading-relaxed">
                    Selamat datang di ruang layanan mandiri warga Desa {{ $desa->nama_desa }}. Ajukan berbagai permohonan surat keterangan desa dengan cepat, pantau progres berkas, dan unduh dokumen resmi dari rumah.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('masyarakat.pengajuan.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-xs transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Ajukan Surat Baru</span>
                    </a>
                    <a href="{{ route('masyarakat.pengajuan.index') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-xs transition-all">
                        <span>Riwayat Pengajuan</span>
                    </a>
                </div>
            </div>

            <!-- Verification Status Pill Card -->
            <div class="md:col-span-4 flex md:justify-end">
                <div class="w-full max-w-xs bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/15 space-y-2">
                    <span class="block text-[10px] uppercase font-bold text-[#8bc3b8]">Status Kependudukan</span>
                    @if($profile?->isVerified())
                        <div class="flex items-center gap-2 text-sm font-black text-[#d4ed31]">
                            <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Terverifikasi Sah</span>
                        </div>
                        <p class="text-[11px] text-slate-200">
                            NIK Anda <strong>{{ $profile->nik }}</strong> cocok dengan data kependudukan resmi desa.
                        </p>
                    @else
                        <div class="flex items-center gap-2 text-sm font-black text-amber-300">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Menunggu Validasi</span>
                        </div>
                        <p class="text-[11px] text-slate-200">
                            Lengkapi foto KTP di halaman profil untuk mempercepat proses persetujuan.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Quick Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <a href="{{ route('masyarakat.pengajuan.index') }}" class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs flex items-center gap-4 hover:border-[#10b981] transition-all">
            <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <span class="block text-2xl font-black text-[#0c3837]">{{ $stats['total_pengajuan'] }}</span>
                <span class="block text-xs font-semibold text-[#64748b]">Total Pengajuan</span>
            </div>
        </a>

        <a href="{{ route('masyarakat.pengajuan.index') }}" class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs flex items-center gap-4 hover:border-amber-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <span class="block text-2xl font-black text-amber-600">{{ $stats['diproses'] }}</span>
                <span class="block text-xs font-semibold text-[#64748b]">Sedang Diproses</span>
            </div>
        </a>

        <a href="{{ route('masyarakat.pengajuan.index') }}" class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs flex items-center gap-4 hover:border-emerald-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#10b981] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <span class="block text-2xl font-black text-[#10b981]">{{ $stats['selesai'] }}</span>
                <span class="block text-xs font-semibold text-[#64748b]">Surat Selesai</span>
            </div>
        </a>

        <a href="{{ route('masyarakat.pengajuan.index') }}" class="bg-white rounded-3xl p-5 border border-[#e1ede8] shadow-xs flex items-center gap-4 hover:border-rose-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <span class="block text-2xl font-black text-rose-600">{{ $stats['perlu_perbaikan'] }}</span>
                <span class="block text-xs font-semibold text-[#64748b]">Perlu Perbaikan</span>
            </div>
        </a>

    </div>

    <!-- Recent Requests (if any) -->
    @if($recentRequests->isNotEmpty())
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-[#e1ede8] shadow-sm space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-[#0c3837]">Pengajuan Terkini</h2>
                <p class="text-xs text-[#64748b]">Status terbaru dari pengajuan surat Anda yang terakhir.</p>
            </div>
            <a href="{{ route('masyarakat.pengajuan.index') }}" class="text-xs font-bold text-[#10b981] hover:text-[#0c3837] transition-colors">
                Lihat Semua &raquo;
            </a>
        </div>
        <div class="space-y-3">
            @foreach($recentRequests as $req)
            @php
                $bgClass = match($req->status) {
                    'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'perlu_perbaikan' => 'bg-orange-50 text-orange-700 border-orange-200',
                    'disetujui' => 'bg-teal-50 text-teal-700 border-teal-200',
                    'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-slate-50 text-slate-600 border-slate-200',
                };
            @endphp
            <a href="{{ route('masyarakat.pengajuan.show', $req) }}"
               class="flex items-center justify-between p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] hover:border-[#10b981] transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#e2f0ed] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#0c3837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">{{ $req->suratTemplate->nama_surat ?? '-' }}</p>
                        <p class="text-[11px] text-[#94a3b8] font-mono">{{ $req->nomor_pengajuan }} &middot; {{ $req->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $bgClass }}">
                    {{ $req->statusLabel() }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Letters Selection Grid -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-[#e1ede8] shadow-sm space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-[#0c3837]">Pilih Layanan Persuratan</h2>
                <p class="text-xs text-[#64748b]">Pilih jenis dokumen yang ingin Anda ajukan kepada Pemerintah Desa</p>
            </div>
            <a href="{{ route('public.layanan') }}" class="text-xs font-bold text-[#10b981] hover:text-[#0c3837] transition-colors">
                Lihat Semua Layanan &raquo;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @forelse($availableTemplates as $tpl)
                <div class="p-5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] hover:border-[#10b981] transition-all flex flex-col justify-between group">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full bg-[#e2f0ed] text-[10px] font-bold text-[#0c3837]">
                                {{ $tpl->kode_surat }}
                            </span>
                            <span class="text-[10px] text-[#10b981] font-bold">Online</span>
                        </div>
                        <h4 class="text-sm font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                            {{ $tpl->nama_surat }}
                        </h4>
                        <p class="text-xs text-[#64748b] line-clamp-2 leading-relaxed">
                            {{ $tpl->deskripsi ?? 'Pengajuan online untuk keperluan berkas administrasi warga.' }}
                        </p>
                    </div>

                    <div class="pt-4 mt-2 flex items-center justify-between border-t border-[#e1ede8]/60">
                        <span class="text-[11px] text-[#94a3b8]">{{ $tpl->estimasi_proses ?? '~15 mnt' }}</span>
                        <a href="{{ route('masyarakat.pengajuan.create', ['template' => $tpl->id]) }}" 
                           class="px-4 py-1.5 rounded-full bg-[#0c3837] hover:bg-[#114443] text-white text-xs font-bold transition-colors">
                            Pilih
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-xs text-[#64748b]">
                    Tidak ada layanan surat aktif saat ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
