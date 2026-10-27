<x-layouts.auth>
    @php
        $desa = \App\Models\DesaProfile::current();
    @endphp
    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#e1ede8] shadow-xl backdrop-blur-md relative">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
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
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#e2f0ed] text-[#114443] border border-[#10b981]/30">
                Portal Internal
            </span>
            <h1 class="text-2xl font-extrabold text-[#0c3837] tracking-tight mt-3">DIGIDES v2.0</h1>
            <p class="text-xs text-[#64748b] mt-1">{{ $desa->nama_desa ? 'Kantor Desa ' . $desa->nama_desa : 'Digitalisasi Administrasi & Pelayanan Kantor Desa' }}</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.store') }}" class="space-y-5" x-data="{ showPass: false }">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-[#0c3837] uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>
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
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="16" r="1"/>
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-10 pr-11 py-3 bg-[#f7faf9] border border-[#e1ede8] rounded-2xl text-sm text-[#0f172a] focus:bg-white focus:outline-none focus:border-[#10b981] focus:ring-2 focus:ring-[#10b981]/20 transition-all">
                    
                    <!-- Toggle Visibility Button -->
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
                    <svg class="w-4 h-4 text-emerald-400 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
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
