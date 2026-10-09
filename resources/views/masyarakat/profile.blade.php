@extends('layouts.masyarakat')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header Title -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#0c3837] tracking-tight">Profil Akun & Kependudukan</h1>
        <p class="text-xs sm:text-sm text-[#64748b]">Kelola data kependudukan Anda untuk mempermudah penerbitan surat resmi desa.</p>
    </div>

    <!-- Verification Card Banner -->
    <div class="p-5 rounded-3xl {{ $profile?->isVerified() ? 'bg-[#e2f0ed] border border-[#34d399]' : 'bg-amber-50 border border-amber-200' }} flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl {{ $profile?->isVerified() ? 'bg-[#10b981] text-white' : 'bg-amber-500 text-white' }} flex items-center justify-center shrink-0 shadow-xs">
                @if($profile?->isVerified())
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                @endif
            </div>
            <div>
                <span class="block text-xs font-bold {{ $profile?->isVerified() ? 'text-[#0c3837]' : 'text-amber-900' }} uppercase tracking-wider">
                    {{ $profile?->isVerified() ? 'Akun Terverifikasi Resmi' : 'Status: Menunggu Validasi Berkas' }}
                </span>
                <p class="text-xs {{ $profile?->isVerified() ? 'text-[#114443]' : 'text-amber-800' }}">
                    {{ $profile?->catatan_verifikasi ?? 'Data Anda tersimpan secara aman di server DIGIDES.' }}
                </p>
            </div>
        </div>
        <div class="shrink-0">
            <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $profile?->isVerified() ? 'bg-[#10b981] text-white' : 'bg-amber-600 text-white' }}">
                {{ $profile?->isVerified() ? 'Sah' : 'Validasi Petugas' }}
            </span>
        </div>
    </div>

    <!-- Main Profile Edit Form -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-[#e1ede8] shadow-sm space-y-8">
        <form action="{{ route('masyarakat.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3">Informasi Identitas Diri</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <!-- NIK (Read-only for security) -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" value="{{ $profile->nik ?? '' }}" disabled 
                           class="w-full px-4 py-3 bg-[#f1f5f4] border border-[#e1ede8] rounded-2xl text-xs font-bold text-slate-500 cursor-not-allowed">
                    <span class="text-[10px] text-[#64748b]">NIK terikat permanen dengan akun.</span>
                </div>

                <!-- No KK -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Nomor Kartu Keluarga (KK)
                    </label>
                    <input type="text" name="no_kk" value="{{ old('no_kk', $profile->no_kk ?? '') }}" maxlength="16"
                           placeholder="16 digit nomor KK" 
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    @error('no_kk') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    @error('name') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Nomor HP / WA -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Nomor WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                    @error('phone') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Tempat Lahir
                    </label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}"
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Tanggal Lahir
                    </label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $profile?->tanggal_lahir?->format('Y-m-d') ?? '') }}"
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Jenis Kelamin
                    </label>
                    <select name="jenis_kelamin" class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Pekerjaan
                    </label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $profile->pekerjaan ?? '') }}"
                           placeholder="Contoh: Wiraswasta, Karyawan Swasta, Petani"
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                </div>

            </div>

            <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3 pt-4">Alamat Domisili Kependudukan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                        Alamat Lengkap / Nama Jalan & Gang
                    </label>
                    <textarea name="alamat" rows="2" 
                              class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">RT</label>
                    <input type="text" name="rt" value="{{ old('rt', $profile->rt ?? '') }}" maxlength="5"
                           placeholder="001" 
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">RW</label>
                    <input type="text" name="rw" value="{{ old('rw', $profile->rw ?? '') }}" maxlength="5"
                           placeholder="002" 
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Dusun / Lingkungan</label>
                    <input type="text" name="dusun" value="{{ old('dusun', $profile->dusun ?? '') }}"
                           placeholder="Nama Dusun" 
                           class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                </div>
            </div>

            <!-- Foto KTP Upload -->
            <div class="pt-4 border-t border-[#e1ede8]">
                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Unggah Foto Kartu Tanda Penduduk (KTP)
                </label>
                <div class="flex items-center gap-4">
                    <input type="file" name="foto_ktp" accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#0c3837] hover:file:bg-[#10b981] hover:file:text-white cursor-pointer">
                    @if(!empty($profile?->foto_ktp_path))
                        <a href="{{ asset('storage/'.$profile->foto_ktp_path) }}" target="_blank" class="text-xs font-bold text-[#10b981] shrink-0 underline">
                            Lihat KTP Terunggah &nearr;
                        </a>
                    @endif
                </div>
                <p class="text-[10px] text-[#64748b] mt-1">Format gambar (JPG, PNG, WebP) maksimal 3MB.</p>
                @error('foto_ktp') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" 
                        class="px-8 py-3.5 rounded-full bg-[#10b981] hover:bg-[#059669] text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                    Simpan Perubahan Profil
                </button>
            </div>

        </form>
    </div>

    <!-- Security & Password Change Card -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-[#e1ede8] shadow-sm space-y-6">
        <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3">Keamanan & Ganti Kata Sandi</h3>

        <form action="{{ route('masyarakat.profil.password') }}" method="POST" class="space-y-4 max-w-xl">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Kata Sandi Saat Ini
                </label>
                <input type="password" name="current_password" required
                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                @error('current_password') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Kata Sandi Baru
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
                @error('password') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Konfirmasi Kata Sandi Baru
                </label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs font-bold text-[#0c3837] focus:outline-none focus:border-[#10b981]">
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="px-6 py-3 rounded-full bg-[#0c3837] hover:bg-[#114443] text-white font-bold text-xs shadow-xs transition-all cursor-pointer">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
