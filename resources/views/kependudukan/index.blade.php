<x-layouts.app>
    <x-slot:title>Buku Induk Penduduk</x-slot:title>

    <!-- Top Workspace Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Kependudukan</span>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Buku Induk</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Buku Induk Kependudukan Desa</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Master data warga terpadu dengan perlindungan privasi data sensitif meja pelayanan</p>
        </div>

        <div class="flex items-center gap-3">
            @can('kependudukan.view')
            <a href="{{ route('kependudukan.duplicates') }}" 
               id="btn-nik-scanner"
               class="px-4 py-2.5 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Cek NIK Ganda</span>
            </a>
            @endcan

            @can('kependudukan.create')
            <a href="{{ route('kependudukan.create') }}" 
               class="px-5 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Daftarkan Warga Baru</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- 1. Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center font-bold text-lg shadow-inner">
                👥
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Total Jiwa</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ number_format($stats['total']) }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#e2f48f] text-[#0c3837] flex items-center justify-center font-bold text-lg shadow-inner">
                ⚖️
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Laki-Laki / Perempuan</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ number_format($stats['laki_laki']) }} <span class="text-xs font-normal text-slate-400">/</span> {{ number_format($stats['perempuan']) }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#0c3837] text-[#d4ed31] flex items-center justify-center font-bold text-lg shadow-inner">
                🏠
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Kepala Keluarga</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ number_format($stats['kk']) }}</span>
            </div>
        </x-card>

        <x-card class="p-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#edf5f2] text-[#4fa394] flex items-center justify-center font-bold text-lg shadow-inner">
                📦
            </div>
            <div>
                <span class="text-[11px] text-[#64748b] font-medium block">Pindah / Meninggal</span>
                <span class="text-xl font-extrabold text-[#0c3837]">{{ number_format($stats['pindah']) }} <span class="text-xs font-normal text-slate-400">/</span> {{ number_format($stats['meninggal']) }}</span>
            </div>
        </x-card>
    </div>

    <!-- 2. Main Data Section -->
    <x-card class="space-y-4">
        <!-- Header Controls: Privacy Mode Toggle + Search & Filters -->
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between pb-4 border-b border-[#e1ede8]" 
             x-data="{ 
                 privacy: sessionStorage.getItem('digides_privacy_mode') === 'true',
                 togglePrivacy() {
                     this.privacy = !this.privacy;
                     sessionStorage.setItem('digides_privacy_mode', this.privacy);
                     document.querySelectorAll('.nik-field').forEach(el => {
                         el.textContent = this.privacy ? el.dataset.masked : el.dataset.plain;
                     });
                 },
                 init() {
                     if (this.privacy) {
                         document.querySelectorAll('.nik-field').forEach(el => {
                             el.textContent = el.dataset.masked;
                         });
                     }
                 }
             }">
            
            <!-- Privacy Toggle Switch -->
            <div class="flex items-center gap-3 bg-[#f7faf9] px-4 py-2 rounded-2xl border border-[#e1ede8]">
                <button type="button"
                        id="privacy-toggle"
                        @click="togglePrivacy()" 
                        :class="privacy ? 'bg-[#10b981]' : 'bg-slate-300'"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                        role="switch"
                        :aria-checked="privacy"
                        title="Klik untuk aktifkan/nonaktifkan Privacy Mode meja pelayanan">
                    <span :class="privacy ? 'translate-x-5' : 'translate-x-0'" 
                          class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                </button>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-[#0c3837]" x-text="privacy ? '🔒 Privacy Mode Aktif' : '👁 Mode Terbuka'"></span>
                    <span class="text-[10px] text-[#64748b]" x-text="privacy ? 'NIK disamarkan saat menghadap warga' : 'NIK terlihat lengkap'"></span>
                </div>
            </div>

            <!-- Search & Filters -->
            <form method="GET" action="{{ route('kependudukan.index') }}" class="flex flex-wrap gap-2.5 items-center w-full lg:w-auto" id="filter-form">
                <div class="relative w-full sm:w-56">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIK, KK..." 
                           id="search-penduduk"
                           class="w-full pl-9 pr-3.5 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="dusun" class="px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" id="filter-dusun" onchange="this.form.submit()">
                    <option value="">Semua Dusun</option>
                    @foreach($dusunList as $dusun)
                        <option value="{{ $dusun }}" @selected(request('dusun') === $dusun)>{{ $dusun }}</option>
                    @endforeach
                </select>

                <select name="jenis_kelamin" class="px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" id="filter-jk" onchange="this.form.submit()">
                    <option value="">Semua Gender</option>
                    <option value="L" @selected(request('jenis_kelamin') === 'L')>Laki-Laki</option>
                    <option value="P" @selected(request('jenis_kelamin') === 'P')>Perempuan</option>
                </select>

                <select name="kategori_usia" class="px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" id="filter-usia" onchange="this.form.submit()">
                    <option value="">Semua Usia</option>
                    <option value="balita" @selected(request('kategori_usia') === 'balita')>Balita (≤5 th)</option>
                    <option value="sekolah" @selected(request('kategori_usia') === 'sekolah')>Usia Sekolah (6-18 th)</option>
                    <option value="produktif" @selected(request('kategori_usia') === 'produktif')>Produktif (19-59 th)</option>
                    <option value="lansia" @selected(request('kategori_usia') === 'lansia')>Lansia (≥60 th)</option>
                </select>

                <select name="status_penduduk" class="px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]" id="filter-status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="tetap" @selected(request('status_penduduk') === 'tetap')>Tetap</option>
                    <option value="sementara" @selected(request('status_penduduk') === 'sementara')>Sementara</option>
                    <option value="pindah" @selected(request('status_penduduk') === 'pindah')>Pindah</option>
                    <option value="meninggal" @selected(request('status_penduduk') === 'meninggal')>Meninggal</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#114443] hover:bg-[#0c3837] text-white rounded-full text-xs font-bold transition-all cursor-pointer" id="btn-filter-apply">
                    Terapkan
                </button>

                @if(request()->anyFilled(['search','dusun','jenis_kelamin','kategori_usia','status_penduduk']))
                    <a href="{{ route('kependudukan.index') }}" class="px-2.5 py-2 text-xs text-rose-600 hover:underline font-semibold" id="btn-filter-reset">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Residents Table -->
        <div class="overflow-x-auto rounded-2xl border border-[#e1ede8]">
            <table class="w-full text-left text-xs" id="table-penduduk">
                <thead class="bg-[#f7faf9] text-[#64748b] font-bold uppercase border-b border-[#e1ede8]">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">NIK (Nomor Induk)</th>
                        <th class="py-3.5 px-4">Nama Lengkap & Profil</th>
                        <th class="py-3.5 px-4">Tempat / Tgl Lahir</th>
                        <th class="py-3.5 px-4">Dusun / RT-RW</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Sumber Data</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8]">
                    @forelse($penduduks as $index => $penduduk)
                    <tr class="hover:bg-[#f7faf9] transition-colors">
                        <td class="py-3.5 px-4 text-center text-[#64748b] font-medium">
                            {{ $penduduks->firstItem() + $index }}
                        </td>

                        <!-- NIK Field (with masking attributes) -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="nik-field font-mono font-bold text-[#0c3837] tracking-wider text-xs bg-[#f0f7f5] px-2.5 py-1 rounded-lg border border-[#e1ede8]"
                                  data-plain="{{ $penduduk->nik }}"
                                  data-masked="{{ $penduduk->masked_nik }}">
                                {{ $penduduk->nik }}
                            </span>
                            <span class="text-[10px] text-[#64748b] block mt-0.5">KK: {{ $penduduk->no_kk }}</span>
                        </td>

                        <!-- Name & Basic Info -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <a href="{{ route('kependudukan.show', $penduduk) }}" class="font-bold text-[#0c3837] hover:text-[#10b981] block text-sm transition-colors">
                                {{ $penduduk->nama_lengkap }}
                            </a>
                            <div class="text-[11px] text-[#64748b] flex items-center gap-1.5 mt-0.5">
                                <span>{{ $penduduk->jenis_kelamin_label }}</span>
                                <span>•</span>
                                <span>{{ $penduduk->umur }} th ({{ $penduduk->kategori_usia }})</span>
                                <span>•</span>
                                <span>{{ $penduduk->agama }}</span>
                            </div>
                        </td>

                        <!-- Birth Info -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-medium text-[#0f172a] block">{{ $penduduk->tempat_lahir }}</span>
                            <span class="text-[11px] text-[#64748b]">{{ $penduduk->tanggal_lahir->format('d M Y') }}</span>
                        </td>

                        <!-- Address Info -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-medium text-[#0f172a] block">{{ $penduduk->dusun ?? 'Pusat Desa' }}</span>
                            <span class="text-[11px] text-[#64748b]">RT {{ $penduduk->rt }} / RW {{ $penduduk->rw }}</span>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @php
                                $statusVariant = match($penduduk->status_penduduk) {
                                    'tetap' => 'emerald',
                                    'sementara' => 'amber',
                                    'pindah' => 'slate',
                                    'meninggal' => 'rose',
                                    default => 'slate'
                                };
                            @endphp
                            <x-badge :variant="$statusVariant">
                                {{ ucfirst($penduduk->status_penduduk) }}
                            </x-badge>
                        </td>

                        <!-- Data Source -->
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="text-[11px] font-medium text-[#64748b] inline-flex items-center gap-1 bg-[#f7faf9] px-2 py-0.5 rounded-md border border-[#e1ede8]">
                                {{ match($penduduk->sumber_data) {
                                    'prodeskel' => '🌐 Prodeskel',
                                    'manual' => '✏️ Input Staff',
                                    'migrasi_legacy' => '📦 Migrasi DB',
                                    default => $penduduk->sumber_data
                                } }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('kependudukan.show', $penduduk) }}" 
                                   id="btn-show-{{ $penduduk->id }}"
                                   class="p-2 text-[#114443] hover:bg-[#e2f0ed] rounded-xl transition-colors" 
                                   title="Lihat Detail & Berkas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @can('kependudukan.edit')
                                <a href="{{ route('kependudukan.edit', $penduduk) }}" 
                                   id="btn-edit-{{ $penduduk->id }}"
                                   class="p-2 text-[#114443] hover:bg-[#e2f0ed] rounded-xl transition-colors" 
                                   title="Edit Data Warga">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endcan

                                @can('kependudukan.delete')
                                <form method="POST" action="{{ route('kependudukan.destroy', $penduduk) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data warga {{ $penduduk->nama_lengkap }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            id="btn-delete-{{ $penduduk->id }}"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" 
                                            title="Hapus Data">
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
                        <td colspan="8" class="py-12 text-center text-[#64748b]">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-semibold block text-sm">Tidak ada data warga yang sesuai.</span>
                            <span class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter yang digunakan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($penduduks->hasPages())
        <div class="pt-2">
            {{ $penduduks->links() }}
        </div>
        @endif
    </x-card>
</x-layouts.app>
