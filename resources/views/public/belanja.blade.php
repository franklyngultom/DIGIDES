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
                <span class="text-white font-semibold">Lapak Belanja Desa</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-emerald-300 tracking-wide uppercase">
                    Pemberdayaan Ekonomi Warga
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Lapak Belanja UMKM & BUMDes
                </h1>
                <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                    Dukung kemandirian perekonomian warga desa dengan membeli produk hasil panen pertanian organik, madu hutan alami, aneka olahan kuliner lezat, dan kerajinan kriya lokal.
                </p>
            </div>

            <!-- Categories -->
            <div class="flex flex-wrap items-center gap-2 pt-8 mt-8 border-t border-white/10">
                @php
                    $categories = ['Semua', 'Hasil Hutan & Tani', 'Kuliner Olahan', 'Kriya & Kerajinan', 'Kesehatan Herbal'];
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('public.belanja', ['kategori' => $cat]) }}" 
                       class="px-5 py-2.5 rounded-full text-xs font-bold transition-all {{ $kategori === $cat ? 'bg-white text-[#0c3837] shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($produkList as $produk)
                <div class="bg-white rounded-3xl border border-[#e1ede8] overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <!-- Image Container -->
                        <div class="h-56 overflow-hidden bg-[#e2f0ed] relative">
                            <img src="{{ $produk['gambar'] }}" alt="{{ $produk['nama'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-[10px] font-bold text-[#0c3837] shadow-xs">
                                {{ $produk['kategori'] }}
                            </span>
                            <span class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold">
                                {{ $produk['stok'] }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-[#64748b]">{{ $produk['penjual'] }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors leading-snug">
                                {{ $produk['nama'] }}
                            </h3>
                            <p class="text-xs text-[#64748b] leading-relaxed line-clamp-2">
                                {{ $produk['deskripsi'] }}
                            </p>
                            <p class="text-2xl font-black text-[#10b981] pt-1">
                                Rp {{ number_format($produk['harga'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Buy via WA Button -->
                    <div class="p-6 pt-0">
                        <a href="https://wa.me/{{ $produk['wa'] }}?text=Halo%20{{ urlencode($produk['penjual']) }},%20saya%20tertarik%20membeli%20produk%20{{ urlencode($produk['nama']) }}%20di%20Lapak%20Desa." 
                           target="_blank"
                           class="w-full py-3 rounded-2xl bg-[#114443] hover:bg-[#0c3837] text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all duration-200 hover:scale-102">
                            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.073-1.119-.064-.265-.086-.607-.202-1.047-.393-1.859-.806-3.071-2.686-3.164-2.81-.093-.124-.757-1.009-.757-1.924 0-.916.48-1.365.651-1.549.171-.184.373-.23.498-.23.125 0 .25.002.359.007.115.006.27-.044.422.323.156.376.533 1.303.58 1.398.047.094.078.204.016.329-.063.125-.094.204-.187.313-.094.11-.197.245-.282.329-.093.094-.191.196-.082.383.109.187.484.798 1.039 1.292.715.637 1.318.834 1.505.928.188.094.297.078.406-.047.11-.125.469-.547.594-.735.125-.187.25-.156.422-.093.172.062 1.094.516 1.281.609.188.094.313.141.359.219.047.078.047.453-.097.858z"/>
                            </svg>
                            <span>Pesan via WhatsApp</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-[#e1ede8]">
                    <p class="text-base font-bold text-[#0c3837]">Belum ada produk dalam kategori ini.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
