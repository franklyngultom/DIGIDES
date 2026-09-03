<x-layouts.auth>
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#e1ede8] shadow-xl backdrop-blur-md relative">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#0c3837] to-[#114443] text-[#d4ed31] mx-auto flex items-center justify-center shadow-md mb-4 border border-[#1b5e5c]">
                <svg class="w-9 h-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30">
                Portal Internal Staf
            </span>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight mt-3">DIGIDES v2.0</h1>
            <p class="text-xs text-[#64748b] mt-1">Digitalisasi Administrasi & Pelayanan Kantor Desa</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.store') }}" class="space-y-5" x-data="{ showPass: false }">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Alamat Email Staf</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           placeholder="nama@desa.id"
                           class="w-full pl-10 pr-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                </div>
                @error('email')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password with Toggle -->
            <div>
                <label for="password" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-10 pr-11 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                    
                    <!-- Toggle Visibility Button -->
                    <button type="button" @click="showPass = !showPass" tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#114443] transition-colors cursor-pointer"
                            :title="showPass ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#114443] border-[#e1ede8] focus:ring-[#10b981]">
                    <span class="text-xs text-slate-600 font-medium">Ingat Sesi Saya</span>
                </label>
                <span class="text-[11px] text-[#10b981] font-semibold">Khusus Internal Kantor</span>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-[#114443] to-[#0c3837] hover:from-[#0c3837] hover:to-[#082424] text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 group cursor-pointer text-sm">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4 text-[#d4ed31] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Register Link -->
        <div class="mt-8 pt-6 border-t border-[#e1ede8] text-center">
            <p class="text-xs text-slate-500">
                Belum memiliki akun terdaftar?
                <a href="{{ route('register') }}" class="font-bold text-[#114443] hover:text-[#10b981] hover:underline transition-colors ml-1">
                    Daftar Akun Baru
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth>
