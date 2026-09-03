<x-layouts.app>
    <x-slot:title>Profil & Identitas Desa</x-slot:title>

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Profil Desa</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Profil & Identitas Resmi Desa</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Konfigurasi identitas resmi kantor desa, kop surat, dan informasi Kepala Desa</p>
        </div>

        <x-badge variant="pine" class="px-4 py-1.5 text-xs">
            Kode Wilayah: {{ $desa->kode_desa ?? 'Belum Diatur' }}
        </x-badge>
    </div>

    <!-- Form Section -->
    <form method="POST" action="{{ route('admin.desa.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Columns: Village & Kades Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Village Administration Data -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Data Pemerintahan & Wilayah</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Nama Desa *</label>
                            <input type="text" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('nama_desa') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Kode Kemendagri Desa</label>
                            <input type="text" name="kode_desa" value="{{ old('kode_desa', $desa->kode_desa) }}" placeholder="3202112001"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all font-mono">
                            @error('kode_desa') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Kecamatan *</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $desa->kecamatan) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('kecamatan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Kabupaten / Kota *</label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten', $desa->kabupaten) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('kabupaten') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Provinsi *</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $desa->provinsi) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('provinsi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $desa->kode_pos) }}" placeholder="43113"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('kode_pos') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Alamat Kantor Desa *</label>
                            <textarea name="alamat_kantor" rows="2" required
                                      class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">{{ old('alamat_kantor', $desa->alamat_kantor) }}</textarea>
                            @error('alamat_kantor') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-card>

                <!-- Village Head Data -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Informasi Kepala Desa (Kades)</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Nama Lengkap Kades *</label>
                            <input type="text" name="nama_kades" value="{{ old('nama_kades', $desa->nama_kades) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('nama_kades') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">NIP Kepala Desa</label>
                            <input type="text" name="nip_kades" value="{{ old('nip_kades', $desa->nip_kades) }}" placeholder="197508172005011003"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all font-mono">
                            @error('nip_kades') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">NIK Kepala Desa (16 Digit)</label>
                            <input type="text" name="nik_kades" value="{{ old('nik_kades', $desa->nik_kades) }}" maxlength="16" placeholder="3202111708750001"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all font-mono">
                            @error('nik_kades') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Logo & Contact Info -->
            <div class="space-y-6">
                <!-- Official Logo Card -->
                <x-card class="space-y-4 text-center">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 text-left">Logo Resmi Desa</h3>

                    <div class="flex flex-col items-center justify-center p-4 bg-[#f7faf9] rounded-2xl border border-dashed border-[#e1ede8]">
                        @if ($desa->logo_url)
                            <img src="{{ $desa->logo_url }}" class="w-24 h-24 object-contain mb-3" alt="Logo {{ $desa->nama_desa }}">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center mb-3">
                                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                        @endif
                        <span class="text-xs font-bold text-[#0c3837] block">Logo Kop Surat</span>
                        <span class="text-[10px] text-[#64748b] mt-0.5">Format: PNG transparan, JPG, SVG (Maks. 2 MB)</span>
                    </div>

                    <div>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#114443] hover:file:bg-[#d0e6e1] cursor-pointer">
                        @error('logo') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </x-card>

                <!-- Scenic Village Landscape Photo Card -->
                <x-card class="space-y-4" x-data="{ 
                    photoPreview: null,
                    removePhoto: false,
                    previewImage(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.photoPreview = URL.createObjectURL(file);
                            this.removePhoto = false;
                        }
                    }
                }">
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <h3 class="text-base font-bold text-[#0c3837]">Foto Lanskap Desa (Sidebar)</h3>
                        @if ($desa->has_custom_foto_desa)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#10b981]/15 text-[#0c3837] border border-[#10b981]/30">Foto Kustom</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">Foto Bawaan</span>
                        @endif
                    </div>

                    <!-- Photo Preview Box -->
                    <div class="relative rounded-2xl overflow-hidden h-36 border border-[#e1ede8] bg-[#0c3837] shadow-inner group">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover" alt="Preview Foto Baru">
                        </template>
                        <template x-if="!photoPreview">
                            <img src="{{ $desa->foto_desa_url }}" 
                                 :class="removePhoto ? 'opacity-30 grayscale' : 'opacity-100'"
                                 class="w-full h-full object-cover transition-all duration-300" 
                                 alt="Foto {{ $desa->nama_desa }}">
                        </template>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837]/90 via-transparent to-transparent flex flex-col justify-end p-3 pointer-events-none">
                            <h4 class="text-xs font-bold text-white tracking-wide">{{ $desa->nama_desa }}</h4>
                            <p class="text-[10px] text-[#8bc3b8]">{{ $desa->kabupaten }}, Indonesia • GMT+7</p>
                        </div>

                        <template x-if="removePhoto">
                            <div class="absolute inset-0 bg-rose-950/70 flex items-center justify-center p-3 text-center">
                                <span class="text-xs font-bold text-rose-200">Foto kustom akan dihapus & kembali ke bawaan saat disimpan</span>
                            </div>
                        </template>
                    </div>

                    <!-- File Input & Upload Control -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Unggah Foto Lanskap Baru</label>
                            <input type="file" name="foto_desa" accept="image/png,image/jpeg,image/webp" @change="previewImage($event)"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#114443] hover:file:bg-[#d0e6e1] cursor-pointer">
                            <span class="text-[10px] text-[#64748b] mt-1 block">Format: JPG, PNG, WEBP (Rasio lanskap disarankan, Maks. 5 MB)</span>
                            @error('foto_desa') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if ($desa->has_custom_foto_desa)
                            <div class="pt-2 border-t border-[#e1ede8]">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="hapus_foto_desa" value="1" x-model="removePhoto"
                                           class="rounded border-[#e1ede8] text-rose-600 focus:ring-rose-500 w-4 h-4">
                                    <span class="text-xs font-semibold text-rose-600">Hapus foto kustom (Kembalikan ke foto bawaan)</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Contact & Digital Presence -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3">Kontak & Portal</h3>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Email Kantor Desa</label>
                        <input type="email" name="email_desa" value="{{ old('email_desa', $desa->email_desa) }}" placeholder="kontak@desa.id"
                               class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                        @error('email_desa') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Telepon Kantor</label>
                        <input type="text" name="telepon_desa" value="{{ old('telepon_desa', $desa->telepon_desa) }}" placeholder="0266-221144"
                               class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                        @error('telepon_desa') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Website Resmi</label>
                        <input type="url" name="website" value="{{ old('website', $desa->website) }}" placeholder="https://desa-sukamaju.id"
                               class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                        @error('website') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </x-card>

                <!-- Action Button -->
                @can('desa.update')
                <x-card>
                    <button type="submit" class="w-full py-3 px-6 bg-[#114443] hover:bg-[#0c3837] text-white font-bold rounded-full shadow-md transition-all text-xs cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan Profil</span>
                    </button>
                </x-card>
                @endcan
            </div>
        </div>
    </form>
</x-layouts.app>
