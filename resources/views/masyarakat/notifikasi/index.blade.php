@extends('layouts.masyarakat')

@section('title', 'Notifikasi Layanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-2">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-[#0c3837]">Pemberitahuan & Notifikasi</h1>
                @if($unreadCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-[11px] font-black animate-pulse">
                        {{ $unreadCount }} Baru
                    </span>
                @endif
            </div>
            <p class="text-xs text-[#64748b] mt-1">
                Informasi penting terkait progres pemeriksaan, revisi berkas, dan penerbitan surat Anda.
            </p>
        </div>

        @if($unreadCount > 0)
            <form action="{{ route('masyarakat.notifikasi.read-all') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-[#f7faf9] border border-[#e1ede8] text-[#0c3837] text-xs font-bold rounded-full shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifications List --}}
    @if($notifications->isEmpty())
        <div class="bg-white rounded-3xl border border-[#e1ede8] p-16 text-center shadow-xs">
            <div class="w-16 h-16 bg-[#e2f0ed] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-base font-bold text-[#0c3837]">Belum Ada Notifikasi</p>
            <p class="text-xs text-[#64748b] mt-1">Anda akan menerima pemberitahuan otomatis saat status permohonan surat Anda diperbarui oleh petugas desa.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                    $data     = $notif->data ?? [];
                    $status   = $data['status'] ?? 'menunggu';
                    $title    = $data['title'] ?? 'Pemberitahuan Layanan';
                    $message  = $data['message'] ?? '-';
                    $noReg    = $data['nomor_pengajuan'] ?? '-';
                    $surat    = $data['nama_surat'] ?? 'Layanan Surat';
                @endphp

                <div class="bg-white rounded-2xl border transition-all duration-200 p-5 shadow-xs flex flex-col sm:flex-row items-start justify-between gap-4 {{ $isUnread ? 'border-emerald-300 ring-2 ring-emerald-500/10 bg-[#fbfdfc]' : 'border-[#e1ede8] opacity-90' }}">
                    <div class="flex items-start gap-3.5">
                        {{-- Status Icon Avatar --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 mt-0.5 {{ $status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($status === 'perlu_perbaikan' ? 'bg-amber-100 text-amber-700' : ($status === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-[#e2f0ed] text-[#0c3837]')) }}">
                            @if($status === 'selesai')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($status === 'perlu_perbaikan')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            @elseif($status === 'ditolak')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-black text-[#0c3837]">
                                    {{ $title }}
                                </span>
                                @if($isUnread)
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                @endif
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide {{ $status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($status === 'perlu_perbaikan' ? 'bg-amber-100 text-amber-800' : ($status === 'ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ $surat }} &bull; {{ $noReg }}
                                </span>
                            </div>

                            <p class="text-xs text-[#334155] leading-relaxed">
                                {{ $message }}
                            </p>

                            <div class="flex items-center gap-2 pt-1 text-[11px] text-[#94a3b8]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $notif->created_at->diffForHumans() }}</span>
                                @if($notif->read_at)
                                    <span>&bull; Dibaca {{ $notif->read_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                        <a href="{{ route('masyarakat.notifikasi.read', $notif->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold {{ $isUnread ? 'bg-[#0c3837] text-white hover:bg-[#114443]' : 'bg-[#f1f5f4] text-[#0c3837] hover:bg-[#e2f0ed]' }} transition-all">
                            <span>Buka Permohonan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
