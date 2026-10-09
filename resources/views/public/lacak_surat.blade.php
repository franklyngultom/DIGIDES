@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Header Title -->
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Transparansi Layanan
        </span>
        <h1 class="text-3xl sm:text-4xl font-black text-[#0c3837] tracking-tight">
            Lacak Status Permohonan Surat
        </h1>
        <p class="text-xs sm:text-sm text-[#64748b]">
            Masukkan nomor surat atau nomor registrasi pengajuan Anda untuk mengecek status validasi dan penerbitan dokumen.
        </p>
    </div>

    <!-- Tracker Search Card -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-[#e1ede8] shadow-sm space-y-6">
        <form action="{{ route('public.lacak-surat') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">
                    Nomor Registrasi / Nomor Surat
                </label>
                <div class="relative flex items-center">
                    <input type="text" 
                           name="nomor" 
                           value="{{ $keyword ?? '' }}" 
                           placeholder="Contoh: 470/001/DS-SKM/X/2026 atau nomor resi Anda" 
                           class="w-full pl-12 pr-4 py-3.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]" 
                           required>
                    <svg class="w-5 h-5 text-[#94a3b8] absolute left-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-[#10b981] hover:bg-[#059669] text-white font-bold text-sm shadow-sm transition-colors cursor-pointer">
                Periksa Status Dokumen
            </button>
        </form>

        @if(!empty($keyword))
            <div class="pt-6 border-t border-[#e1ede8]">
                @if($result)
                    <div class="p-5 rounded-2xl bg-[#e2f0ed] border border-[#34d399]/40 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#0c3837] uppercase tracking-wider">Status Dokumen</span>
                            <span class="px-3 py-1 rounded-full bg-[#10b981] text-white text-xs font-extrabold uppercase">
                                {{ $result->status ?? 'Terbit' }}
                            </span>
                        </div>
                        <div class="text-xs space-y-1 text-[#0c3837]">
                            <p><strong>Nomor Surat:</strong> {{ $result->nomor_surat }}</p>
                            <p><strong>Jenis Dokumen:</strong> {{ $result->template->nama_surat ?? '-' }}</p>
                            <p><strong>Nama Pemohon:</strong> {{ $result->penduduk->nama ?? '-' }}</p>
                            <p><strong>Tanggal Terbit:</strong> {{ $result->tanggal_terbit ? \Carbon\Carbon::parse($result->tanggal_terbit)->isoFormat('D MMMM Y') : '-' }}</p>
                        </div>
                        <div class="pt-2 text-[11px] text-[#4fa394] font-medium">
                            &check; Dokumen sah dan terverifikasi dalam arsip register persuratan resmi Pemerintah {{ $desa->nama_desa }}.
                        </div>
                    </div>
                @else
                    <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-center text-rose-700 text-xs space-y-2">
                        <p class="font-bold">Nomor surat atau nomor pengajuan "{{ $keyword }}" tidak ditemukan.</p>
                        <p class="text-[11px] text-rose-600">Pastikan nomor yang Anda masukkan sudah sesuai dengan format tanda bukti pengajuan Anda.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
