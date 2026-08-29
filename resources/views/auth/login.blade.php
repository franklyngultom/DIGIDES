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
        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
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

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-10 pr-4 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
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

        <!-- Development Quick Logins -->
        <div class="mt-8 pt-6 border-t border-[#e1ede8] text-center">
            <span class="text-[11px] font-semibold text-slate-400 block mb-3 uppercase tracking-wider">Akses Uji Coba Cepat</span>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="document.getElementById('email').value='admin@desa.id'; document.getElementById('password').value='password';"
                        class="p-2 rounded-2xl bg-[#e2f0ed] hover:bg-[#d0e6e1] text-[#114443] font-semibold transition-colors text-center border border-[#10b981]/20 cursor-pointer">
                    Admin Desa
                </button>
                <button type="button" onclick="document.getElementById('email').value='staff@desa.id'; document.getElementById('password').value='password';"
                        class="p-2 rounded-2xl bg-[#f6fce2] hover:bg-[#edf9ca] text-[#0c3837] font-semibold transition-colors text-center border border-[#d4ed31]/50 cursor-pointer">
                    Staff Pelayanan
                </button>
            </div>
        </div>
    </div>
</x-layouts.auth>
