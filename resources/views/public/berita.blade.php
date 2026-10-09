@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Header Title -->
    <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#e2f0ed] text-xs font-bold text-[#0c3837]">
            Kabar Desa Terkini
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-[#0c3837] tracking-tight">
            Berita & Pengumuman Desa
        </h1>
        <p class="text-sm sm:text-base text-[#64748b]">
            Dapatkan informasi kegiatan pemerintah desa, pengumuman program bantuan, jadwal kegiatan warga, dan agenda pembangunan.
        </p>
    </div>

    <!-- Category Filters & Search -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs">
        
        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
            @php $currentCat = $kategori ?? 'Semua'; @endphp
            @foreach(['Semua', 'Berita', 'Pengumuman', 'Agenda'] as $cat)
                <a href="{{ route('public.berita', array_merge(request()->query(), ['kategori' => $cat === 'Semua' ? null : $cat])) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all {{ ($currentCat === $cat || ($cat === 'Semua' && empty($kategori))) ? 'bg-[#0c3837] text-white shadow-xs' : 'bg-[#f7faf9] text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#0c3837]' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Search Input -->
        <form action="{{ route('public.berita') }}" method="GET" class="relative w-full md:w-72">
            @if(!empty($kategori))
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            <input type="text" 
                   name="q" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Cari berita..." 
                   class="w-full pl-10 pr-4 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-full text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
            <svg class="w-4 h-4 text-[#94a3b8] absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </form>

    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($newsList as $news)
            <article class="bg-white rounded-[2rem] overflow-hidden border border-[#e1ede8] shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="h-48 overflow-hidden bg-[#e2f0ed] relative">
                        <img src="{{ $news->image_url }}" 
                             alt="{{ $news->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-[11px] font-bold text-[#0c3837] shadow-xs">
                            {{ $news->kategori }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="text-[11px] font-semibold text-[#64748b] mb-2 flex items-center gap-2">
                            <span>{{ $news->published_at ? $news->published_at->isoFormat('D MMMM Y') : 'Terbaru' }}</span>
                            <span>•</span>
                            <span>{{ $news->penulis }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors leading-snug line-clamp-2">
                            <a href="{{ route('public.berita.detail', $news->slug) }}">
                                {{ $news->judul }}
                            </a>
                        </h3>
                        <p class="text-xs text-[#64748b] mt-2 line-clamp-3 leading-relaxed">
                            {{ $news->ringkasan }}
                        </p>
                    </div>
                </div>
                <div class="px-6 pb-6 pt-2">
                    <a href="{{ route('public.berita.detail', $news->slug) }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0c3837] group-hover:text-[#10b981] transition-colors">
                        <span>Baca Selengkapnya</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-[#e1ede8]">
                <p class="text-sm font-semibold text-[#64748b]">Belum ada berita dalam kategori ini.</p>
                <a href="{{ route('public.berita') }}" class="mt-3 inline-block text-xs font-bold text-[#10b981]">Lihat Semua Kategori</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $newsList->links() }}
    </div>

</div>
@endsection
