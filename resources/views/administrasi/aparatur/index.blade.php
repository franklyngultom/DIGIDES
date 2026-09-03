<x-layouts.app title="Buku Aparat Pemerintah Desa">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'umum']) }}" class="hover:text-[#114443]">Administrasi Umum</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">Buku Aparat Desa</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Buku Aparat Pemerintah Desa</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Buku register susunan aparatur, perangkat desa, NIP, status kepegawaian, dan jam dinas</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('administrasi.aparatur.export-excel', request()->query()) }}" class="px-3.5 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Excel</span>
                </a>

                <a href="{{ route('administrasi.aparatur.export-pdf', request()->query()) }}" target="_blank" class="px-3.5 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>PDF</span>
                </a>

                @can('administrasi.manage')
                <button type="button" @click="$dispatch('open-import-modal-aparatur')" class="px-3.5 py-2.5 bg-white border border-[#e1ede8] text-[#114443] hover:bg-[#e2f0ed] text-xs font-bold rounded-2xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Impor</span>
                </button>

                <a href="{{ route('administrasi.aparatur.create') }}" 
                   class="px-4 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Aparat Desa</span>
                </a>
                @endcan
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-3xl border border-[#e1ede8] shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('administrasi.aparatur.index') }}" class="w-full flex flex-col md:flex-row items-center gap-3">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, atau jabatan..." 
                           class="w-full pl-9 pr-4 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-[#114443] text-white text-xs font-bold rounded-2xl hover:bg-[#0c3837]">
                        Cari
                    </button>
                    @if($search || $status !== null)
                    <a href="{{ route('administrasi.aparatur.index') }}" class="px-3 py-2 text-xs text-rose-600 font-bold hover:underline">
                        Reset
                    </a>
                    @endif
                </div>
            </form>

            <div class="flex items-center gap-2 text-xs font-bold text-[#114443] whitespace-nowrap">
                <span class="px-3 py-1.5 bg-[#e2f0ed] rounded-xl">{{ $stats['total'] }} Total</span>
                <span class="px-3 py-1.5 bg-[#e2f48f] text-[#0c3837] rounded-xl">{{ $stats['aktif'] }} Aktif</span>
            </div>
        </div>

        <!-- Table View -->
        <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#f7faf9] text-[#64748b] border-b border-[#e1ede8] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 font-bold text-center w-12">No</th>
                            <th class="py-3.5 px-4 font-bold">Nama Aparatur & NIK</th>
                            <th class="py-3.5 px-4 font-bold">Jabatan</th>
                            <th class="py-3.5 px-4 font-bold">NIP / No. Induk</th>
                            <th class="py-3.5 px-4 font-bold">Status Pegawai</th>
                            <th class="py-3.5 px-4 font-bold">Jam Dinas Standar</th>
                            <th class="py-3.5 px-4 font-bold text-center">Status</th>
                            <th class="py-3.5 px-4 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8] text-[#0c3837]">
                        @forelse($aparatur as $idx => $item)
                        <tr class="hover:bg-[#f7faf9] transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-[#64748b]">
                                {{ $aparatur->firstItem() + $idx }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#0c3837]">{{ $item->penduduk->nama_lengkap ?? '-' }}</div>
                                <div class="text-[11px] text-[#64748b] font-mono mt-0.5">NIK: {{ $item->penduduk->nik ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 font-bold text-[#114443]">
                                {{ $item->jabatan }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $item->nip ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 bg-[#e2f0ed] text-[#114443] rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $item->status_kepegawaian) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[11px] text-[#64748b]">
                                {{ substr($item->jam_masuk_standar, 0, 5) }} - {{ substr($item->jam_pulang_standar, 0, 5) }} (Tol: {{ $item->toleransi_terlambat_menit }}m)
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->status_aktif)
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 rounded-full text-[10px] font-bold">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('administrasi.manage')
                                    <a href="{{ route('administrasi.aparatur.edit', $item->id) }}" 
                                       title="Edit Data"
                                       class="p-1.5 text-slate-500 hover:text-[#114443] hover:bg-[#e2f0ed] rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('administrasi.aparatur.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data aparat desa ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Data" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                Belum ada data aparat pemerintah desa terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($aparatur->hasPages())
                <div class="p-4 border-t border-[#e1ede8]">
                    {{ $aparatur->links() }}
                </div>
            @endif
        </div>

        <x-ui.import-modal 
            id="aparatur" 
            title="Impor Register Aparatur Desa" 
            action="{{ route('administrasi.aparatur.import') }}" 
            templateUrl="{{ route('administrasi.aparatur.import-template') }}" 
            description="Unggah susunan perangkat dan pamong desa secara massal dari berkas Excel/CSV." />

    </div>
</x-layouts.app>
