<x-layouts.app>
    <x-slot:title>Edit Pengguna - {{ $user->name }}</x-slot:title>

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Dashboard</a>
                <span class="text-xs text-[#64748b]">/</span>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-[#64748b] hover:text-[#10b981]">Manajemen Pengguna</a>
                <span class="text-xs text-[#64748b]">/</span>
                <span class="text-xs font-bold text-[#0c3837]">Edit: {{ $user->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3837] tracking-tight">Edit Akun Staf & Hak Akses</h1>
            <p class="text-xs sm:text-sm text-[#64748b] mt-0.5">Perbarui kredensial, peran, dan matriks hak akses khusus</p>
        </div>

        <a href="{{ route('admin.users.index') }}" 
           class="px-4 py-2 bg-white hover:bg-[#f7faf9] text-[#0c3837] border border-[#e1ede8] text-xs font-bold rounded-full shadow-xs flex items-center gap-2 transition-all">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <x-card class="space-y-5">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3">Informasi Akun Staf</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Nama Lengkap Petugas *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Alamat Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password (Optional on edit) -->
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Kata Sandi Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                            @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru"
                                   class="w-full px-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-xs focus:bg-white focus:outline-none focus:border-[#10b981] transition-all">
                        </div>
                    </div>
                </x-card>

                <!-- Granular Permissions Section -->
                <x-card class="space-y-4">
                    <div class="flex items-center justify-between border-b border-[#e1ede8] pb-3">
                        <div>
                            <h3 class="text-base font-bold text-[#0c3837]">Matriks Hak Akses Khusus (Granular Permissions)</h3>
                            <p class="text-xs text-[#64748b]">Tentukan akses modul spesifik di luar role bawaan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($permissionsGrouped as $groupName => $perms)
                        <div class="p-4 rounded-2xl bg-[#f7faf9] border border-[#e1ede8]">
                            <h4 class="text-xs font-extrabold text-[#0c3837] uppercase tracking-wider mb-3">{{ $groupName }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                                @foreach ($perms as $perm)
                                <label class="inline-flex items-center gap-2 p-2 rounded-xl bg-white border border-[#e1ede8] hover:border-[#10b981] cursor-pointer transition-colors text-xs">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                           {{ in_array($perm->name, old('permissions', $userDirectPermissions)) ? 'checked' : '' }}
                                           class="w-3.5 h-3.5 rounded text-[#114443] border-[#e1ede8] focus:ring-[#10b981]">
                                    <span class="text-slate-700 font-mono text-[11px]">{{ $perm->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            <!-- Right Col: Role & Status -->
            <div class="space-y-6">
                <!-- Role Assignment Card -->
                <x-card class="space-y-4">
                    <h3 class="text-base font-bold text-[#0c3837] border-b border-[#e1ede8] pb-3">Peran & Status</h3>

                    <!-- Current Avatar Preview -->
                    <div class="flex items-center gap-4 p-3 bg-[#f7faf9] rounded-2xl border border-[#e1ede8]">
                        <img src="{{ $user->avatar_url }}" class="w-12 h-12 rounded-full object-cover border border-[#e1ede8]" alt="{{ $user->name }}">
                        <div>
                            <span class="text-xs font-bold text-[#0c3837] block">Foto Profil Aktif</span>
                            <span class="text-[11px] text-[#64748b]">Unggah foto baru untuk mengganti</span>
                        </div>
                    </div>

                    <!-- Role Selector -->
                    <div>
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Pilih Peran (Role) *</label>
                        <div class="space-y-2">
                            @foreach ($roles as $role)
                            <label class="flex items-center justify-between p-3 rounded-2xl border border-[#e1ede8] hover:border-[#10b981] cursor-pointer bg-[#f7faf9] has-checked:bg-[#e2f0ed] has-checked:border-[#10b981] transition-all">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="role" value="{{ $role->name }}" 
                                           {{ old('role', $userRole) === $role->name ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#114443] border-[#e1ede8] focus:ring-[#10b981]">
                                    <div>
                                        <span class="text-xs font-bold text-[#0c3837] block">{{ $role->name }}</span>
                                        <span class="text-[10px] text-[#64748b]">
                                            {{ $role->name === 'Admin Desa' ? 'Akses penuh seluruh modul sistem' : 'Akses operasional harian' }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="pt-2">
                        <label class="flex items-center justify-between p-3 rounded-2xl border border-[#e1ede8] bg-[#f7faf9] cursor-pointer">
                            <span class="text-xs font-bold text-[#0c3837]">Status Akun Aktif</span>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-[#114443] border-[#e1ede8] focus:ring-[#10b981]">
                        </label>
                    </div>

                    <!-- Avatar Upload -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Ganti Foto Profil</label>
                        <input type="file" name="avatar" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#e2f0ed] file:text-[#114443] hover:file:bg-[#d0e6e1] cursor-pointer">
                        <p class="text-[10px] text-[#64748b] mt-1">Maksimal 2 MB (JPG, PNG, WebP)</p>
                    </div>
                </x-card>

                <!-- Actions -->
                <x-card class="space-y-3">
                    <button type="submit" class="w-full py-3 px-6 bg-[#114443] hover:bg-[#0c3837] text-white font-bold rounded-full shadow-md transition-all text-xs cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-[#d4ed31]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Perbarui Data Staf</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="w-full py-2.5 px-6 bg-white hover:bg-[#f7faf9] text-[#64748b] border border-[#e1ede8] font-bold rounded-full transition-all text-xs text-center block">
                        Batalkan
                    </a>
                </x-card>
            </div>
        </div>
    </form>
</x-layouts.app>
