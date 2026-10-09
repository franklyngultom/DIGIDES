@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Header Title -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Katalog Dokumen Mandiri
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-[#0c3837] tracking-tight">
            Katalog Layanan Surat Desa
        </h1>
        <p class="text-sm sm:text-base text-[#64748b]">
            Daftar jenis surat keterangan dan dokumen administrasi kependudukan yang dapat diajukan secara online maupun langsung di kantor desa.
        </p>
    </div>

    <!-- Search & Filter Form -->
    <div class="max-w-2xl mx-auto">
        <form action="{{ route('public.layanan') }}" method="GET" class="relative flex items-center">
            <input type="text" 
                   name="q" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Ketik nama surat atau kata kunci (contoh: SKCK, Domisili, Usaha, Kematian)..." 
                   class="w-full pl-12 pr-28 py-3.5 bg-white border border-[#e1ede8] rounded-full text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] shadow-sm">
            
            <svg class="w-5 h-5 text-[#94a3b8] absolute left-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>

            <button type="submit" class="absolute right-2 px-5 py-2 bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold rounded-full transition-colors cursor-pointer">
                Cari
            </button>
        </form>
    </div>

    <!-- Service Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $tpl)
            <div x-data="{ openModal: false }" class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center font-black text-sm group-hover:bg-[#10b981] group-hover:text-white transition-colors">
                            {{ $tpl->kode_surat ?? 'SRT' }}
                        </div>
                        <span class="px-3 py-1 rounded-full bg-[#f7faf9] border border-[#e1ede8] text-[11px] font-bold text-[#0c3837]">
                            Tersedia Online
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                        {{ $tpl->nama_surat }}
                    </h3>

                    <p class="text-xs text-[#64748b] mt-2 leading-relaxed">
                        {{ $tpl->deskripsi ?? 'Surat keterangan resmi yang diterbitkan oleh Pemerintah Desa untuk keperluan administrasi warga yang bersangkutan.' }}
                    </p>
                </div>

                <div class="pt-6 mt-4 border-t border-[#f1f5f4] flex items-center justify-between">
                    <button @click="openModal = true" 
                            type="button" 
                            class="text-xs font-bold text-[#10b981] hover:text-[#0c3837] transition-colors cursor-pointer">
                        Lihat Persyaratan &raquo;
                    </button>

                    <a href="{{ route('login') }}" 
                       class="px-4 py-2 rounded-full bg-[#0c3837] hover:bg-[#114443] text-white font-bold text-xs shadow-xs transition-colors">
                        Ajukan Sekarang
                    </a>
                </div>

                <!-- Requirement Detail Modal -->
                <div x-show="openModal" 
                     x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
                    <div @click.away="openModal = false" 
                         class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-[#e1ede8] space-y-4">
                        
                        <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                            <div>
                                <span class="text-[10px] font-bold text-[#10b981] uppercase tracking-wider">Persyaratan Layanan</span>
                                <h4 class="text-base font-bold text-[#0c3837]">{{ $tpl->nama_surat }}</h4>
                            </div>
                            <button @click="openModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-3 text-xs text-[#475569]">
                            <p class="font-bold text-[#0c3837]">Dokumen yang wajib disiapkan:</p>
                            <ul class="list-disc pl-5 space-y-1.5">
                                <li>Kartu Tanda Penduduk (KTP) asli / foto jelas.</li>
                                <li>Kartu Keluarga (KK) yang masih berlaku.</li>
                                <li>Surat Pengantar dari Ketua RT / RW setempat (jika ada).</li>
                                <li>Dokumen pendukung khusus sesuai keperluan surat (seperti Surat Keterangan Usaha / Surat Kematian).</li>
                            </ul>
                            <div class="p-3 rounded-xl bg-[#f7faf9] border border-[#e1ede8] text-[11px] text-[#64748b]">
                                <strong>Waktu Proses:</strong> 15 - 30 Menit pada jam operasional kerja setelah diverifikasi oleh petugas pelayanan desa.
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button @click="openModal = false" type="button" class="px-4 py-2 rounded-full border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                Tutup
                            </button>
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-[#10b981] text-white text-xs font-bold hover:bg-[#059669]">
                                Masuk & Ajukan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-[#e1ede8]">
                <p class="text-sm font-semibold text-[#64748b]">Tidak ada layanan yang sesuai dengan kata kunci "{{ $search }}".</p>
                <a href="{{ route('public.layanan') }}" class="mt-3 inline-block text-xs font-bold text-[#10b981]">Reset Pencarian</a>
            </div>
        @endforelse
    </div>

</div>
@endsection
