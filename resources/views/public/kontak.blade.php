@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Header Title -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Hubungi Pemerintah Desa
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-[#0c3837] tracking-tight">
            Kontak & Lokasi Kantor Desa
        </h1>
        <p class="text-sm sm:text-base text-[#64748b]">
            Kami siap mendengar saran, masukan, permohonan informasi, dan memberikan pelayanan terbaik bagi seluruh warga.
        </p>
    </div>

    <!-- Contact Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1: Alamat -->
        <div class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-sm space-y-3 text-center">
            <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-[#0c3837]">Alamat Kantor</h3>
            <p class="text-xs text-[#64748b] leading-relaxed">
                {{ $desa->alamat_kantor }}<br>
                Kec. {{ $desa->kecamatan }}, Kab. {{ $desa->kabupaten }}, {{ $desa->provinsi }} {{ $desa->kode_pos }}
            </p>
        </div>

        <!-- Card 2: Telepon & WhatsApp -->
        <div class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-sm space-y-3 text-center">
            <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-[#0c3837]">Telepon & Layanan Info</h3>
            <p class="text-xs text-[#64748b] leading-relaxed">
                Telepon Kantor: <strong class="text-[#0c3837]">{{ $desa->telepon_desa ?? '0266-221144' }}</strong><br>
                Layanan Aduan Cepat: <strong class="text-[#0c3837]">+62 812-3456-7890</strong>
            </p>
        </div>

        <!-- Card 3: Email & Website -->
        <div class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-sm space-y-3 text-center">
            <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-[#0c3837]">Surat Elektronik & Web</h3>
            <p class="text-xs text-[#64748b] leading-relaxed">
                Email: <strong class="text-[#0c3837]">{{ $desa->email_desa ?? 'kontak@desa.id' }}</strong><br>
                Website Resmi: <strong class="text-[#0c3837]">{{ $desa->website ?? 'desa-sukamaju.id' }}</strong>
            </p>
        </div>

    </div>

    <!-- Operating Hours & Office Location Map Placeholder -->
    <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 border border-[#e1ede8] shadow-sm grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        
        <div class="space-y-6">
            <div>
                <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Pelayanan Langsung</span>
                <h3 class="text-2xl font-black text-[#0c3837]">Jam Operasional Kantor Balai Desa</h3>
            </div>

            <p class="text-xs sm:text-sm text-[#64748b] leading-relaxed">
                Pelayanan tatap muka di Kantor Desa Sukamaju berlangsung setiap hari kerja. Untuk pengajuan mandiri melalui portal online DIGIDES, sistem dapat diakses selama 24 jam setiap hari.
            </p>

            <div class="space-y-3 text-xs sm:text-sm">
                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                    <span class="font-bold text-[#0c3837]">Senin — Kamis</span>
                    <span class="text-[#10b981] font-extrabold">08:00 – 15:30 WIB</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                    <span class="font-bold text-[#0c3837]">Jumat</span>
                    <span class="text-[#10b981] font-extrabold">08:00 – 14:00 WIB</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                    <span class="font-bold text-[#0c3837]">Sabtu, Minggu & Hari Libur Nasional</span>
                    <span class="text-rose-500 font-extrabold">Tutup (Layanan Online Aktif)</span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl overflow-hidden shadow-md border border-[#e1ede8] aspect-[4/3] bg-[#e2f0ed]">
            <img src="{{ asset('images/public/kantor-desa.jpg') }}" alt="Kantor Desa" class="w-full h-full object-cover">
        </div>

    </div>

</div>
@endsection
