@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-[#64748b]">
        <a href="{{ route('public.home') }}" class="hover:text-[#0c3837]">Beranda</a>
        <span>/</span>
        <a href="{{ route('public.berita') }}" class="hover:text-[#0c3837]">Berita & Pengumuman</a>
        <span>/</span>
        <span class="text-[#0c3837] truncate max-w-xs">{{ $article->judul }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Main Content (8 cols) -->
        <article class="lg:col-span-8 bg-white rounded-[2.5rem] p-6 sm:p-10 border border-[#e1ede8] shadow-sm space-y-6">
            
            <div class="space-y-3">
                <span class="inline-block px-3 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
                    {{ $article->kategori }}
                </span>
                
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[#0c3837] leading-tight tracking-tight">
                    {{ $article->judul }}
                </h1>

                <div class="flex items-center gap-4 text-xs text-[#64748b] border-y border-[#f1f5f4] py-3">
                    <span>Oleh: <strong class="text-[#0c3837]">{{ $article->penulis }}</strong></span>
                    <span>•</span>
                    <span>{{ $article->published_at ? $article->published_at->isoFormat('dddd, D MMMM Y') : 'Terbaru' }}</span>
                    <span>•</span>
                    <span>Dibaca {{ number_format($article->views_count, 0, ',', '.') }} kali</span>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="rounded-3xl overflow-hidden aspect-[16/9] bg-[#e2f0ed] shadow-xs">
                <img src="{{ $article->image_url }}" alt="{{ $article->judul }}" class="w-full h-full object-cover">
            </div>

            <!-- News Body -->
            <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-[#334155] space-y-4">
                {!! $article->konten !!}
            </div>

            <!-- Share & Back -->
            <div class="pt-6 border-t border-[#f1f5f4] flex items-center justify-between">
                <a href="{{ route('public.berita') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#10b981] hover:text-[#0c3837] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Daftar Berita</span>
                </a>
            </div>

        </article>

        <!-- Sidebar (4 cols) -->
        <aside class="lg:col-span-4 space-y-8">
            
            <!-- Quick Letter CTA Box -->
            <div class="bg-gradient-to-br from-[#0c3837] to-[#114443] rounded-3xl p-6 text-white shadow-md space-y-4 border border-[#1b5e5c]/40">
                <span class="text-[10px] font-bold text-[#d4ed31] uppercase tracking-wider block">Layanan Cepat</span>
                <h3 class="text-lg font-bold">Butuh Surat Keterangan Desa?</h3>
                <p class="text-xs text-[#8bc3b8] leading-relaxed">
                    Ajukan permohonan surat secara online tanpa antre. Cek persyaratan dan mulai permohonan sekarang.
                </p>
                <a href="{{ route('public.layanan') }}" 
                   class="block text-center py-2.5 px-4 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-xs transition-colors">
                    Lihat Katalog Surat
                </a>
            </div>

            <!-- Recent News Widget -->
            <div class="bg-white rounded-3xl p-6 border border-[#e1ede8] shadow-sm space-y-4">
                <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3">Berita Lainnya</h3>
                
                <div class="space-y-4">
                    @forelse($recentNews as $recent)
                        <a href="{{ route('public.berita.detail', $recent->slug) }}" class="flex gap-3 group">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-[#e2f0ed] shrink-0">
                                <img src="{{ $recent->image_url }}" alt="{{ $recent->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="block text-[10px] font-semibold text-[#64748b]">
                                    {{ $recent->published_at ? $recent->published_at->isoFormat('D MMM Y') : '' }}
                                </span>
                                <h4 class="text-xs font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors line-clamp-2 leading-snug">
                                    {{ $recent->judul }}
                                </h4>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-[#64748b]">Tidak ada artikel lain.</p>
                    @endforelse
                </div>
            </div>

        </aside>

    </div>

</div>
@endsection
