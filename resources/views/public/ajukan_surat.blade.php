@extends('layouts.public')

@section('title', 'Ajukan Surat Online Mandiri')

@section('content')
    {{-- Header Banner --}}
    <section class="bg-gradient-to-br from-[#0c3837] to-[#114443] text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-block px-3 py-1 rounded-full bg-white/15 text-white text-xs font-bold uppercase tracking-wider mb-3">
                        Pelayanan Tanpa Harus Ke Kantor Desa
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Formulir Pengajuan Surat Online</h1>
                    <p class="text-white/90 text-sm mt-2 leading-relaxed">
                        Ajukan berbagai surat keterangan dan pengantar desa langsung dari rumah. Setelah diverifikasi dan diterbitkan oleh petugas, dokumen surat resmi ber-QR Code dapat langsung Anda unduh dalam format PDF.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('public.lacak-surat') }}"
                       class="px-5 py-2.5 rounded-full bg-white text-[#0c3837] hover:bg-[#f7faf9] text-xs font-bold transition-all shadow-sm">
                        Lacak Status & Unduh Surat
                    </a>
                    <a href="{{ route('public.layanan') }}"
                       class="px-5 py-2.5 rounded-full bg-white/15 hover:bg-white/25 text-white border border-white/25 text-xs font-bold transition-all">
                        Katalog Layanan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($errors->any())
            <div class="mb-6 p-5 rounded-3xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1.5">
                <p class="font-bold text-sm">Mohon periksa kembali isian formulir Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Kolom Kiri: Pilihan Cepat Jenis Surat Populer & Persyaratan (4 Cols) --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Daftar Surat yang Sering Diajukan Masyarakat --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#10b981]">Layanan Terpopuler</span>
                        <h2 class="text-base font-extrabold text-[#0c3837] mt-0.5">Pilih Jenis Surat</h2>
                        <p class="text-xs text-[#64748b] mt-0.5">Klik jenis surat yang sering diajukan masyarakat di bawah ini:</p>
                    </div>

                    <div class="space-y-2" id="quick-template-list">
                        @foreach($templates as $tpl)
                            @php
                                $isSelected = old('surat_template_id', $selectedTemplate?->id) == $tpl->id;
                            @endphp
                            <button type="button"
                                    onclick="selectLetterTemplate({{ $tpl->id }})"
                                    data-tpl-btn="{{ $tpl->id }}"
                                    class="w-full text-left p-3.5 rounded-2xl border transition-all cursor-pointer flex items-center justify-between gap-3 {{ $isSelected ? 'bg-[#f0fdf4] border-[#10b981] text-[#0c3837]' : 'bg-[#f7faf9] border-[#e1ede8] text-[#0c3837] hover:border-[#10b981]' }}">
                                <div class="min-w-0">
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-white border border-[#e1ede8] text-[10px] font-extrabold text-[#10b981] mb-1">
                                        {{ $tpl->kode_surat }}
                                    </span>
                                    <p class="text-xs font-bold text-[#0c3837] leading-snug">{{ $tpl->nama_surat }}</p>
                                </div>
                                <span class="text-xs font-bold text-[#10b981] shrink-0">&rarr;</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Box Persyaratan & Estimasi sesuai Surat yang Dipilih --}}
                <div class="bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-xs space-y-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#10b981]">Informasi Layanan</span>
                        <h3 id="info-nama-surat" class="text-sm font-extrabold text-[#0c3837] mt-0.5">
                            {{ $selectedTemplate?->nama_surat ?? 'Pilih Jenis Surat' }}
                        </h3>
                        <p id="info-deskripsi-surat" class="text-xs text-[#64748b] mt-1 leading-relaxed">
                            {{ $selectedTemplate?->deskripsi ?? '' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-[#e1ede8] text-xs">
                        <div class="p-3 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                            <span class="text-[10px] font-bold uppercase text-[#64748b] block">Estimasi Proses</span>
                            <span id="info-estimasi" class="font-extrabold text-[#0c3837] mt-0.5 block">{{ $selectedTemplate?->estimasi_proses ?? '1 Hari Kerja' }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                            <span class="text-[10px] font-bold uppercase text-[#64748b] block">Biaya Layanan</span>
                            <span id="info-biaya" class="font-extrabold text-[#10b981] mt-0.5 block">{{ $selectedTemplate?->biaya ?? 'Gratis / Rp 0' }}</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-[#e1ede8]">
                        <p class="text-xs font-bold text-[#0c3837] mb-2">Persyaratan Dokumen Pendukung:</p>
                        <ul id="info-persyaratan-list" class="list-disc list-inside text-xs text-[#475569] space-y-1.5">
                            @if(!empty($selectedTemplate?->persyaratan_json))
                                @foreach($selectedTemplate->persyaratan_json as $syarat)
                                    <li>{{ $syarat }}</li>
                                @endforeach
                            @else
                                <li>Fotokopi / Foto KTP Elektronik Pemohon</li>
                                <li>Fotokopi / Foto Kartu Keluarga (KK)</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Formulir Utama Pengajuan Surat Online (8 Cols) --}}
            <div class="lg:col-span-8">
                <form action="{{ route('public.ajukan-surat.store') }}" method="POST" enctype="multipart/form-data"
                      class="bg-white rounded-3xl border border-[#e1ede8] p-6 sm:p-8 shadow-xs space-y-8">
                    @csrf

                    {{-- Bagian 1: Identitas Warga Pemohon --}}
                    <div class="space-y-5">
                        <div class="pb-3 border-b border-[#e1ede8] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#10b981]">Langkah 1 dari 3</span>
                                <h2 class="text-lg font-extrabold text-[#0c3837]">Identitas Warga Pemohon</h2>
                            </div>
                            <span class="text-[11px] text-[#64748b]">Masukkan 16 digit NIK untuk mengisi otomatis jika terdaftar</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- NIK + Tombol Cek Otomatis --}}
                            <div class="sm:col-span-2">
                                <label for="pemohon_nik" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                    Nomor Induk Kependudukan (NIK 16 Digit) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col sm:flex-row gap-2.5">
                                    <input type="text" id="pemohon_nik" name="pemohon_nik" required maxlength="16" pattern="[0-9]{16}"
                                           value="{{ old('pemohon_nik', $prefill['nik'] ?? '') }}"
                                           placeholder="Contoh: 3202111505800001"
                                           class="flex-1 px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-mono font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                                    <button type="button" onclick="lookupNikPenduduk()"
                                            id="btn-cek-nik"
                                            class="px-5 py-3 bg-[#0c3837] hover:bg-[#114443] text-white text-xs font-bold rounded-2xl transition-all shrink-0 cursor-pointer">
                                        Cek NIK Otomatis
                                    </button>
                                </div>
                                <p id="nik-lookup-feedback" class="text-[11px] text-[#64748b] mt-1.5 hidden"></p>
                            </div>

                            {{-- Nama Lengkap --}}
                            <div>
                                <label for="pemohon_nama" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                    Nama Lengkap Sesuai KTP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pemohon_nama" name="pemohon_nama" required
                                       value="{{ old('pemohon_nama', $prefill['nama_lengkap'] ?? '') }}"
                                       placeholder="Masukkan nama lengkap Anda"
                                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                            </div>

                            {{-- Nomor KK --}}
                            <div>
                                <label for="pemohon_no_kk" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                    Nomor Kartu Keluarga (No. KK) <span class="text-[#94a3b8] font-normal">(Opsional)</span>
                                </label>
                                <input type="text" id="pemohon_no_kk" name="pemohon_no_kk" maxlength="16"
                                       value="{{ old('pemohon_no_kk', $prefill['no_kk'] ?? '') }}"
                                       placeholder="16 digit Nomor KK"
                                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-mono text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                            </div>

                            {{-- Nomor HP / WhatsApp --}}
                            <div>
                                <label for="pemohon_phone" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                    Nomor HP / WhatsApp Aktif <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pemohon_phone" name="pemohon_phone" required
                                       value="{{ old('pemohon_phone', $prefill['phone'] ?? '') }}"
                                       placeholder="Contoh: 081234567890"
                                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                            </div>

                            {{-- Alamat Lengkap Domisili --}}
                            <div>
                                <label for="pemohon_alamat" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                    Alamat Lengkap Domisili (Dusun / RT / RW) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="pemohon_alamat" name="pemohon_alamat" required
                                       value="{{ old('pemohon_alamat', $prefill['alamat'] ?? '') }}"
                                       placeholder="Contoh: Dusun I RT 001 / RW 002 Desa {{ $desa->nama_desa ?? 'Balam Sempurna' }}"
                                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                            </div>
                        </div>
                    </div>

                    {{-- Bagian 2: Jenis Surat & Rincian Data Surat --}}
                    <div class="space-y-5">
                        <div class="pb-3 border-b border-[#e1ede8]">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#10b981]">Langkah 2 dari 3</span>
                            <h2 class="text-lg font-extrabold text-[#0c3837]">Rincian Surat yang Diajukan</h2>
                        </div>

                        {{-- Pilih Jenis Surat --}}
                        <div>
                            <label for="surat_template_id" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                Jenis Layanan Surat <span class="text-red-500">*</span>
                            </label>
                            <select id="surat_template_id" name="surat_template_id" required
                                    onchange="selectLetterTemplate(this.value)"
                                    class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                                @foreach($templates as $tpl)
                                    <option value="{{ $tpl->id }}" {{ old('surat_template_id', $selectedTemplate?->id) == $tpl->id ? 'selected' : '' }}>
                                        [{{ $tpl->kode_surat }}] {{ $tpl->nama_surat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dynamic Fields Sesuai Jenis Surat (Di-render otomatis via JS) --}}
                        <div id="dynamic-fields-wrapper" class="p-5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-[#0c3837]" id="dynamic-section-title">
                                    Data Rincian {{ $selectedTemplate?->nama_surat ?? 'Surat' }}
                                </span>
                                <span class="text-[11px] font-semibold text-[#10b981]">Isian otomatis tercetak pada surat</span>
                            </div>
                            <div id="dynamic-fields-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Diisi oleh fungsi renderDynamicFields() --}}
                            </div>
                        </div>

                        {{-- Keperluan / Tujuan Penggunaan Surat --}}
                        <div>
                            <label for="keperluan" class="block text-xs font-bold text-[#0c3837] mb-1.5">
                                Keperluan / Tujuan Penggunaan Surat <span class="text-red-500">*</span>
                            </label>
                            <textarea id="keperluan" name="keperluan" rows="3" required
                                      placeholder="Jelaskan secara singkat keperluan surat ini, misalnya: Persyaratan melamar pekerjaan di PT. Perkebunan Nusantara / Pengajuan KUR Bank BRI / Persyaratan Beasiswa Sekolah..."
                                      class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0c3837] focus:outline-none focus:border-[#10b981]">{{ old('keperluan') }}</textarea>
                        </div>
                    </div>

                    {{-- Bagian 3: Unggah Lampiran Persyaratan --}}
                    <div class="space-y-4">
                        <div class="pb-3 border-b border-[#e1ede8]">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#10b981]">Langkah 3 dari 3</span>
                            <h2 class="text-lg font-extrabold text-[#0c3837]">Unggah Berkas Persyaratan (KTP / KK / Pengantar)</h2>
                        </div>

                        <div class="p-6 border-2 border-dashed border-[#c9dfd8] rounded-2xl bg-[#f7faf9] text-center hover:border-[#10b981] transition-colors cursor-pointer"
                             onclick="document.getElementById('dokumen-upload').click()">
                            <p class="text-sm font-bold text-[#0c3837]">Klik di sini untuk memilih foto / dokumen persyaratan</p>
                            <p class="text-xs text-[#64748b] mt-1">Unggah Foto KTP, Kartu Keluarga (KK), atau Surat Pengantar RT/RW (Format JPG, PNG, atau PDF, maks. 5 MB per berkas)</p>
                            <span class="inline-block mt-3 px-4 py-1.5 rounded-full bg-white border border-[#e1ede8] text-xs font-bold text-[#10b981]">
                                Pilih Berkas Pendukung
                            </span>
                        </div>

                        <input type="file" id="dokumen-upload" name="dokumen[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden" onchange="previewUploadedFiles(this)">

                        <div id="uploaded-files-preview" class="space-y-2 hidden"></div>
                    </div>

                    {{-- Informasi & Tombol Kirim --}}
                    <div class="pt-4 border-t border-[#e1ede8] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-xs text-[#64748b] leading-relaxed">
                            Setelah dikirim, Anda akan mendapatkan <strong>Nomor Registrasi Pengajuan (PGJ-...)</strong> untuk memantau status dan mengunduh PDF surat resmi dari rumah.
                        </p>
                        <button type="submit"
                                class="px-8 py-4 bg-[#10b981] hover:bg-[#059669] text-white text-sm font-extrabold rounded-full shadow-md transition-all shrink-0 cursor-pointer">
                            Kirim Permohonan Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $templatesPayload = $templates->mapWithKeys(function ($t) {
            return [
                $t->id => [
                    'id' => $t->id,
                    'kode_surat' => $t->kode_surat,
                    'nama_surat' => $t->nama_surat,
                    'deskripsi' => $t->deskripsi,
                    'estimasi_proses' => $t->estimasi_proses ?? '1 Hari Kerja',
                    'biaya' => $t->biaya ?? 'Gratis / Rp 0',
                    'persyaratan' => $t->persyaratan_json ?? ['Fotokopi KTP Elektronik Pemohon', 'Fotokopi Kartu Keluarga (KK)'],
                    'schema_fields' => $t->schema_fields_json ?? [],
                ],
            ];
        });
        $oldFormData = old('form_data', []);
    @endphp

    @push('scripts')
    <script>
        const TEMPLATES_DATA = @json($templatesPayload);
        const OLD_FORM_DATA = @json($oldFormData);

        function selectLetterTemplate(templateId) {
            const tpl = TEMPLATES_DATA[templateId];
            if (!tpl) return;

            // Sinkronkan dropdown
            const selectEl = document.getElementById('surat_template_id');
            if (selectEl && selectEl.value != String(templateId)) {
                selectEl.value = String(templateId);
            }

            // Highlight tombol di sidebar kiri
            document.querySelectorAll('[data-tpl-btn]').forEach(btn => {
                const isMatch = btn.getAttribute('data-tpl-btn') === String(templateId);
                btn.className = 'w-full text-left p-3.5 rounded-2xl border transition-all cursor-pointer flex items-center justify-between gap-3 ' +
                    (isMatch
                        ? 'bg-[#f0fdf4] border-[#10b981] text-[#0c3837]'
                        : 'bg-[#f7faf9] border-[#e1ede8] text-[#0c3837] hover:border-[#10b981]');
            });

            // Perbarui informasi layanan & persyaratan di sebelah kiri
            document.getElementById('info-nama-surat').textContent = tpl.nama_surat;
            document.getElementById('info-deskripsi-surat').textContent = tpl.deskripsi || '';
            document.getElementById('info-estimasi').textContent = tpl.estimasi_proses || '1 Hari Kerja';
            document.getElementById('info-biaya').textContent = tpl.biaya || 'Gratis';

            const syaratList = document.getElementById('info-persyaratan-list');
            syaratList.innerHTML = '';
            (tpl.persyaratan || []).forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                syaratList.appendChild(li);
            });

            // Render field dinamis sesuai jenis surat
            renderDynamicFields(tpl);
        }

        function renderDynamicFields(tpl) {
            const wrapper = document.getElementById('dynamic-fields-wrapper');
            const container = document.getElementById('dynamic-fields-container');
            const titleEl = document.getElementById('dynamic-section-title');

            titleEl.textContent = 'Data Rincian ' + tpl.nama_surat;
            container.innerHTML = '';

            const fields = tpl.schema_fields || [];
            if (fields.length === 0) {
                wrapper.classList.add('hidden');
                return;
            }

            wrapper.classList.remove('hidden');

            fields.forEach(field => {
                const div = document.createElement('div');
                const savedVal = OLD_FORM_DATA[field.name] || '';
                const reqMark = field.required ? '<span class="text-red-500">*</span>' : '<span class="text-[#94a3b8] font-normal">(Opsional)</span>';
                const reqAttr = field.required ? 'required' : '';

                let inputHtml = '';
                if (field.type === 'select' && Array.isArray(field.options)) {
                    const opts = field.options.map(opt => {
                        const selected = (savedVal === opt) ? 'selected' : '';
                        return `<option value="${opt}" ${selected}>${opt}</option>`;
                    }).join('');
                    inputHtml = `
                        <select name="form_data[${field.name}]" ${reqAttr}
                                class="w-full px-3.5 py-2.5 bg-white border border-[#e1ede8] rounded-xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                            ${opts}
                        </select>
                    `;
                } else {
                    const inputType = field.type === 'date' ? 'date' : 'text';
                    inputHtml = `
                        <input type="${inputType}" name="form_data[${field.name}]" value="${savedVal}" ${reqAttr}
                               placeholder="${field.placeholder || ''}"
                               class="w-full px-3.5 py-2.5 bg-white border border-[#e1ede8] rounded-xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    `;
                }

                div.innerHTML = `
                    <label class="block text-xs font-bold text-[#0c3837] mb-1">
                        ${field.label} ${reqMark}
                    </label>
                    ${inputHtml}
                `;
                container.appendChild(div);
            });
        }

        async function lookupNikPenduduk() {
            const nikInput = document.getElementById('pemohon_nik');
            const feedback = document.getElementById('nik-lookup-feedback');
            const btn = document.getElementById('btn-cek-nik');
            const nik = (nikInput.value || '').replace(/[^0-9]/g, '');

            nikInput.value = nik;
            feedback.classList.remove('hidden');

            if (nik.length !== 16) {
                feedback.className = 'text-[11px] font-semibold text-red-600 mt-1.5';
                feedback.textContent = 'Masukkan tepat 16 digit angka NIK Anda terlebih dahulu.';
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mencari Data...';
            feedback.className = 'text-[11px] text-[#64748b] mt-1.5';
            feedback.textContent = 'Memeriksa NIK pada Buku Induk Penduduk Desa...';

            try {
                const res = await fetch(`{{ route('public.ajukan-surat.cek-nik') }}?nik=${encodeURIComponent(nik)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();

                if (json.found && json.data) {
                    if (json.data.nama_lengkap) document.getElementById('pemohon_nama').value = json.data.nama_lengkap;
                    if (json.data.no_kk) document.getElementById('pemohon_no_kk').value = json.data.no_kk;
                    if (json.data.phone && !document.getElementById('pemohon_phone').value) {
                        document.getElementById('pemohon_phone').value = json.data.phone;
                    }
                    if (json.data.alamat) document.getElementById('pemohon_alamat').value = json.data.alamat;

                    feedback.className = 'text-[11px] font-bold text-[#10b981] mt-1.5';
                    feedback.textContent = 'Data ditemukan (' + json.source + '). Identitas pemohon berhasil diisi otomatis.';
                } else {
                    feedback.className = 'text-[11px] font-semibold text-amber-700 mt-1.5';
                    feedback.textContent = json.message || 'NIK belum terdaftar di Buku Induk, silakan lengkapi nama dan alamat Anda secara manual.';
                }
            } catch (e) {
                feedback.className = 'text-[11px] text-[#64748b] mt-1.5';
                feedback.textContent = 'Silakan lengkapi nama lengkap dan alamat domisili Anda pada kolom di bawah.';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Cek NIK Otomatis';
            }
        }

        function previewUploadedFiles(input) {
            const container = document.getElementById('uploaded-files-preview');
            container.innerHTML = '';
            if (!input.files || input.files.length === 0) {
                container.classList.add('hidden');
                return;
            }
            container.classList.remove('hidden');
            Array.from(input.files).forEach(file => {
                const sizeKb = (file.size / 1024).toFixed(1);
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837]';
                row.innerHTML = `
                    <span class="font-bold truncate">${file.name}</span>
                    <span class="text-[#64748b] shrink-0 ml-3">${sizeKb} KB</span>
                `;
                container.appendChild(row);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const initialId = document.getElementById('surat_template_id')?.value;
            if (initialId) {
                selectLetterTemplate(initialId);
            }
        });
    </script>
    @endpush
@endsection

