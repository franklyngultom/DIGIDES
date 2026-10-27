@extends('layouts.public')

@section('content')
<div x-data="{ tab: '{{ $tab }}', showPermohonanModal: false }" class="space-y-12">

    <!-- Header Banner -->
    <section class="bg-gradient-to-r from-[#0c3837] via-[#114443] to-[#082424] text-white py-14 sm:py-20 border-b border-[#1b5e5c]/40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-[#8bc3b8] mb-4">
                <a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white font-semibold">PPID Desa</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-emerald-300 tracking-wide uppercase">
                    Keterbukaan Informasi Publik (UU No. 14 / 2008)
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                    Pejabat Pengelola Informasi & Dokumentasi (PPID)
                </h1>
                <p class="text-sm sm:text-base text-[#8bc3b8] leading-relaxed">
                    Akses keterbukaan dokumen regulasi perdes, laporan pertanggungjawaban anggaran desa, serta pengajuan permohonan informasi publik secara daring.
                </p>
            </div>

            <!-- Tab Buttons & Request Button -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-8 mt-8 border-t border-white/10">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('public.ppid', ['tab' => 'berkala']) }}" 
                       class="px-5 py-2.5 rounded-full text-xs font-bold transition-all {{ $tab === 'berkala' ? 'bg-white text-[#0c3837] shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Informasi Berkala
                    </a>
                    <a href="{{ route('public.ppid', ['tab' => 'serta-merta']) }}" 
                       class="px-5 py-2.5 rounded-full text-xs font-bold transition-all {{ $tab === 'serta-merta' ? 'bg-white text-[#0c3837] shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Informasi Serta Merta
                    </a>
                    <a href="{{ route('public.ppid', ['tab' => 'setiap-saat']) }}" 
                       class="px-5 py-2.5 rounded-full text-xs font-bold transition-all {{ $tab === 'setiap-saat' ? 'bg-white text-[#0c3837] shadow-md' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        Informasi Setiap Saat
                    </a>
                </div>

                <button type="button" 
                        @click="showPermohonanModal = true"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Permohonan Informasi Online</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Documents Table List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="bg-white rounded-3xl border border-[#e1ede8] overflow-hidden shadow-xs">
            <div class="p-6 border-b border-[#e1ede8] flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#0c3837]">Daftar Dokumen Informasi Publik</h3>
                    <p class="text-xs text-[#64748b]">Silakan unduh dokumen resmi pemerintah desa di bawah ini.</p>
                </div>
                <span class="text-xs font-bold text-[#10b981] bg-emerald-50 px-3 py-1 rounded-full">
                    {{ $filteredDokumen->count() }} Dokumen Tersedia
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#f7faf9] text-[#64748b] border-b border-[#e1ede8] uppercase tracking-wider font-bold">
                        <tr>
                            <th class="px-6 py-4">Nomor Dokumen</th>
                            <th class="px-6 py-4">Judul Dokumen / Informasi</th>
                            <th class="px-6 py-4">Tanggal Rilis</th>
                            <th class="px-6 py-4">Ukuran</th>
                            <th class="px-6 py-4 text-center">Aksi Unduh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8]">
                        @forelse($filteredDokumen as $doc)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-[#0c3837]">
                                    {{ $doc['nomor'] }}
                                </td>
                                <td class="px-6 py-4 font-medium text-[#0c3837] max-w-md">
                                    {{ $doc['judul'] }}
                                </td>
                                <td class="px-6 py-4 text-[#64748b]">
                                    {{ $doc['tgl_rilis'] }}
                                </td>
                                <td class="px-6 py-4 text-[#64748b]">
                                    {{ $doc['ukuran'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" 
                                            onclick="alert('Dokumen {{ $doc['nomor'] }} siap diunduh.')"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#e2f0ed] text-[#0c3837] hover:bg-[#10b981] hover:text-white font-bold text-xs transition-colors shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span>Unduh PDF</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#64748b]">
                                    Belum ada dokumen yang dipublikasikan pada kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alur Permohonan Informasi Publik -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[#e1ede8] shadow-xs space-y-6">
            <div>
                <span class="text-xs font-bold text-[#10b981] uppercase tracking-wider block">Standar Operasional Prosedur</span>
                <h3 class="text-xl font-bold text-[#0c3837]">Alur Pelayanan Permohonan Informasi Publik</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-2">
                    <span class="w-7 h-7 rounded-xl bg-[#0c3837] text-white flex items-center justify-center font-bold text-xs">1</span>
                    <h4 class="font-bold text-[#0c3837]">Pengajuan Online</h4>
                    <p class="text-[#64748b]">Pemohon mengisi formulir permohonan informasi publik secara online atau datang langsung.</p>
                </div>
                <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-2">
                    <span class="w-7 h-7 rounded-xl bg-[#0c3837] text-white flex items-center justify-center font-bold text-xs">2</span>
                    <h4 class="font-bold text-[#0c3837]">Verifikasi Berkas</h4>
                    <p class="text-[#64748b]">Petugas PPID desa memeriksa kelengkapan syarat identitas KTP dan maksud tujuan permohonan.</p>
                </div>
                <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-2">
                    <span class="w-7 h-7 rounded-xl bg-[#0c3837] text-white flex items-center justify-center font-bold text-xs">3</span>
                    <h4 class="font-bold text-[#0c3837]">Proses 3 Hari Kerja</h4>
                    <p class="text-[#64748b]">PPID desa menyiapkan salinan dokumen fisik atau file digital yang diminta pemohon.</p>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2">
                    <span class="w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">4</span>
                    <h4 class="font-bold text-[#0c3837]">Penyerahan Dokumen</h4>
                    <p class="text-[#64748b]">Dokumen diserahkan secara resmi disertai tanda terima berita acara PPID desa.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Permohonan Informasi Modal -->
    <div x-show="showPermohonanModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4">
            <div @click="showPermohonanModal = false" class="fixed inset-0 bg-[#082424]/60 backdrop-blur-sm"></div>

            <div class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-[#e1ede8] space-y-4 z-10">
                <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                    <h3 class="text-base font-bold text-[#0c3837]">Formulir Permohonan Informasi Publik</h3>
                    <button @click="showPermohonanModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <form onsubmit="event.preventDefault(); alert('Permohonan informasi publik Anda berhasil diajukan ke meja PPID.'); showPermohonanModal = false;" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-[#0c3837] mb-1">Nama Pemohon (Sesuai KTP)</label>
                        <input type="text" required placeholder="Nama lengkap..." class="w-full px-3.5 py-2 rounded-xl border border-[#e1ede8] bg-[#f7faf9]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#0c3837] mb-1">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" required placeholder="16 digit NIK..." class="w-full px-3.5 py-2 rounded-xl border border-[#e1ede8] bg-[#f7faf9]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#0c3837] mb-1">No. WhatsApp / HP</label>
                        <input type="tel" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2 rounded-xl border border-[#e1ede8] bg-[#f7faf9]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#0c3837] mb-1">Rincian Informasi yang Dibutuhkan</label>
                        <textarea rows="3" required placeholder="Jelaskan dokumen yang ingin diperoleh..." class="w-full px-3.5 py-2 rounded-xl border border-[#e1ede8] bg-[#f7faf9] resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block font-bold text-[#0c3837] mb-1">Tujuan Penggunaan Informasi</label>
                        <input type="text" required placeholder="Contoh: Riset Akademis, Transparansi Warga, dll" class="w-full px-3.5 py-2 rounded-xl border border-[#e1ede8] bg-[#f7faf9]">
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="showPermohonanModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#0c3837] text-white font-bold">Kirim Permohonan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
