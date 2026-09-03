<x-layouts.auth>
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#e1ede8] shadow-xl backdrop-blur-md relative">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#0c3837] to-[#114443] text-[#d4ed31] mx-auto flex items-center justify-center shadow-md mb-4 border border-[#1b5e5c]">
                <svg class="w-9 h-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30">
                Pendaftaran Akun
            </span>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight mt-3">Daftar Akun Baru</h1>
            <p class="text-xs text-[#64748b] mt-1">Lengkapi data diri untuk membuat akun staf pelayanan desa</p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register.store') }}" class="space-y-4" x-data="{ showPass: false, showPassConfirm: false }">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           placeholder="Nama lengkap staf"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('name')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           placeholder="nama@desa.id"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                           placeholder="081234567890"
                           class="w-full pl-10 pr-4 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('phone')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password with Toggle -->
            <div>
                <label for="password" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password"
                           placeholder="Minimal 8 karakter"
                           class="w-full pl-10 pr-11 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                    
                    <!-- Toggle Visibility Button -->
                    <button type="button" @click="showPass = !showPass" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#114443] transition-colors cursor-pointer"
                            :title="showPass ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'">
                        <!-- Eye Icon (Hidden state) -->
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <!-- Eye Slash Icon (Visible state) -->
                        <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation with Toggle -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <input id="password_confirmation" :type="showPassConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                           placeholder="Ulangi kata sandi"
                           class="w-full pl-10 pr-11 py-2.5 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                    
                    <!-- Toggle Visibility Button -->
                    <button type="button" @click="showPassConfirm = !showPassConfirm" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#114443] transition-colors cursor-pointer"
                            :title="showPassConfirm ? 'Sembunyikan Konfirmasi Kata Sandi' : 'Tampilkan Konfirmasi Kata Sandi'">
                        <!-- Eye Icon (Hidden state) -->
                        <svg x-show="!showPassConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <!-- Eye Slash Icon (Visible state) -->
                        <svg x-show="showPassConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-[#114443] to-[#0c3837] hover:from-[#0c3837] hover:to-[#082424] text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 group cursor-pointer text-sm">
                    <span>Daftarkan Akun Baru</span>
                    <svg class="w-4 h-4 text-[#d4ed31] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-6 pt-5 border-t border-[#e1ede8] text-center">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun terdaftar?
                <a href="{{ route('login') }}" class="font-bold text-[#114443] hover:text-[#10b981] hover:underline transition-colors ml-1">
                    Masuk ke Sistem
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth>
