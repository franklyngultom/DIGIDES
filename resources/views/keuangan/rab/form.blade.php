<x-layouts.app :title="isset($rab) ? 'Edit RAB: ' . $rab->nomor_rab : 'Buat Dokumen RAB Baru'">
    <div class="max-w-6xl mx-auto space-y-6" 
         x-data="{
             items: {{ isset($rab) && $rab->items->count() > 0 
                ? json_encode($rab->items->map(function($i) {
                    return [
                        'kategori'      => $i->kategori,
                        'kode_rekening' => $i->kode_rekening ?? '',
                        'uraian'        => $i->uraian,
                        'volume'        => (float)$i->volume,
                        'satuan'        => $i->satuan,
                        'harga_satuan'  => (float)$i->harga_satuan,
                        'keterangan'    => $i->keterangan ?? '',
                    ];
                }))
                : json_encode([
                    [
                        'kategori'      => 'bahan_material',
                        'kode_rekening' => '5.2.1.01',
                        'uraian'        => '',
                        'volume'        => 1,
                        'satuan'        => 'Zak',
                        'harga_satuan'  => 0,
                        'keterangan'    => '',
                    ]
                ]) 
             }},
             addItem() {
                 this.items.push({
                     kategori: 'bahan_material',
                     kode_rekening: '',
                     uraian: '',
                     volume: 1,
                     satuan: 'Unit',
                     harga_satuan: 0,
                     keterangan: '',
                 });
             },
             removeItem(index) {
                 if (this.items.length > 1) {
                     this.items.splice(index, 1);
                 } else {
                     alert('Minimal harus ada satu item rincian belanja dalam RAB.');
                 }
             },
             subtotal(kategori) {
                 return this.items
                     .filter(item => item.kategori === kategori)
                     .reduce((sum, item) => sum + ((parseFloat(item.volume) || 0) * (parseFloat(item.harga_satuan) || 0)), 0);
             },
             totalAnggaran() {
                 return this.items.reduce((sum, item) => sum + ((parseFloat(item.volume) || 0) * (parseFloat(item.harga_satuan) || 0)), 0);
             },
             formatRupiah(val) {
                 return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
             }
         }">
        
        <!-- Breadcrumb & Header -->
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#64748b] mb-1">
                <a href="{{ route('keuangan.index') }}" class="hover:text-[#114443]">Keuangan Desa</a>
                <span>/</span>
                <a href="{{ route('keuangan.rab.index', ['tahun' => $tahun]) }}" class="hover:text-[#114443]">RAB Desa</a>
                <span>/</span>
                <span class="text-[#0c3837] font-semibold">{{ isset($rab) ? 'Edit Dokumen RAB' : 'Penyusunan RAB Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight">
                {{ isset($rab) ? 'Edit Rencana Anggaran Biaya (RAB)' : 'Penyusunan Rencana Anggaran Biaya (RAB)' }}
            </h1>
            <p class="text-xs text-[#64748b] mt-0.5">
                Rincikan komponen belanja material, upah HOK, sewa alat, dan operasional kegiatan desa sesuai Permendagri No. 20/2018
            </p>
        </div>

        <form method="POST" action="{{ isset($rab) ? route('keuangan.rab.update', $rab) : route('keuangan.rab.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($rab))
                @method('PUT')
            @endif

            <!-- 1. Data Informasi Umum RAB -->
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm space-y-6">
                <div class="border-b border-[#e1ede8] pb-3">
                    <h2 class="text-sm font-bold text-[#0c3837] uppercase tracking-wider">1. Informasi Umum Dokumen RAB</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Tahun Anggaran -->
                    <div>
                        <label for="tahun_anggaran" class="block text-xs font-bold text-[#0c3837] mb-1.5">Tahun Anggaran <span class="text-rose-500">*</span></label>
                        <input type="number" id="tahun_anggaran" name="tahun_anggaran" value="{{ old('tahun_anggaran', $rab->tahun_anggaran ?? $tahun) }}" required min="2000" max="2099" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('tahun_anggaran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Dokumen RAB -->
                    <div>
                        <label for="nomor_rab" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nomor Dokumen RAB <span class="text-rose-500">*</span></label>
                        <input type="text" id="nomor_rab" name="nomor_rab" value="{{ old('nomor_rab', $rab->nomor_rab ?? 'RAB/01/DDS/' . date('Y')) }}" placeholder="Contoh: RAB/01/DDS/2026" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:outline-none focus:border-[#10b981]">
                        @error('nomor_rab') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label for="sumber_dana" class="block text-xs font-bold text-[#0c3837] mb-1.5">Sumber Dana <span class="text-rose-500">*</span></label>
                        <select id="sumber_dana" name="sumber_dana" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="DDS" {{ old('sumber_dana', $rab->sumber_dana ?? $sumberDana) === 'DDS' ? 'selected' : '' }}>DDS (Dana Desa)</option>
                            <option value="ADD" {{ old('sumber_dana', $rab->sumber_dana ?? $sumberDana) === 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                            <option value="PBH" {{ old('sumber_dana', $rab->sumber_dana ?? $sumberDana) === 'PBH' ? 'selected' : '' }}>PBH (Bagi Hasil Pajak)</option>
                            <option value="PAD" {{ old('sumber_dana', $rab->sumber_dana ?? $sumberDana) === 'PAD' ? 'selected' : '' }}>PAD (Pendapatan Asli Desa)</option>
                            <option value="DLL" {{ old('sumber_dana', $rab->sumber_dana ?? $sumberDana) === 'DLL' ? 'selected' : '' }}>DLL (Bantuan Keuangan)</option>
                        </select>
                        @error('sumber_dana') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bidang APBDes -->
                    <div>
                        <label for="bidang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Bidang Pemerintahan <span class="text-rose-500">*</span></label>
                        <select id="bidang" name="bidang" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="Bidang 1: Penyelenggaraan Pemerintahan Desa" {{ old('bidang', $rab->bidang ?? '') === 'Bidang 1: Penyelenggaraan Pemerintahan Desa' ? 'selected' : '' }}>Bidang 1: Penyelenggaraan Pemerintahan Desa</option>
                            <option value="Bidang 2: Pelaksanaan Pembangunan Desa" {{ old('bidang', $rab->bidang ?? 'Bidang 2: Pelaksanaan Pembangunan Desa') === 'Bidang 2: Pelaksanaan Pembangunan Desa' ? 'selected' : '' }}>Bidang 2: Pelaksanaan Pembangunan Desa</option>
                            <option value="Bidang 3: Pembinaan Kemasyarakatan Desa" {{ old('bidang', $rab->bidang ?? '') === 'Bidang 3: Pembinaan Kemasyarakatan Desa' ? 'selected' : '' }}>Bidang 3: Pembinaan Kemasyarakatan Desa</option>
                            <option value="Bidang 4: Pemberdayaan Masyarakat Desa" {{ old('bidang', $rab->bidang ?? '') === 'Bidang 4: Pemberdayaan Masyarakat Desa' ? 'selected' : '' }}>Bidang 4: Pemberdayaan Masyarakat Desa</option>
                            <option value="Bidang 5: Penanggulangan Bencana & Darurat" {{ old('bidang', $rab->bidang ?? '') === 'Bidang 5: Penanggulangan Bencana & Darurat' ? 'selected' : '' }}>Bidang 5: Penanggulangan Bencana & Darurat</option>
                        </select>
                        @error('bidang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sub Bidang -->
                    <div>
                        <label for="sub_bidang" class="block text-xs font-bold text-[#0c3837] mb-1.5">Sub Bidang / Urusan (Opsional)</label>
                        <input type="text" id="sub_bidang" name="sub_bidang" value="{{ old('sub_bidang', $rab->sub_bidang ?? '') }}" placeholder="Contoh: Pekerjaan Umum dan Penataan Ruang" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('sub_bidang') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Nama Kegiatan -->
                <div>
                    <label for="nama_kegiatan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nama Kegiatan / Pekerjaan <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $rab->nama_kegiatan ?? '') }}" placeholder="Contoh: Pembangunan Rabat Beton Jalan Usaha Tani Dusun Babakan" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    @error('nama_kegiatan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-[#0c3837] mb-1.5">Lokasi Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $rab->lokasi ?? '') }}" placeholder="Contoh: Dusun Babakan RT 02 / RW 03" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('lokasi') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Waktu Pelaksanaan -->
                    <div>
                        <label for="waktu_pelaksanaan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Waktu Pelaksanaan <span class="text-rose-500">*</span></label>
                        <input type="text" id="waktu_pelaksanaan" name="waktu_pelaksanaan" value="{{ old('waktu_pelaksanaan', $rab->waktu_pelaksanaan ?? '90 Hari Kalender') }}" placeholder="Contoh: 90 Hari Kalender (Maret - Mei 2026)" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('waktu_pelaksanaan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- PPKD / Pelaksana Kegiatan -->
                    <div>
                        <label for="nama_ppkd" class="block text-xs font-bold text-[#0c3837] mb-1.5">Nama Pelaksana Kegiatan (PPKD)</label>
                        <input type="text" id="nama_ppkd" name="nama_ppkd" value="{{ old('nama_ppkd', $rab->nama_ppkd ?? 'Kaur Kasi Kesejahteraan / Kaur Perencanaan') }}" placeholder="Nama Kasi/Kaur Pelaksana" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('nama_ppkd') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jabatan PPKD -->
                    <div>
                        <label for="jabatan_ppkd" class="block text-xs font-bold text-[#0c3837] mb-1.5">Jabatan Pelaksana</label>
                        <input type="text" id="jabatan_ppkd" name="jabatan_ppkd" value="{{ old('jabatan_ppkd', $rab->jabatan_ppkd ?? 'Kepala Seksi Kesejahteraan') }}" placeholder="Contoh: Kasi Kesejahteraan / TPK" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">
                        @error('jabatan_ppkd') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status RAB -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-[#0c3837] mb-1.5">Status Persetujuan <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                            <option value="draft" {{ old('status', $rab->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft Rancangan</option>
                            <option value="disetujui" {{ old('status', $rab->status ?? '') === 'disetujui' ? 'selected' : '' }}>Disetujui PPKD / Kades</option>
                            <option value="direalisasikan" {{ old('status', $rab->status ?? '') === 'direalisasikan' ? 'selected' : '' }}>Telah Direalisasikan</option>
                        </select>
                        @error('status') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Keterangan / Spesifikasi Ringkas -->
                <div>
                    <label for="keterangan" class="block text-xs font-bold text-[#0c3837] mb-1.5">Keterangan / Deskripsi Teknis Kegiatan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan spesifikasi teknis pekerjaan atau dasar penetapan harga..." class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none focus:border-[#10b981]">{{ old('keterangan', $rab->keterangan ?? '') }}</textarea>
                    @error('keterangan') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- File Lampiran Dokumen RAB -->
                <div>
                    <label for="file_lampiran" class="block text-xs font-bold text-[#0c3837] mb-1.5">Unggah Berkas Lampiran Teknis (PDF/Excel Maks. 10MB)</label>
                    <input type="file" id="file_lampiran" name="file_lampiran" accept=".pdf,.xlsx,.xls" class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#114443] file:text-white hover:file:bg-[#0c3837] cursor-pointer">
                    @if(isset($rab) && $rab->file_lampiran_path)
                        <div class="mt-2 text-xs text-[#10b981] flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Lampiran tersimpan: <a href="{{ asset('storage/' . $rab->file_lampiran_path) }}" target="_blank" class="underline font-bold">Unduh Lampiran</a></span>
                        </div>
                    @endif
                    @error('file_lampiran') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- 2. Rincian Item Belanja RAB (Dynamic Multi-Row) -->
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#e1ede8] shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e1ede8] pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-[#0c3837] uppercase tracking-wider">2. Rincian Item Belanja RAB</h2>
                        <p class="text-xs text-[#64748b] mt-0.5">Tambah komponen belanja material, upah HOK, sewa alat, dan operasional</p>
                    </div>

                    <button type="button" @click="addItem()" class="px-4 py-2 bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] text-xs font-bold rounded-2xl transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Item Belanja</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8] space-y-3 relative transition-all">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-[#114443] flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-[#114443] text-white text-[10px] flex items-center justify-center font-bold" x-text="index + 1"></span>
                                    <span>Item Belanja #<span x-text="index + 1"></span></span>
                                </span>

                                <button type="button" @click="removeItem(index)" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Kategori -->
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0c3837] mb-1">Kategori Belanja <span class="text-rose-500">*</span></label>
                                    <select :name="`items[${index}][kategori]`" x-model="item.kategori" required class="w-full px-3 py-2 bg-white border border-[#e1ede8] rounded-xl text-xs font-semibold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                                        <option value="bahan_material">Bahan & Material</option>
                                        <option value="upah_tenaga_kerja">Upah Tenaga Kerja (HOK)</option>
                                        <option value="sewa_alat">Sewa Peralatan</option>
                                        <option value="operasional">Operasional & Lainnya</option>
                                    </select>
                                </div>

                                <!-- Kode Rekening -->
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0c3837] mb-1">Kode Rekening (Opsional)</label>
                                    <input type="text" :name="`items[${index}][kode_rekening]`" x-model="item.kode_rekening" placeholder="5.2.1.01" class="w-full px-3 py-2 bg-white border border-[#e1ede8] rounded-xl text-xs font-mono focus:outline-none focus:border-[#10b981]">
                                </div>

                                <!-- Uraian Item -->
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-bold text-[#0c3837] mb-1">Uraian / Deskripsi Item Belanja <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="`items[${index}][uraian]`" x-model="item.uraian" placeholder="Contoh: Semen Gresik 50kg / Upah Tukang Batu" required class="w-full px-3 py-2 bg-white border border-[#e1ede8] rounded-xl text-xs font-semibold focus:outline-none focus:border-[#10b981]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                                <!-- Volume -->
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0c3837] mb-1">Volume / Jumlah <span class="text-rose-500">*</span></label>
                                    <input type="number" :name="`items[${index}][volume]`" x-model="item.volume" step="0.01" min="0.01" required class="w-full px-3 py-2 bg-white border border-[#e1ede8] rounded-xl text-xs font-bold text-center focus:outline-none focus:border-[#10b981]">
                                </div>

                                <!-- Satuan -->
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0c3837] mb-1">Satuan <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="`items[${index}][satuan]`" x-model="item.satuan" placeholder="Zak, M3, HOK, Hari, Unit" required class="w-full px-3 py-2 bg-white border border-[#e1ede8] rounded-xl text-xs text-center focus:outline-none focus:border-[#10b981]">
                                </div>

                                <!-- Harga Satuan -->
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0c3837] mb-1">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="number" :name="`items[${index}][harga_satuan]`" x-model="item.harga_satuan" step="0.01" min="0" required class="w-full px-3 py-2 bg-white border border-[#e1ede8] rounded-xl text-xs font-bold text-right focus:outline-none focus:border-[#10b981]">
                                </div>

                                <!-- Subtotal Harga (Computed) -->
                                <div class="bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 text-right">
                                    <span class="text-[10px] text-emerald-800 font-bold block uppercase">Subtotal Item:</span>
                                    <span class="text-xs font-extrabold text-emerald-800 font-mono" x-text="formatRupiah((parseFloat(item.volume) || 0) * (parseFloat(item.harga_satuan) || 0))"></span>
                                </div>
                            </div>

                            <!-- Keterangan Item -->
                            <div>
                                <label class="block text-[10px] font-semibold text-[#64748b] mb-0.5">Spesifikasi / Catatan Merk (Opsional)</label>
                                <input type="text" :name="`items[${index}][keterangan]`" x-model="item.keterangan" placeholder="Contoh: SNI type 1, lokasi depo Sukamaju" class="w-full px-3 py-1.5 bg-white border border-[#e1ede8] rounded-xl text-[11px] focus:outline-none focus:border-[#10b981]">
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Tombol Tambah Baris Cepat -->
                <div class="pt-2">
                    <button type="button" @click="addItem()" class="w-full py-3 border-2 border-dashed border-[#10b981]/40 hover:border-[#10b981] hover:bg-[#e2f0ed]/40 text-[#114443] text-xs font-bold rounded-2xl transition-colors flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Baris Item Belanja Baru</span>
                    </button>
                </div>

                <!-- Ringkasan Anggaran Real-time Box -->
                <div class="p-6 rounded-2xl bg-[#0c3837] text-white space-y-4">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#d4ed31]">Rekapitulasi Anggaran RAB Real-Time</span>
                        <span class="text-xs font-semibold text-slate-300" x-text="`${items.length} Item Terdaftar`"></span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                            <span class="text-[10px] text-slate-300 block">Bahan & Material:</span>
                            <span class="font-bold text-white font-mono mt-0.5 block" x-text="formatRupiah(subtotal('bahan_material'))"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                            <span class="text-[10px] text-slate-300 block">Upah Tenaga Kerja:</span>
                            <span class="font-bold text-white font-mono mt-0.5 block" x-text="formatRupiah(subtotal('upah_tenaga_kerja'))"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                            <span class="text-[10px] text-slate-300 block">Sewa Peralatan:</span>
                            <span class="font-bold text-white font-mono mt-0.5 block" x-text="formatRupiah(subtotal('sewa_alat'))"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                            <span class="text-[10px] text-slate-300 block">Operasional / Lainnya:</span>
                            <span class="font-bold text-white font-mono mt-0.5 block" x-text="formatRupiah(subtotal('operasional'))"></span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-sm font-extrabold text-white">TOTAL RENCANA ANGGARAN BIAYA (RAB) :</span>
                        <span class="text-xl font-extrabold text-[#d4ed31] font-mono" x-text="formatRupiah(totalAnggaran())"></span>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan & Batal -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('keuangan.rab.index', ['tahun' => $tahun]) }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-7 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-2xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ isset($rab) ? 'Simpan Perubahan RAB' : 'Simpan Dokumen RAB' }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
