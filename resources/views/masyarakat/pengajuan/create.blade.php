@extends('layouts.masyarakat')

@section('title', 'Ajukan Surat Online')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-[#64748b]">
        <a href="{{ route('masyarakat.dashboard') }}" class="hover:text-[#10b981]">Dashboard</a>
        <span>/</span>
        <a href="{{ route('masyarakat.pengajuan.index') }}" class="hover:text-[#10b981]">Pengajuan</a>
        <span>/</span>
        <span class="text-[#0c3837] font-semibold">Ajukan Baru</span>
    </nav>

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-black text-[#0c3837]">Formulir Pengajuan Surat</h1>
        <p class="text-xs text-[#64748b] mt-1">Isi formulir berikut untuk mengajukan permohonan surat secara online kepada Pemerintah Desa.</p>
    </div>

    {{-- Applicant Info Card --}}
    <div class="bg-gradient-to-r from-[#0c3837] to-[#114443] rounded-2xl p-5 text-white">
        <p class="text-[10px] uppercase font-bold text-[#8bc3b8] mb-2">Data Pemohon (Otomatis dari Profil Anda)</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div>
                <span class="block text-[11px] text-[#8bc3b8]">Nama Lengkap</span>
                <span class="font-bold">{{ $profile->nama_lengkap }}</span>
            </div>
            <div>
                <span class="block text-[11px] text-[#8bc3b8]">NIK</span>
                <span class="font-bold font-mono">{{ $profile->nik }}</span>
            </div>
            @if($profile->no_kk)
            <div>
                <span class="block text-[11px] text-[#8bc3b8]">No. KK</span>
                <span class="font-bold font-mono">{{ $profile->no_kk }}</span>
            </div>
            @endif
            <div>
                <span class="block text-[11px] text-[#8bc3b8]">Alamat</span>
                <span class="font-semibold text-xs">{{ $profile->full_address ?? $profile->alamat ?? '-' }}</span>
            </div>
        </div>
        @if(!$profile->isVerified())
            <div class="mt-3 flex items-center gap-2 px-3 py-2 bg-amber-400/20 rounded-xl text-amber-300 text-[11px] font-semibold">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Profil Anda belum terverifikasi. Pengajuan tetap dapat dikirim namun mungkin memerlukan verifikasi data tambahan oleh petugas.
            </div>
        @endif
    </div>

    {{-- Submission Form --}}
    <form action="{{ route('masyarakat.pengajuan.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-3xl border border-[#e1ede8] shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        {{-- Errors --}}
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                <p class="font-bold">Mohon periksa kembali isian berikut:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- Step 1: Choose Letter Type --}}
        <div class="space-y-2">
            <label for="surat_template_id" class="block text-sm font-bold text-[#0c3837]">
                Pilih Jenis Surat <span class="text-red-500">*</span>
            </label>
            <select id="surat_template_id" name="surat_template_id" required
                    onchange="renderPortalDynamicFields(this.value)"
                    class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981] transition-colors">
                <option value="">-- Pilih jenis surat yang dibutuhkan --</option>
                @foreach($templates as $tpl)
                    <option value="{{ $tpl->id }}" {{ (old('surat_template_id', $template?->id ?? $templates->first()?->id) == $tpl->id) ? 'selected' : '' }}>
                        [{{ $tpl->kode_surat }}] {{ $tpl->nama_surat }}
                    </option>
                @endforeach
            </select>
            @error('surat_template_id')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dynamic Fields Sesuai Jenis Surat --}}
        <div id="portal-dynamic-wrapper" class="p-5 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-4 hidden">
            <div class="flex items-center justify-between">
                <span id="portal-dynamic-title" class="text-xs font-extrabold text-[#0c3837]">Data Rincian Surat</span>
                <span class="text-[11px] font-semibold text-[#10b981]">Tercetak otomatis pada dokumen</span>
            </div>
            <div id="portal-dynamic-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            <div id="portal-persyaratan-box" class="pt-3 border-t border-[#e1ede8] text-xs text-[#475569]">
                <span class="font-bold text-[#0c3837] block mb-1">Berkas Persyaratan yang Dianjurkan:</span>
                <ul id="portal-persyaratan-list" class="list-disc list-inside space-y-0.5"></ul>
            </div>
        </div>

        {{-- Step 2: Keperluan / Tujuan --}}
        <div class="space-y-2">
            <label for="keperluan" class="block text-sm font-bold text-[#0c3837]">
                Keperluan / Tujuan Pengajuan <span class="text-red-500">*</span>
            </label>
            <textarea id="keperluan" name="keperluan" rows="3" required
                      placeholder="Contoh: Untuk keperluan melamar pekerjaan di PT. Maju Bersama..."
                      class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0c3837] placeholder-[#94a3b8] focus:outline-none focus:border-[#10b981] resize-none transition-colors">{{ old('keperluan') }}</textarea>
            @error('keperluan')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Step 3: Document Upload --}}
        <div class="space-y-2">
            <label class="block text-sm font-bold text-[#0c3837]">
                Dokumen Pendukung
                <span class="text-[#64748b] font-normal text-xs ml-1">(Opsional, max 5 MB per file: PDF, JPG, PNG)</span>
            </label>

            <div id="upload-area"
                 class="border-2 border-dashed border-[#c9dfd8] rounded-2xl p-8 text-center hover:border-[#10b981] transition-colors cursor-pointer bg-[#f7faf9]"
                 onclick="document.getElementById('dokumen-input').click()">
                <svg class="w-10 h-10 text-[#10b981] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="text-sm font-bold text-[#0c3837]">Klik atau seret file ke sini</p>
                <p class="text-xs text-[#64748b] mt-1">Foto KTP, KK, surat pengantar RT/RW, dll.</p>
            </div>

            <input type="file" id="dokumen-input" name="dokumen[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                   class="hidden" onchange="showFileNames(this)">

            <div id="file-list" class="space-y-1.5 hidden">
                {{-- Populated by JS --}}
            </div>
            @error('dokumen.*')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Guidelines Box --}}
        <div class="p-4 rounded-2xl bg-[#f0faf6] border border-[#c9dfd8] text-xs text-[#475569] space-y-1.5">
            <p class="font-bold text-[#0c3837]">Petunjuk Pengajuan:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Pastikan data profil Anda (NIK, Nama, Alamat) sudah benar dan terkini.</li>
                <li>Pengajuan akan diproses dalam <strong>1 × 24 jam kerja</strong> oleh petugas desa.</li>
                <li>Status pengajuan dapat dipantau di halaman Riwayat Pengajuan.</li>
                <li>Untuk pengajuan darurat, silakan datang langsung ke kantor desa.</li>
            </ul>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('masyarakat.pengajuan.index') }}"
               class="px-5 py-2.5 rounded-full border border-[#e1ede8] text-xs font-semibold text-[#64748b] hover:bg-[#f7faf9] transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-7 py-2.5 bg-[#10b981] hover:bg-[#059669] text-white text-sm font-bold rounded-full shadow-sm transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Kirim Pengajuan
            </button>
        </div>
    </form>

</div>

@php
    $portalTemplatesJson = $templates->mapWithKeys(fn($t) => [
        $t->id => [
            'nama_surat' => $t->nama_surat,
            'schema_fields' => $t->schema_fields_json ?? [],
            'persyaratan' => $t->persyaratan_json ?? [],
        ]
    ]);
    $portalOldFormData = old('form_data', []);
@endphp

@push('scripts')
<script>
const PORTAL_TEMPLATES = @json($portalTemplatesJson);
const PORTAL_OLD_DATA = @json($portalOldFormData);

function renderPortalDynamicFields(tplId) {
    const wrapper = document.getElementById('portal-dynamic-wrapper');
    const container = document.getElementById('portal-dynamic-fields');
    const titleEl = document.getElementById('portal-dynamic-title');
    const syaratList = document.getElementById('portal-persyaratan-list');
    const tpl = PORTAL_TEMPLATES[tplId];

    if (!tpl) {
        wrapper.classList.add('hidden');
        return;
    }

    wrapper.classList.remove('hidden');
    titleEl.textContent = 'Data Rincian ' + tpl.nama_surat;
    container.innerHTML = '';

    (tpl.schema_fields || []).forEach(field => {
        const div = document.createElement('div');
        const val = PORTAL_OLD_DATA[field.name] || '';
        const reqAttr = field.required ? 'required' : '';
        const reqLabel = field.required ? '<span class="text-red-500">*</span>' : '';

        let inputHtml = '';
        if (field.type === 'select' && Array.isArray(field.options)) {
            const opts = field.options.map(opt => `<option value="${opt}" ${val === opt ? 'selected' : ''}>${opt}</option>`).join('');
            inputHtml = `<select name="form_data[${field.name}]" ${reqAttr} class="w-full px-3.5 py-2.5 bg-white border border-[#e1ede8] rounded-xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">${opts}</select>`;
        } else {
            const type = field.type === 'date' ? 'date' : 'text';
            inputHtml = `<input type="${type}" name="form_data[${field.name}]" value="${val}" ${reqAttr} placeholder="${field.placeholder || ''}" class="w-full px-3.5 py-2.5 bg-white border border-[#e1ede8] rounded-xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">`;
        }

        div.innerHTML = `<label class="block text-xs font-bold text-[#0c3837] mb-1">${field.label} ${reqLabel}</label>${inputHtml}`;
        container.appendChild(div);
    });

    syaratList.innerHTML = '';
    (tpl.persyaratan || []).forEach(s => {
        const li = document.createElement('li');
        li.textContent = s;
        syaratList.appendChild(li);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('surat_template_id');
    if (sel && sel.value) {
        renderPortalDynamicFields(sel.value);
    }
});

function showFileNames(input) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    if (input.files.length === 0) {
        fileList.classList.add('hidden');
        return;
    }
    fileList.classList.remove('hidden');
    Array.from(input.files).forEach((file, idx) => {
        const sizeKb = (file.size / 1024).toFixed(1);
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 px-3 py-2 bg-[#f7faf9] border border-[#e1ede8] rounded-xl text-xs text-[#0c3837]';
        div.innerHTML = `
            <svg class="w-4 h-4 text-[#10b981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-semibold truncate">${file.name}</span>
            <span class="ml-auto text-[#94a3b8] shrink-0">${sizeKb} KB</span>
        `;
        fileList.appendChild(div);
    });
}
</script>
@endpush
@endsection
