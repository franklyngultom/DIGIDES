<x-layouts.auth>
    @php
        $desa = \App\Models\DesaProfile::current();
    @endphp
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#e1ede8] shadow-xl backdrop-blur-md relative">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-white border border-[#e1ede8] shadow-sm p-1.5 mx-auto flex items-center justify-center mb-4 overflow-hidden">
                @if(!empty($desa->logo_url))
                    <img src="{{ $desa->logo_url }}" alt="Logo {{ $desa->nama_desa }}" class="w-full h-full object-contain">
                @elseif(file_exists(public_path('images/logo-provinsi-riau.png')))
                    <img src="{{ asset('images/logo-provinsi-riau.png') }}" alt="Logo {{ $desa->nama_desa ?? 'Desa' }}" class="w-full h-full object-contain">
                @else
                    <svg class="w-8 h-8 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                @endif
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30">
                Portal Warga Mandiri
            </span>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight mt-3">Daftar Akun Masyarakat</h1>
            <p class="text-xs text-[#64748b] mt-1">Lengkapi data diri Anda untuk mengakses layanan persuratan online desa</p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register.store') }}" class="space-y-4" x-data="{ showPass: false, showPassConfirm: false }">
            @csrf

            <!-- NIK 16 Digit -->
            <div>
                <label for="nik" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="14" x="2" y="5" rx="2"/>
                            <line x1="2" x2="22" y1="10" y2="10"/>
                        </svg>
                    </div>
                    <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required autofocus maxlength="16"
                           placeholder="16 digit nomor KTP"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('nik')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                           placeholder="Nama sesuai KTP"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('name')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           placeholder="nama@email.com"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Nomor WhatsApp / HP Aktif <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                           placeholder="081234567890"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('phone')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password with Toggle -->
            <div>
                <label for="password" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="16" r="1"/>
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password"
                           placeholder="Minimal 8 karakter"
                           class="w-full pl-10 pr-11 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                    
                    <button type="button" @click="showPass = !showPass" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#114443] transition-colors cursor-pointer"
                            :title="showPass ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'">
                        <svg x-show="!showPass" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPass" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" y1="2" x2="22" y2="22"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation with Toggle -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">
                    Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="16" r="1"/>
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input id="password_confirmation" :type="showPassConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                           placeholder="Ulangi kata sandi"
                           class="w-full pl-10 pr-11 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                    
                    <button type="button" @click="showPassConfirm = !showPassConfirm" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#114443] transition-colors cursor-pointer"
                            :title="showPassConfirm ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'">
                        <svg x-show="!showPassConfirm" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPassConfirm" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                            <line x1="2" y1="2" x2="22" y2="22"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-[#114443] to-[#0c3837] hover:from-[#0c3837] hover:to-[#082424] text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 group cursor-pointer text-sm">
                    <span>Daftar Akun Masyarakat</span>
                    <svg class="w-4 h-4 text-emerald-400 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-6 pt-5 border-t border-[#e1ede8] text-center">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun terdaftar?
                <a href="{{ route('login') }}" class="font-bold text-[#114443] hover:text-[#10b981] hover:underline transition-colors ml-1">
                    Masuk ke Portal
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth>
