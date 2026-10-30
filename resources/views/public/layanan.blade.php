@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Header Title -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Pelayanan Mandiri Tanpa Ke Kantor Desa
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-[#0c3837] tracking-tight">
            Katalog & Pengajuan Surat Online
        </h1>
        <p class="text-sm sm:text-base text-[#64748b]">
            Pilih jenis surat keterangan atau pengantar yang sering diajukan masyarakat di bawah ini untuk langsung mengisi formulir permohonan dari rumah.
        </p>
        <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('public.ajukan-surat') }}"
               class="px-6 py-3 rounded-full bg-[#10b981] hover:bg-[#059669] text-white text-xs sm:text-sm font-extrabold shadow-sm transition-all">
                Buka Formulir Pengajuan Surat Online &rarr;
            </a>
            <a href="{{ route('public.lacak-surat') }}"
               class="px-6 py-3 rounded-full bg-white hover:bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8] text-xs sm:text-sm font-bold transition-all">
                Lacak Status & Unduh Surat PDF
            </a>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="max-w-2xl mx-auto">
        <form action="{{ route('public.layanan') }}" method="GET" class="relative flex items-center">
            <input type="text" 
                   name="q" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Ketik nama surat atau kata kunci (contoh: SKCK, Domisili, Usaha, SKTM, Nikah, Kelahiran)..." 
                   class="w-full pl-5 pr-28 py-3.5 bg-white border border-[#e1ede8] rounded-full text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] shadow-sm">

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
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full bg-[#e2f0ed] text-xs font-extrabold text-[#0c3837]">
                            {{ $tpl->kode_surat ?? 'SRT' }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
                                {{ $tpl->biaya ?? 'Gratis' }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full bg-[#f7faf9] border border-[#e1ede8] text-[10px] font-bold text-[#0c3837]">
                                {{ $tpl->estimasi_proses ?? '1 Hari Kerja' }}
                            </span>
                        </div>
                    </div>

                    <h3 class="text-lg font-extrabold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                        {{ $tpl->nama_surat }}
                    </h3>

                    <p class="text-xs text-[#64748b] mt-2 leading-relaxed">
                        {{ $tpl->deskripsi ?? 'Surat keterangan resmi yang diterbitkan oleh Pemerintah Desa untuk keperluan administrasi warga yang bersangkutan.' }}
                    </p>

                    @if(!empty($tpl->persyaratan_json))
                        <div class="mt-4 pt-3 border-t border-[#f1f5f4]">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748b] block mb-1.5">Syarat Berkas Utama:</span>
                            <ul class="list-disc list-inside text-[11px] text-[#475569] space-y-0.5">
                                @foreach(array_slice($tpl->persyaratan_json, 0, 3) as $syarat)
                                    <li class="truncate">{{ $syarat }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="pt-5 mt-5 border-t border-[#f1f5f4] flex items-center justify-between gap-3">
                    <button @click="openModal = true" 
                            type="button" 
                            class="text-xs font-bold text-[#64748b] hover:text-[#0c3837] transition-colors cursor-pointer">
                        Detail Persyaratan &raquo;
                    </button>

                    <a href="{{ route('public.ajukan-surat', ['template' => $tpl->id]) }}" 
                       class="px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-extrabold text-xs shadow-xs transition-colors">
                        Ajukan Online
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
                                <span class="text-[10px] font-bold text-[#10b981] uppercase tracking-wider">Persyaratan Layanan ({{ $tpl->kode_surat }})</span>
                                <h4 class="text-base font-bold text-[#0c3837]">{{ $tpl->nama_surat }}</h4>
                            </div>
                            <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                &times;
                            </button>
                        </div>

                        <div class="space-y-3 text-xs text-[#475569]">
                            <p class="font-bold text-[#0c3837]">Dokumen yang perlu disiapkan:</p>
                            <ul class="list-disc pl-5 space-y-1.5">
                                @if(!empty($tpl->persyaratan_json))
                                    @foreach($tpl->persyaratan_json as $syarat)
                                        <li>{{ $syarat }}</li>
                                    @endforeach
                                @else
                                    <li>Kartu Tanda Penduduk (KTP) asli / foto jelas.</li>
                                    <li>Kartu Keluarga (KK) yang masih berlaku.</li>
                                    <li>Surat Pengantar dari Ketua RT / RW setempat (jika ada).</li>
                                @endif
                            </ul>
                            <div class="p-3 rounded-xl bg-[#f7faf9] border border-[#e1ede8] text-[11px] text-[#64748b]">
                                <strong>Estimasi Proses:</strong> {{ $tpl->estimasi_proses ?? '1 Hari Kerja' }} &bull; <strong>Biaya:</strong> {{ $tpl->biaya ?? 'Gratis / Rp 0' }}
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button @click="openModal = false" type="button" class="px-4 py-2 rounded-full border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                                Tutup
                            </button>
                            <a href="{{ route('public.ajukan-surat', ['template' => $tpl->id]) }}" class="px-5 py-2 rounded-full bg-[#10b981] text-white text-xs font-bold hover:bg-[#059669]">
                                Isi Formulir Pengajuan &rarr;
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
