<x-layouts.app>
    <x-slot:title>Daftarkan Warga Baru</x-slot:title>

    <!-- Top Workspace Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <a href="{{ route('kependudukan.index') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Buku Induk</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Pendaftaran Warga</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Formulir Pendaftaran Warga Baru</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Lengkapi biodata kependudukan resmi untuk pencatatan Buku Induk Penduduk</p>
        </div>

        <div>
            <a href="{{ route('kependudukan.index') }}" 
               class="px-4 py-2.5 bg-white hover:bg-[#e2f0ed] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Buku Induk</span>
            </a>
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('kependudukan.store') }}" id="form-penduduk-create" class="space-y-6">
        @csrf

        @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs shadow-xs space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mohon periksa kembali formulir pendaftaran warga:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-600 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Identitas Utama Kependudukan -->
            <x-card class="space-y-4">
                <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    <span>Identitas Utama & Hubungan Keluarga</span>
                </h3>

                <div class="space-y-3.5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="nik">
                                NIK (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" maxlength="16" placeholder="3202110000000001" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('nik') border-rose-500 @enderror">
                            @error('nik') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="no_kk">
                                Nomor Kartu Keluarga (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" maxlength="16" placeholder="3202110000000000" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('no_kk') border-rose-500 @enderror">
                            @error('no_kk') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="nama_lengkap">
                            Nama Lengkap Sesuai Dokumen <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Asep Suhendar" required
                               class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('nama_lengkap') border-rose-500 @enderror">
                        @error('nama_lengkap') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="tempat_lahir">
                                Tempat Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Sukabumi" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('tempat_lahir') border-rose-500 @enderror">
                            @error('tempat_lahir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="tanggal_lahir">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('tanggal_lahir') border-rose-500 @enderror">
                            @error('tanggal_lahir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="jenis_kelamin">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                    class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('jenis_kelamin') border-rose-500 @enderror">
                                <option value="">Pilih Jenis Kelamin...</option>
                                <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-Laki</option>
                                <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="golongan_darah">
                                Golongan Darah
                            </label>
                            <select name="golongan_darah" id="golongan_darah"
                                    class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('golongan_darah') border-rose-500 @enderror">
                                <option value="">Tidak Diketahui</option>
                                @foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gd)
                                    <option value="{{ $gd }}" @selected(old('golongan_darah') === $gd)>{{ $gd }}</option>
                                @endforeach
                            </select>
                            @error('golongan_darah') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="agama">
                                Agama <span class="text-rose-500">*</span>
                            </label>
                            <select name="agama" id="agama" required
                                    class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('agama') border-rose-500 @enderror">
                                <option value="">Pilih Agama...</option>
                                @foreach(['Islam','Kristen Protestan','Kristen Katolik','Hindu','Budha','Konghucu'] as $ag)
                                    <option value="{{ $ag }}" @selected(old('agama', 'Islam') === $ag)>{{ $ag }}</option>
                                @endforeach
                            </select>
                            @error('agama') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="kewarganegaraan">
                                Kewarganegaraan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kewarganegaraan" id="kewarganegaraan" value="{{ old('kewarganegaraan', 'WNI') }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('kewarganegaraan') border-rose-500 @enderror">
                            @error('kewarganegaraan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="status_perkawinan">
                                Status Perkawinan <span class="text-rose-500">*</span>
                            </label>
                            <select name="status_perkawinan" id="status_perkawinan" required
                                    class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('status_perkawinan') border-rose-500 @enderror">
                                <option value="belum_kawin" @selected(old('status_perkawinan') === 'belum_kawin')>Belum Kawin</option>
                                <option value="kawin" @selected(old('status_perkawinan') === 'kawin')>Kawin</option>
                                <option value="cerai_hidup" @selected(old('status_perkawinan') === 'cerai_hidup')>Cerai Hidup</option>
                                <option value="cerai_mati" @selected(old('status_perkawinan') === 'cerai_mati')>Cerai Mati</option>
                            </select>
                            @error('status_perkawinan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="status_dalam_keluarga">
                                Status dalam Keluarga <span class="text-rose-500">*</span>
                            </label>
                            <select name="status_dalam_keluarga" id="status_dalam_keluarga" required
                                    class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('status_dalam_keluarga') border-rose-500 @enderror">
                                <option value="kepala_keluarga" @selected(old('status_dalam_keluarga') === 'kepala_keluarga')>Kepala Keluarga</option>
                                <option value="istri" @selected(old('status_dalam_keluarga') === 'istri')>Istri</option>
                                <option value="anak" @selected(old('status_dalam_keluarga') === 'anak')>Anak</option>
                                <option value="famili_lain" @selected(old('status_dalam_keluarga') === 'famili_lain')>Famili Lain</option>
                                <option value="lainnya" @selected(old('status_dalam_keluarga') === 'lainnya')>Lainnya</option>
                            </select>
                            @error('status_dalam_keluarga') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Right Column: Domisili, Pendidikan, & Klasifikasi -->
            <div class="space-y-6">
                <!-- Domisili & Kontak Card -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span>Domisili Wilayah & Kontak</span>
                    </h3>

                    <div class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="alamat_lengkap">
                                Alamat Lengkap Domisili <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alamat_lengkap" id="alamat_lengkap" rows="2" placeholder="Contoh: Jl. Cikole No. 12, Kp. Sukasari" required
                                      class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('alamat_lengkap') border-rose-500 @enderror">{{ old('alamat_lengkap') }}</textarea>
                            @error('alamat_lengkap') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="rt">
                                    RT <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="rt" id="rt" value="{{ old('rt') }}" maxlength="3" placeholder="001" required
                                       class="w-full px-3 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-center font-mono focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('rt') border-rose-500 @enderror">
                                @error('rt') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="rw">
                                    RW <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="rw" id="rw" value="{{ old('rw') }}" maxlength="3" placeholder="002" required
                                       class="w-full px-3 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-center font-mono focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('rw') border-rose-500 @enderror">
                                @error('rw') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="dusun">
                                    Dusun / Blok
                                </label>
                                <input type="text" name="dusun" id="dusun" value="{{ old('dusun') }}" placeholder="Cikole"
                                       class="w-full px-3 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('dusun') border-rose-500 @enderror">
                                @error('dusun') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="telepon">
                                Nomor Kontak / WhatsApp
                            </label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" placeholder="0812XXXXXXXX"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-mono focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('telepon') border-rose-500 @enderror">
                            @error('telepon') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-card>

                <!-- Pekerjaan & Status Administrasi Card -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-[#e2f0ed] text-[#114443] flex items-center justify-center text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <span>Sosial Ekonomi & Status Kependudukan</span>
                    </h3>

                    <div class="space-y-3.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="pendidikan_terakhir">
                                    Pendidikan Terakhir
                                </label>
                                <select name="pendidikan_terakhir" id="pendidikan_terakhir"
                                        class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('pendidikan_terakhir') border-rose-500 @enderror">
                                    <option value="">Pilih Jenjang...</option>
                                    @foreach(['Tidak/Belum Sekolah','Belum Tamat SD','Tamat SD','SLTP/Sederajat','SLTA/Sederajat','Diploma I/II','Diploma III','Diploma IV/S1','S2','S3'] as $p)
                                        <option value="{{ $p }}" @selected(old('pendidikan_terakhir') === $p)>{{ $p }}</option>
                                    @endforeach
                                </select>
                                @error('pendidikan_terakhir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="pekerjaan">
                                    Pekerjaan
                                </label>
                                <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="Petani, Wiraswasta, PNS, dll."
                                       class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('pekerjaan') border-rose-500 @enderror">
                                @error('pekerjaan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="sumber_data">
                                    Sumber Asal Data <span class="text-rose-500">*</span>
                                </label>
                                <select name="sumber_data" id="sumber_data" required
                                        class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('sumber_data') border-rose-500 @enderror">
                                    <option value="manual" @selected(old('sumber_data','manual') === 'manual')>Input Manual Staff</option>
                                    <option value="prodeskel" @selected(old('sumber_data') === 'prodeskel')>Prodeskel Kemendagri</option>
                                    <option value="migrasi_legacy" @selected(old('sumber_data') === 'migrasi_legacy')>Migrasi DB Legacy</option>
                                </select>
                                @error('sumber_data') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5" for="status_penduduk">
                                    Status Kependudukan <span class="text-rose-500">*</span>
                                </label>
                                <select name="status_penduduk" id="status_penduduk" required
                                        class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs text-[#0c3837] focus:bg-white focus:outline-none focus:border-[#10b981] transition-all @error('status_penduduk') border-rose-500 @enderror">
                                    <option value="tetap" @selected(old('status_penduduk','tetap') === 'tetap')>Penduduk Tetap</option>
                                    <option value="sementara" @selected(old('status_penduduk') === 'sementara')>Penduduk Sementara</option>
                                    <option value="pindah" @selected(old('status_penduduk') === 'pindah')>Pindah</option>
                                    <option value="meninggal" @selected(old('status_penduduk') === 'meninggal')>Meninggal</option>
                                </select>
                                @error('status_penduduk') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Action Button Card -->
                <x-card class="flex items-center justify-end gap-3">
                    <a href="{{ route('kependudukan.index') }}" 
                       class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-[#0f172a] text-xs font-bold rounded-full transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            id="btn-simpan-warga"
                            class="px-6 py-2.5 bg-[#114443] hover:bg-[#0c3837] text-white text-xs font-bold rounded-full shadow-md flex items-center gap-2 transition-all cursor-pointer">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Data Warga</span>
                    </button>
                </x-card>
            </div>
        </div>
    </form>
</x-layouts.app>
