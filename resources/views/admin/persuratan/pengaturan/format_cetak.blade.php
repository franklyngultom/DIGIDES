<x-persuratan.pengaturan-layout 
    pageTitle="Format Cetak Surat - DIGIDES"
    cardTitle="Pengaturan Format Cetak Surat"
    activeTab="format-cetak">

    <x-slot:cardActions>
        <a href="{{ route('persuratan.create') }}" 
           class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-[#1e293b] text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-white/20 transition-all">
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span>Halaman Pelayanan</span>
        </a>

        <button type="submit" 
                form="form-format-cetak"
                class="px-4 py-1.5 rounded-xl bg-[#4338ca] hover:bg-[#3730a3] text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all hover:scale-102 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            <span>Simpan</span>
        </button>
    </x-slot:cardActions>

    <form id="form-format-cetak" method="POST" action="{{ route('persuratan.pengaturan.format-cetak.update') }}">
        @csrf

        <div class="space-y-6">
            <!-- Grid Setting Format Cetak Global -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- 1. Ukuran Kertas -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Ukuran Kertas <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="ukuran_kertas" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="A4 (210mm x 297mm)" {{ old('ukuran_kertas', $pengaturan->ukuran_kertas) === 'A4 (210mm x 297mm)' ? 'selected' : '' }}>A4 (210mm x 297mm)</option>
                            <option value="F4 / Folio (215mm x 330mm)" {{ old('ukuran_kertas', $pengaturan->ukuran_kertas) === 'F4 / Folio (215mm x 330mm)' ? 'selected' : '' }}>F4 / Folio (215mm x 330mm)</option>
                            <option value="Letter (216mm x 279mm)" {{ old('ukuran_kertas', $pengaturan->ukuran_kertas) === 'Letter (216mm x 279mm)' ? 'selected' : '' }}>Letter (216mm x 279mm)</option>
                            <option value="Legal (216mm x 356mm)" {{ old('ukuran_kertas', $pengaturan->ukuran_kertas) === 'Legal (216mm x 356mm)' ? 'selected' : '' }}>Legal (216mm x 356mm)</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Margin Kertas (4 sisi) -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Margin Kertas (mm) <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-1 bg-[#fefce8] p-1 rounded-xl border border-[#fef08a]">
                        <input type="number" name="margin_atas" value="{{ old('margin_atas', $pengaturan->margin_atas ?? 20) }}" title="Atas" placeholder="Atas" class="w-full text-center py-1.5 bg-white rounded-lg text-xs font-bold focus:outline-none">
                        <input type="number" name="margin_kanan" value="{{ old('margin_kanan', $pengaturan->margin_kanan ?? 10) }}" title="Kanan" placeholder="Kanan" class="w-full text-center py-1.5 bg-white rounded-lg text-xs font-bold focus:outline-none">
                        <input type="number" name="margin_bawah" value="{{ old('margin_bawah', $pengaturan->margin_bawah ?? 20) }}" title="Bawah" placeholder="Bawah" class="w-full text-center py-1.5 bg-white rounded-lg text-xs font-bold focus:outline-none">
                        <input type="number" name="margin_kiri" value="{{ old('margin_kiri', $pengaturan->margin_kiri ?? 10) }}" title="Kiri" placeholder="Kiri" class="w-full text-center py-1.5 bg-white rounded-lg text-xs font-bold focus:outline-none">
                    </div>
                </div>

                <!-- 3. Jenis Font -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Jenis Font <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="jenis_font" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="Arial" {{ old('jenis_font', $pengaturan->jenis_font) === 'Arial' ? 'selected' : '' }}>Arial</option>
                            <option value="Times New Roman" {{ old('jenis_font', $pengaturan->jenis_font) === 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                            <option value="Calibri" {{ old('jenis_font', $pengaturan->jenis_font) === 'Calibri' ? 'selected' : '' }}>Calibri</option>
                            <option value="Plus Jakarta Sans" {{ old('jenis_font', $pengaturan->jenis_font) === 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans</option>
                        </select>
                    </div>
                </div>

                <!-- 4. Ukuran Font -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Ukuran Font <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="ukuran_font" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="10pt" {{ old('ukuran_font', $pengaturan->ukuran_font) === '10pt' ? 'selected' : '' }}>10pt</option>
                            <option value="11pt" {{ old('ukuran_font', $pengaturan->ukuran_font) === '11pt' ? 'selected' : '' }}>11pt</option>
                            <option value="12pt" {{ old('ukuran_font', $pengaturan->ukuran_font) === '12pt' ? 'selected' : '' }}>12pt</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Row 2: Tinggi Baris, Pangkat, NIP, QR Code -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Tinggi Baris -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Tinggi Baris <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="tinggi_baris" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="1.0pt" {{ old('tinggi_baris', $pengaturan->tinggi_baris) === '1.0pt' ? 'selected' : '' }}>1.0pt</option>
                            <option value="1.15pt" {{ old('tinggi_baris', $pengaturan->tinggi_baris) === '1.15pt' ? 'selected' : '' }}>1.15pt</option>
                            <option value="1.4pt" {{ old('tinggi_baris', $pengaturan->tinggi_baris) === '1.4pt' ? 'selected' : '' }}>1.4pt</option>
                            <option value="1.5pt" {{ old('tinggi_baris', $pengaturan->tinggi_baris) === '1.5pt' ? 'selected' : '' }}>1.5pt</option>
                        </select>
                    </div>
                </div>

                <!-- Munculkan Pangkat -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Munculkan Pangkat <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="munculkan_pangkat" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="Tidak" {{ old('munculkan_pangkat', $pengaturan->munculkan_pangkat) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('munculkan_pangkat', $pengaturan->munculkan_pangkat) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                </div>

                <!-- Munculkan NIP -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Munculkan NIP <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="munculkan_nip" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="-" {{ old('munculkan_nip', $pengaturan->munculkan_nip) === '-' ? 'selected' : '' }}>-</option>
                            <option value="Ya" {{ old('munculkan_nip', $pengaturan->munculkan_nip) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                </div>

                <!-- Munculkan QR Code -->
                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1.5">
                        Munculkan QR Code <span class="text-rose-500">*</span>
                    </label>
                    <div class="rounded-xl bg-[#fefce8] border border-[#fef08a] focus-within:border-[#f59e0b] overflow-hidden shadow-2xs">
                        <select name="munculkan_qr" class="w-full px-3.5 py-2.5 bg-transparent text-xs text-[#1e293b] font-medium focus:outline-none cursor-pointer">
                            <option value="Tidak" {{ old('munculkan_qr', $pengaturan->munculkan_qr) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('munculkan_qr', $pengaturan->munculkan_qr) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Section Bawah: Pengaturan Per-Jenis Surat (CRUD setting per template) -->
    <div class="border-t border-[#e1ede8] mt-8 pt-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="text-sm font-extrabold text-[#0c3837]">
                Pengaturan Per-Jenis Surat:
            </h3>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('persuratan.pengaturan.format-cetak') }}" class="relative w-full sm:w-72">
                <input type="text" 
                       name="q" 
                       value="{{ $search ?? '' }}"
                       placeholder="Cari jenis surat..." 
                       class="w-full pl-9 pr-4 py-2 rounded-xl bg-[#fefce8] border border-[#fef08a] text-xs focus:outline-none focus:border-[#f59e0b]">
                <svg class="w-4 h-4 text-[#854d0e] absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>
        </div>

        <!-- Table List Template Surat -->
        <div class="overflow-x-auto rounded-xl border border-[#e1ede8] shadow-2xs">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#1b5e5c] text-white uppercase text-[11px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-bold">Kode</th>
                        <th class="px-4 py-3 font-bold">Nama Layanan Surat</th>
                        <th class="px-4 py-3 font-bold">Format Penomoran</th>
                        <th class="px-4 py-3 font-bold text-center">Status Online</th>
                        <th class="px-4 py-3 font-bold text-center">Aksi Override</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1ede8] bg-white">
                    @forelse($templates as $tmpl)
                    <tr class="hover:bg-[#f7faf9] transition-colors" x-data="{ openEdit: false }">
                        <td class="px-4 py-3 font-mono font-bold text-[#114443]">{{ $tmpl->kode_surat }}</td>
                        <td class="px-4 py-3 font-bold text-[#0f172a]">{{ $tmpl->nama_surat }}</td>
                        <td class="px-4 py-3 font-mono text-[11px] text-[#64748b]">{{ $tmpl->penomoran_format }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $tmpl->is_online_available ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $tmpl->is_online_available ? 'Online Aktif' : 'Internal Saja' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" 
                                    @click="openEdit = true"
                                    class="px-3 py-1.5 rounded-lg bg-[#edf5f2] hover:bg-[#114443] text-[#114443] hover:text-emerald-300 font-bold text-[11px] transition-all cursor-pointer">
                                Kustom Cetak
                            </button>

                            <!-- Modal Kustom Cetak Template -->
                            <div x-show="openEdit" 
                                 x-cloak 
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
                                 @keydown.escape.window="openEdit = false">
                                <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl space-y-4" @click.away="openEdit = false">
                                    <div class="flex items-center justify-between pb-3 border-b border-[#e1ede8]">
                                        <h4 class="font-bold text-sm text-[#0c3837]">Kustom Format: {{ $tmpl->nama_surat }}</h4>
                                        <button type="button" @click="openEdit = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                                    </div>
                                    <form method="POST" action="{{ route('persuratan.pengaturan.format-cetak.template', $tmpl) }}" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[11px] font-bold text-[#334155] mb-1">Ukuran Kertas Khusus</label>
                                            <select name="ukuran_kertas" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#cbd5e1] rounded-xl text-xs">
                                                <option value="">Gunakan Standar Global ({{ $pengaturan->ukuran_kertas }})</option>
                                                <option value="A4" {{ ($tmpl->custom_print_settings_json['ukuran_kertas'] ?? '') === 'A4' ? 'selected' : '' }}>A4</option>
                                                <option value="F4 / Folio" {{ ($tmpl->custom_print_settings_json['ukuran_kertas'] ?? '') === 'F4 / Folio' ? 'selected' : '' }}>F4 / Folio</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-[#334155] mb-1">Font Khusus</label>
                                            <select name="jenis_font" class="w-full px-3 py-2 bg-[#f7faf9] border border-[#cbd5e1] rounded-xl text-xs">
                                                <option value="">Gunakan Standar Global ({{ $pengaturan->jenis_font }})</option>
                                                <option value="Arial" {{ ($tmpl->custom_print_settings_json['jenis_font'] ?? '') === 'Arial' ? 'selected' : '' }}>Arial</option>
                                                <option value="Times New Roman" {{ ($tmpl->custom_print_settings_json['jenis_font'] ?? '') === 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                            </select>
                                        </div>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-[#e1ede8]">
                                            <button type="button" @click="openEdit = false" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs">Batal</button>
                                            <button type="submit" class="px-4 py-1.5 rounded-lg bg-[#114443] text-white text-xs font-bold">Simpan Kustom</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-400">Tidak ada data template surat ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-persuratan.pengaturan-layout>
