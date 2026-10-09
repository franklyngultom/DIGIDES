<x-layouts.app title="Pelayanan Surat Walk-In (Front Desk)">
    <div class="space-y-6 max-w-5xl mx-auto">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#114443]">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('persuratan.arsip.index') }}" class="hover:text-[#114443]">Persuratan</a>
                    <span>/</span>
                    <span class="text-[#0c3837] font-semibold">Pelayanan Surat Walk-In</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">Pelayanan Surat Walk-In (Front Desk)</h1>
                <p class="text-xs text-[#64748b] mt-0.5">Penerbitan surat keterangan dinas langsung untuk warga pemohon yang datang ke kantor desa</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('persuratan.antrean.index') }}" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#0c3837] hover:bg-[#e2f0ed] text-xs font-bold rounded-full shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Antrean Online</span>
                </a>
                <a href="{{ route('persuratan.arsip.index') }}" class="px-4 py-2.5 bg-white border border-[#e1ede8] text-[#0c3837] hover:bg-[#e2f0ed] text-xs font-bold rounded-full shadow-xs transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Buku Register Arsip</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('persuratan.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Form Input -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Template Surat -->
                    <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                        <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center text-xs font-black">1</span>
                            <span>Pilih Jenis Surat Layanan</span>
                        </h2>

                        <div>
                            <label for="template_id" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jenis Dokumen Surat <span class="text-rose-500">*</span></label>
                            <select name="template_id" id="template_id" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                                <option value="">-- Pilih Format Surat --</option>
                                @foreach($templates as $tmpl)
                                    <option value="{{ $tmpl->id }}" {{ old('template_id') == $tmpl->id ? 'selected' : '' }}>
                                        [{{ $tmpl->kode_surat }}] {{ $tmpl->nama_surat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Pilih Penduduk -->
                    <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                        <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center text-xs font-black">2</span>
                            <span>Cari Data Penduduk Pemohon</span>
                        </h2>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] mb-1.5">Ketik NIK atau Nama Penduduk</label>
                            <input type="text" id="live_search_penduduk" placeholder="Ketik minimal 3 karakter untuk mencari..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-medium text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                            
                            <input type="hidden" name="penduduk_id" id="selected_penduduk_id" value="{{ old('penduduk_id') }}" required>
                        </div>

                        <!-- Dropdown Hasil Pencarian -->
                        <div id="search_results" class="space-y-2 hidden max-h-48 overflow-y-auto"></div>

                        <!-- Info Warga Terpilih -->
                        <div id="selected_info" class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] text-xs space-y-1 hidden">
                            <div class="font-bold text-[#0c3837]" id="info_nama"></div>
                            <div class="text-[11px] text-[#64748b]">NIK: <span id="info_nik" class="font-mono font-bold text-[#0c3837]"></span></div>
                            <div class="text-[11px] text-[#64748b]">Alamat: <span id="info_alamat"></span></div>
                        </div>
                    </div>

                    <!-- Keperluan -->
                    <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                        <h2 class="text-sm font-bold text-[#0c3837] pb-3 border-b border-[#e1ede8] flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-[#e2f0ed] text-[#0c3837] flex items-center justify-center text-xs font-black">3</span>
                            <span>Keperluan & Keterangan Surat</span>
                        </h2>

                        <div>
                            <label for="keperluan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keperluan Pembuatan Surat <span class="text-rose-500">*</span></label>
                            <textarea name="keperluan" id="keperluan" rows="3" required placeholder="Contoh: Persyaratan pendaftaran sekolah / administrasi perbankan..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:outline-none focus:border-[#10b981]">{{ old('keperluan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Summary & Action -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-[#0c3837] text-white rounded-3xl p-6 shadow-sm space-y-4">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-[#d4ed31] pb-2 border-b border-white/10 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Informasi Penerbitan Surat</span>
                        </h2>

                        <div class="space-y-3 text-xs">
                            <p class="text-white/80 leading-relaxed">
                                Penerbitan melalui modul Front Desk ini akan:
                            </p>
                            <ul class="space-y-2 text-white/90 list-disc list-inside text-[11px]">
                                <li>Memberikan penomoran resmi secara berurutan (sequential)</li>
                                <li>Menghasilkan berkas digital PDF surat resmi desa</li>
                                <li>Mencatatkannya secara otomatis ke <strong>Buku Ekspedisi</strong></li>
                                <li>Mencatatkannya secara otomatis ke <strong>Buku Agenda Surat Keluar</strong></li>
                            </ul>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-3.5 bg-[#10b981] hover:bg-[#059669] text-white text-xs font-black rounded-2xl flex items-center justify-center gap-2 transition-all shadow-md cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Terbitkan Surat Sekarang</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const searchInput = document.getElementById('live_search_penduduk');
        const resultsBox = document.getElementById('search_results');
        const selectedIdInput = document.getElementById('selected_penduduk_id');
        const selectedInfo = document.getElementById('selected_info');

        let debounceTimer;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const term = this.value.trim();

                if (term.length < 2) {
                    resultsBox.classList.add('hidden');
                    resultsBox.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('persuratan.search-penduduk') }}?term=${encodeURIComponent(term)}`)
                        .then(res => res.json())
                        .then(data => {
                            resultsBox.innerHTML = '';
                            if (data.length === 0) {
                                resultsBox.innerHTML = '<div class="p-3 text-xs text-[#94a3b8] bg-[#f7faf9] rounded-xl">Tidak ditemukan penduduk dengan data tersebut.</div>';
                                resultsBox.classList.remove('hidden');
                                return;
                            }

                            data.forEach(p => {
                                const el = document.createElement('div');
                                el.className = 'p-3 rounded-xl bg-[#f7faf9] hover:bg-[#e2f0ed] border border-[#e1ede8] cursor-pointer text-xs transition-colors';
                                el.innerHTML = `<div class="font-bold text-[#0c3837]">${p.nama_lengkap}</div><div class="text-[11px] text-[#64748b]">NIK: ${p.nik} &bull; ${p.alamat_lengkap || '-'}</div>`;
                                el.addEventListener('click', () => {
                                    selectedIdInput.value = p.id;
                                    document.getElementById('info_nama').textContent = p.nama_lengkap;
                                    document.getElementById('info_nik').textContent = p.nik;
                                    document.getElementById('info_alamat').textContent = p.alamat_lengkap || '-';
                                    selectedInfo.classList.remove('hidden');
                                    resultsBox.classList.add('hidden');
                                    searchInput.value = '';
                                });
                                resultsBox.appendChild(el);
                            });
                            resultsBox.classList.remove('hidden');
                        })
                        .catch(() => {});
                }, 300);
            });
        }
    </script>
</x-layouts.app>
