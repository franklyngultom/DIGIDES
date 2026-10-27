@extends('layouts.public')

@section('content')
<div class="space-y-12">

    <!-- Header Banner -->
    <section class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#082424] text-white py-14 sm:py-20 border-b border-[#1b5e5c]/40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-[#8bc3b8] mb-4">
                <a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white font-semibold">Listing Potensi</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-emerald-300 tracking-wide uppercase">
                    Direktori Desa Terpadu
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Listing Potensi & Fasilitas Desa
                </h1>
                <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                    Direktori lengkap mengenai kekayaan potensi alam, sentra UMKM warga, destinasi wisata desa, serta sarana fasilitas publik yang ada di {{ $desa->nama_desa ?? 'Desa Balam Sempurna' }}.
                </p>
            </div>

            <!-- Search & Filter Controls -->
            <div class="pt-8 mt-8 border-t border-white/10 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <!-- Filter Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    @php
                        $filterOptions = ['Semua', 'Potensi Alam', 'UMKM Desa', 'Fasilitas Umum', 'BUMDes', 'Wisata Desa'];
                    @endphp
                    @foreach($filterOptions as $opt)
                        <a href="{{ route('public.listing', ['kategori' => $opt, 'q' => $search]) }}"
                           class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $kategori === $opt ? 'bg-white text-[#0c3837] shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
                            {{ $opt }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Input -->
                <form action="{{ route('public.listing') }}" method="GET" class="relative w-full md:w-72">
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari potensi / fasilitas..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-full bg-white/10 border border-white/20 text-white placeholder:text-emerald-200/60 text-xs focus:outline-none focus:bg-white/20">
                    <svg class="w-4 h-4 text-emerald-200/80 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
            </div>
        </div>
    </section>

    <!-- Listing Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($listings as $item)
                <div class="bg-white rounded-3xl border border-[#e1ede8] overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <!-- Image Container -->
                        <div class="h-52 overflow-hidden bg-[#e2f0ed] relative">
                            <img src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-[10px] font-bold text-[#0c3837] shadow-xs">
                                {{ $item['kategori'] }}
                            </span>
                            <span class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full bg-[#10b981] text-white text-[10px] font-bold">
                                {{ $item['badge'] }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-3">
                            <h3 class="text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors leading-snug">
                                {{ $item['judul'] }}
                            </h3>
                            <p class="text-xs text-[#64748b] leading-relaxed line-clamp-3">
                                {{ $item['deskripsi'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Meta -->
                    <div class="p-6 pt-3 border-t border-slate-50 space-y-2 bg-[#f7faf9]/50">
                        <div class="flex items-center gap-2 text-xs text-[#64748b]">
                            <svg class="w-4 h-4 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $item['lokasi'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1">
                            <span class="text-[11px] text-[#64748b]">{{ $item['kontak'] }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item['kontak']) }}?text=Halo,%20saya%20melihat%20listing%20{{ urlencode($item['judul']) }}" 
                               target="_blank"
                               class="text-xs font-bold text-[#10b981] hover:underline flex items-center gap-1">
                                <span>Kontak</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-[#e1ede8]">
                    <svg class="w-12 h-12 mx-auto text-[#64748b]/40 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-base font-bold text-[#0c3837]">Tidak ada direktori ditemukan.</p>
                    <p class="text-xs text-[#64748b] mt-1">Coba gunakan kata kunci pencarian yang berbeda.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
