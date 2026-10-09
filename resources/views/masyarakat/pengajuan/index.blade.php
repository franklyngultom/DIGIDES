@extends('layouts.masyarakat')

@section('title', 'Riwayat Pengajuan Surat')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#0c3837]">Riwayat Pengajuan</h1>
            <p class="text-xs text-[#64748b] mt-1">Daftar seluruh permohonan surat yang pernah Anda ajukan secara online.</p>
        </div>
        <a href="{{ route('masyarakat.pengajuan.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold rounded-full shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Surat Baru
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ session('warning') }}
        </div>
    @endif

    {{-- Table --}}
    @if($pengajuan->isEmpty())
        <div class="bg-white rounded-3xl border border-[#e1ede8] p-16 text-center shadow-sm">
            <div class="w-16 h-16 bg-[#e2f0ed] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-base font-bold text-[#0c3837]">Belum Ada Pengajuan</p>
            <p class="text-xs text-[#64748b] mt-1 mb-5">Anda belum pernah mengajukan surat secara online. Mulai sekarang!</p>
            <a href="{{ route('masyarakat.pengajuan.create') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0c3837] hover:bg-[#114443] text-white text-xs font-bold rounded-full transition-all">
                + Ajukan Surat Pertama
            </a>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#e1ede8] bg-[#f7faf9]">
                            <th class="text-left px-6 py-4 text-xs font-bold text-[#64748b] uppercase tracking-wider">No. Pengajuan</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-[#64748b] uppercase tracking-wider">Jenis Surat</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-[#64748b] uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-[#64748b] uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-4 text-xs font-bold text-[#64748b] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f1f5f4]">
                        @foreach($pengajuan as $item)
                            <tr class="hover:bg-[#f7faf9] transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-[#0c3837]">{{ $item->nomor_pengajuan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-[#0c3837]">{{ $item->suratTemplate->nama_surat ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-[#64748b]">{{ $item->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $colors = [
                                            'menunggu' => 'amber',
                                            'diproses' => 'blue',
                                            'perlu_perbaikan' => 'orange',
                                            'disetujui' => 'teal',
                                            'selesai' => 'green',
                                            'ditolak' => 'red',
                                        ];
                                        $color = $colors[$item->status] ?? 'gray';
                                        $bgClass = match($color) {
                                            'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'blue' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'orange' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'teal' => 'bg-teal-50 text-teal-700 border-teal-200',
                                            'green' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'red' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $bgClass }}">
                                        {{ $item->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('masyarakat.pengajuan.show', $item) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#e2f0ed] hover:bg-[#10b981] text-[#0c3837] hover:text-white text-xs font-bold transition-all">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pengajuan->hasPages())
                <div class="px-6 py-4 border-t border-[#e1ede8]">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
