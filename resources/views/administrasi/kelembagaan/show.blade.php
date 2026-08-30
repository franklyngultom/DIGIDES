<x-layouts.app :title="$institution->nama_lembaga . ' (' . $institution->singkatan . ')'">
    <div class="space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748b] mb-1">
                    <a href="{{ route('administrasi.index', ['tab' => 'kelembagaan']) }}" class="hover:text-[#114443]">Administrasi Kelembagaan</a>
                    <span>/</span>
                    <span class="text-[#0c3837]">{{ $institution->singkatan ?? $institution->nama_lembaga }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">
                        {{ $institution->nama_lembaga }}
                    </h1>
                    <span class="px-3 py-1 bg-[#114443] text-[#d4ed31] font-bold text-xs rounded-xl shadow-xs">
                        {{ $institution->singkatan }}
                    </span>
                </div>
                <p class="text-xs text-[#64748b] mt-1">{{ $institution->deskripsi ?? 'Pusat tata kelola dokumen dan keanggotaan lembaga desa' }}</p>
            </div>

            <!-- Institution Switcher -->
            <div class="flex items-center gap-2">
                <label for="institution-select" class="text-xs font-bold text-[#64748b] whitespace-nowrap">Pilih Lembaga:</label>
                <select id="institution-select" onchange="window.location.href=this.value" class="px-3 py-2 bg-white border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#114443] shadow-xs focus:outline-none cursor-pointer">
                    @foreach($allInstitutions as $inst)
                        <option value="{{ route('administrasi.kelembagaan.show', ['institution' => $inst->slug, 'tab' => $activeTab]) }}" {{ $inst->id === $institution->id ? 'selected' : '' }}>
                            {{ $inst->singkatan }} - {{ $inst->nama_lembaga }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Institution Info Box -->
        <div class="bg-white p-5 rounded-3xl border border-[#e1ede8] shadow-xs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div class="p-3 bg-[#f7faf9] rounded-2xl">
                <span class="text-[11px] font-semibold text-[#64748b] block">Nomor SK Pendirian</span>
                <span class="font-bold text-[#0c3837] mt-0.5 block">{{ $institution->nomor_sk_pendirian ?? 'Belum terdata' }}</span>
            </div>
            <div class="p-3 bg-[#f7faf9] rounded-2xl">
                <span class="text-[11px] font-semibold text-[#64748b] block">Tanggal Penetapan SK</span>
                <span class="font-bold text-[#0c3837] mt-0.5 block">{{ $institution->tanggal_sk ? $institution->tanggal_sk->isoFormat('D MMMM Y') : 'Belum terdata' }}</span>
            </div>
            <div class="p-3 bg-[#f7faf9] rounded-2xl">
                <span class="text-[11px] font-semibold text-[#64748b] block">Kategori Lembaga</span>
                <span class="font-bold text-[#114443] mt-0.5 block uppercase tracking-wider">{{ $institution->kategori }}</span>
            </div>
            <div class="p-3 bg-[#f7faf9] rounded-2xl">
                <span class="text-[11px] font-semibold text-[#64748b] block">Alamat / Sekretariat</span>
                <span class="font-bold text-[#0c3837] mt-0.5 block line-clamp-1">{{ $institution->alamat_sekretariat ?? 'Kantor Desa' }}</span>
            </div>
        </div>

        <!-- 4 Pillars Sub-Tab Navigation -->
        <div class="bg-white p-2 rounded-3xl border border-[#e1ede8] shadow-xs">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota']) }}"
                   class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all text-center flex items-center justify-center gap-2 {{ $activeTab === 'anggota' ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>1. Anggota ({{ $institution->active_members_count }})</span>
                </a>

                <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan']) }}"
                   class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all text-center flex items-center justify-center gap-2 {{ $activeTab === 'keputusan' ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>2. Keputusan ({{ $institution->decisions_count }})</span>
                </a>

                <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan']) }}"
                   class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all text-center flex items-center justify-center gap-2 {{ $activeTab === 'kegiatan' ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>3. Kegiatan ({{ $institution->activities_count }})</span>
                </a>

                <a href="{{ route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda']) }}"
                   class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all text-center flex items-center justify-center gap-2 {{ $activeTab === 'agenda' ? 'bg-[#114443] text-[#d4ed31] shadow-sm' : 'text-[#64748b] hover:bg-[#e2f0ed] hover:text-[#114443]' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>4. Agenda ({{ $institution->agendas_count }})</span>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PILAR 1: ANGGOTA LEMBAGA -->
        <!-- ========================================== -->
        @if($activeTab === 'anggota')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-[#0c3837]">Buku Register Anggota & Pengurus {{ $institution->singkatan }}</h2>
                    <p class="text-xs text-[#64748b]">Daftar pengurus, jabatan, nomor SK, dan masa periode bakti</p>
                </div>
                @can('administrasi.manage')
                <a href="{{ route('administrasi.kelembagaan.members.create', ['institution' => $institution->slug]) }}" 
                   class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Anggota {{ $institution->singkatan }}</span>
                </a>
                @endcan
            </div>

            <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-xs overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#f7faf9] text-[#64748b] border-b border-[#e1ede8] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 font-bold text-center w-12">No</th>
                            <th class="py-3.5 px-4 font-bold">Nama Lengkap & NIK</th>
                            <th class="py-3.5 px-4 font-bold">Jabatan</th>
                            <th class="py-3.5 px-4 font-bold">SK Pengangkatan</th>
                            <th class="py-3.5 px-4 font-bold">Periode Bakti</th>
                            <th class="py-3.5 px-4 font-bold">Kontak / HP</th>
                            <th class="py-3.5 px-4 font-bold text-center">Status</th>
                            <th class="py-3.5 px-4 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8] text-[#0c3837]">
                        @forelse($members as $idx => $m)
                        <tr class="hover:bg-[#f7faf9] transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-[#64748b]">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#0c3837]">{{ $m->nama_lengkap }}</div>
                                <div class="text-[11px] text-[#64748b] font-mono mt-0.5">NIK: {{ $m->nik ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 font-bold text-[#114443]">{{ $m->jabatan }}</td>
                            <td class="py-3 px-4">
                                <div class="text-[#0c3837]">{{ $m->nomor_sk_pengangkatan ?? '-' }}</div>
                                <div class="text-[10px] text-[#64748b]">{{ $m->tanggal_sk ? $m->tanggal_sk->isoFormat('D MMM Y') : '' }}</div>
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-700">
                                {{ $m->periode_mulai ?? '-' }} s/d {{ $m->periode_selesai ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">{{ $m->kontak ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($m->status_aktif)
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold">Purna</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('administrasi.manage')
                                    <a href="{{ route('administrasi.kelembagaan.members.edit', ['institution' => $institution->slug, 'member' => $m->id]) }}" 
                                       title="Edit Anggota"
                                       class="p-1.5 text-slate-500 hover:text-[#114443] hover:bg-[#e2f0ed] rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('administrasi.kelembagaan.members.destroy', ['institution' => $institution->slug, 'member' => $m->id]) }}" method="POST" onsubmit="return confirm('Hapus data anggota ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Anggota" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                Belum ada anggota {{ $institution->singkatan }} terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ========================================== -->
        <!-- PILAR 2: KEPUTUSAN LEMBAGA -->
        <!-- ========================================== -->
        @if($activeTab === 'keputusan')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-[#0c3837]">Buku Register Keputusan / Ketetapan {{ $institution->singkatan }}</h2>
                    <p class="text-xs text-[#64748b]">Arsip Surat Keputusan, berita acara, dan ketetapan resmi internal lembaga</p>
                </div>
                @can('administrasi.manage')
                <a href="{{ route('administrasi.kelembagaan.decisions.create', ['institution' => $institution->slug]) }}" 
                   class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Keputusan {{ $institution->singkatan }}</span>
                </a>
                @endcan
            </div>

            <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-xs overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#f7faf9] text-[#64748b] border-b border-[#e1ede8] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 font-bold text-center w-12">No</th>
                            <th class="py-3.5 px-4 font-bold">Nomor Keputusan</th>
                            <th class="py-3.5 px-4 font-bold">Tanggal SK</th>
                            <th class="py-3.5 px-4 font-bold">Tentang / Perihal</th>
                            <th class="py-3.5 px-4 font-bold">Uraian Singkat</th>
                            <th class="py-3.5 px-4 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8] text-[#0c3837]">
                        @forelse($decisions as $idx => $d)
                        <tr class="hover:bg-[#f7faf9] transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-[#64748b]">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4 font-bold font-mono text-[#114443]">{{ $d->nomor_keputusan }}</td>
                            <td class="py-3 px-4 text-[#64748b]">{{ $d->tanggal_keputusan ? $d->tanggal_keputusan->isoFormat('D MMMM Y') : '-' }}</td>
                            <td class="py-3 px-4 font-semibold text-[#0c3837]">{{ $d->tentang }}</td>
                            <td class="py-3 px-4 text-[#64748b] max-w-xs">{{ $d->uraian_singkat ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('administrasi.manage')
                                    <a href="{{ route('administrasi.kelembagaan.decisions.edit', ['institution' => $institution->slug, 'decision' => $d->id]) }}" 
                                       title="Edit Keputusan"
                                       class="p-1.5 text-slate-500 hover:text-[#114443] hover:bg-[#e2f0ed] rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('administrasi.kelembagaan.decisions.destroy', ['institution' => $institution->slug, 'decision' => $d->id]) }}" method="POST" onsubmit="return confirm('Hapus ketetapan ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Keputusan" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada keputusan / SK {{ $institution->singkatan }} dicatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ========================================== -->
        <!-- PILAR 3: KEGIATAN LEMBAGA -->
        <!-- ========================================== -->
        @if($activeTab === 'kegiatan')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-[#0c3837]">Buku Register Kegiatan & Operasional {{ $institution->singkatan }}</h2>
                    <p class="text-xs text-[#64748b]">Dokumentasi program kerja, musyawarah, aksi lapangan, dan realisasi output</p>
                </div>
                @can('administrasi.manage')
                <a href="{{ route('administrasi.kelembagaan.activities.create', ['institution' => $institution->slug]) }}" 
                   class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Kegiatan {{ $institution->singkatan }}</span>
                </a>
                @endcan
            </div>

            <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-xs overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#f7faf9] text-[#64748b] border-b border-[#e1ede8] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 font-bold text-center w-12">No</th>
                            <th class="py-3.5 px-4 font-bold">Nama Kegiatan</th>
                            <th class="py-3.5 px-4 font-bold">Tanggal & Lokasi</th>
                            <th class="py-3.5 px-4 font-bold">Penanggung Jawab</th>
                            <th class="py-3.5 px-4 font-bold">Anggaran / Sumber</th>
                            <th class="py-3.5 px-4 font-bold">Hasil / Output</th>
                            <th class="py-3.5 px-4 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8] text-[#0c3837]">
                        @forelse($activities as $idx => $a)
                        <tr class="hover:bg-[#f7faf9] transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-[#64748b]">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-[#0c3837]">{{ $a->nama_kegiatan }}</td>
                            <td class="py-3 px-4">
                                <div class="text-[#0c3837]">{{ $a->tanggal_kegiatan ? $a->tanggal_kegiatan->isoFormat('D MMM Y') : '-' }}</div>
                                <div class="text-[11px] text-[#64748b]">{{ $a->lokasi ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-[#114443] font-semibold">{{ $a->penanggung_jawab ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#0c3837]">Rp {{ number_format($a->anggaran, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-[#64748b]">{{ $a->sumber_dana ?? 'Kas Lembaga' }}</div>
                            </td>
                            <td class="py-3 px-4 text-[#64748b] max-w-xs">{{ $a->output_hasil ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('administrasi.manage')
                                    <a href="{{ route('administrasi.kelembagaan.activities.edit', ['institution' => $institution->slug, 'activity' => $a->id]) }}" 
                                       title="Edit Kegiatan"
                                       class="p-1.5 text-slate-500 hover:text-[#114443] hover:bg-[#e2f0ed] rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('administrasi.kelembagaan.activities.destroy', ['institution' => $institution->slug, 'activity' => $a->id]) }}" method="POST" onsubmit="return confirm('Hapus catatan kegiatan ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Kegiatan" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada kegiatan {{ $institution->singkatan }} dicatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ========================================== -->
        <!-- PILAR 4: AGENDA LEMBAGA -->
        <!-- ========================================== -->
        @if($activeTab === 'agenda')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-[#0c3837]">Buku Agenda & Jadwal Kerja {{ $institution->singkatan }}</h2>
                    <p class="text-xs text-[#64748b]">Jadwal rapat, musyawarah pleno, audiensi warga, dan agenda rutin</p>
                </div>
                @can('administrasi.manage')
                <a href="{{ route('administrasi.kelembagaan.agendas.create', ['institution' => $institution->slug]) }}" 
                   class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-[#d4ed31] font-bold text-xs rounded-2xl shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Jadwalkan Agenda {{ $institution->singkatan }}</span>
                </a>
                @endcan
            </div>

            <div class="bg-white rounded-3xl border border-[#e1ede8] shadow-xs overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#f7faf9] text-[#64748b] border-b border-[#e1ede8] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4 font-bold text-center w-12">No</th>
                            <th class="py-3.5 px-4 font-bold">Waktu & Tanggal</th>
                            <th class="py-3.5 px-4 font-bold">Nama Agenda</th>
                            <th class="py-3.5 px-4 font-bold">Tempat</th>
                            <th class="py-3.5 px-4 font-bold">Peserta / Undangan</th>
                            <th class="py-3.5 px-4 font-bold">Pokok Bahasan</th>
                            <th class="py-3.5 px-4 font-bold text-center">Status</th>
                            <th class="py-3.5 px-4 font-bold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1ede8] text-[#0c3837]">
                        @forelse($agendas as $idx => $ag)
                        <tr class="hover:bg-[#f7faf9] transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-[#64748b]">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-[#0c3837]">{{ $ag->tanggal_agenda ? $ag->tanggal_agenda->isoFormat('D MMM Y') : '-' }}</div>
                                <div class="text-[10px] text-[#64748b]">{{ $ag->waktu ?? 'WIB' }}</div>
                            </td>
                            <td class="py-3 px-4 font-bold text-[#114443]">{{ $ag->nama_agenda }}</td>
                            <td class="py-3 px-4 text-slate-700">{{ $ag->tempat ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#64748b]">{{ $ag->peserta ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#64748b] max-w-xs">{{ $ag->pembahasan ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($ag->status === 'selesai')
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-bold">Selesai</span>
                                @elseif($ag->status === 'berlangsung')
                                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-[10px] font-bold">Berlangsung</span>
                                @elseif($ag->status === 'dibatalkan')
                                    <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 rounded-full text-[10px] font-bold">Dibatalkan</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-[10px] font-bold">Rencana</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('administrasi.manage')
                                    <a href="{{ route('administrasi.kelembagaan.agendas.edit', ['institution' => $institution->slug, 'agenda' => $ag->id]) }}" 
                                       title="Edit Agenda"
                                       class="p-1.5 text-slate-500 hover:text-[#114443] hover:bg-[#e2f0ed] rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('administrasi.kelembagaan.agendas.destroy', ['institution' => $institution->slug, 'agenda' => $ag->id]) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Agenda" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                Belum ada agenda kerja {{ $institution->singkatan }} dijadwalkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-layouts.app>
