@props([
    'pageTitle' => 'Pengaturan Persuratan',
    'cardTitle' => 'Pengaturan Surat',
    'activeTab' => 'pengaturan',
    'cardActions' => null,
])

<x-layouts.app :title="$pageTitle">
    <div class="space-y-5">
        <!-- Breadcrumb & Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-[#e1ede8] shadow-2xs">
            <div class="flex items-center gap-2 text-xs text-[#64748b]">
                <a href="{{ route('dashboard') }}" class="hover:text-[#10b981] font-medium">Dashboard</a>
                <span>/</span>
                <a href="{{ route('persuratan.arsip.index') }}" class="hover:text-[#10b981] font-medium">Persuratan</a>
                <span>/</span>
                <span class="font-bold text-[#0c3837]">Pengaturan Surat</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-[#edf5f2] text-[#114443] text-xs font-bold border border-[#10b981]/20 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                    Modul Persuratan Desa
                </span>
            </div>
        </div>

        <!-- Main Grid Layout: Left Submenu + Right Card Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            <!-- 1. Left Secondary Navigation (Sesuai Referensi Gambar) -->
            <div class="lg:col-span-3 bg-white rounded-2xl border border-[#e1ede8] shadow-sm overflow-hidden sticky top-20">
                <!-- Header Submenu -->
                <div class="p-4 bg-[#f7faf9] border-b border-[#e1ede8] flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-[#114443] text-emerald-300 flex items-center justify-center shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xs font-extrabold text-[#0c3837] uppercase tracking-wider">Menu Persuratan</h2>
                        <span class="text-[10px] text-[#64748b]">Konfigurasi & Format Standar</span>
                    </div>
                </div>

                <!-- Nav List Items -->
                <nav class="p-2 space-y-1">
                    <!-- Tab 1: Pengaturan Surat -->
                    <a href="{{ route('persuratan.pengaturan.index') }}" 
                       class="px-3.5 py-2.5 rounded-xl flex items-center justify-between transition-all text-xs font-bold {{ $activeTab === 'pengaturan' ? 'bg-[#f97316] text-white shadow-xs' : 'text-[#475569] hover:bg-[#edf5f2] hover:text-[#114443]' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activeTab === 'pengaturan' ? 'bg-white' : 'bg-[#94a3b8]' }}"></span>
                            <span>Pengaturan Surat</span>
                        </div>
                        @if($activeTab === 'pengaturan')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </a>

                    <!-- Tab 2: Format Cetak Surat -->
                    <a href="{{ route('persuratan.pengaturan.format-cetak') }}" 
                       class="px-3.5 py-2.5 rounded-xl flex items-center justify-between transition-all text-xs font-bold {{ $activeTab === 'format-cetak' ? 'bg-[#f97316] text-white shadow-xs' : 'text-[#475569] hover:bg-[#edf5f2] hover:text-[#114443]' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activeTab === 'format-cetak' ? 'bg-white' : 'bg-[#94a3b8]' }}"></span>
                            <span>Format Cetak Surat</span>
                        </div>
                        @if($activeTab === 'format-cetak')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </a>

                    <!-- Tab 3: Penomoran Surat -->
                    <a href="{{ route('persuratan.pengaturan.penomoran') }}" 
                       class="px-3.5 py-2.5 rounded-xl flex items-center justify-between transition-all text-xs font-bold {{ $activeTab === 'penomoran' ? 'bg-[#f97316] text-white shadow-xs' : 'text-[#475569] hover:bg-[#edf5f2] hover:text-[#114443]' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activeTab === 'penomoran' ? 'bg-white' : 'bg-[#94a3b8]' }}"></span>
                            <span>Penomoran Surat</span>
                        </div>
                        @if($activeTab === 'penomoran')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </a>

                    <!-- Tab 4: Kop Surat -->
                    <a href="{{ route('persuratan.pengaturan.kop-surat') }}" 
                       class="px-3.5 py-2.5 rounded-xl flex items-center justify-between transition-all text-xs font-bold {{ $activeTab === 'kop-surat' ? 'bg-[#f97316] text-white shadow-xs' : 'text-[#475569] hover:bg-[#edf5f2] hover:text-[#114443]' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activeTab === 'kop-surat' ? 'bg-white' : 'bg-[#94a3b8]' }}"></span>
                            <span>Kop Surat</span>
                        </div>
                        @if($activeTab === 'kop-surat')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </a>

                    <!-- Tab 5: Tanda Tangan -->
                    <a href="{{ route('persuratan.pengaturan.tanda-tangan') }}" 
                       class="px-3.5 py-2.5 rounded-xl flex items-center justify-between transition-all text-xs font-bold {{ $activeTab === 'tanda-tangan' ? 'bg-[#f97316] text-white shadow-xs' : 'text-[#475569] hover:bg-[#edf5f2] hover:text-[#114443]' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activeTab === 'tanda-tangan' ? 'bg-white' : 'bg-[#94a3b8]' }}"></span>
                            <span>Tanda Tangan</span>
                        </div>
                        @if($activeTab === 'tanda-tangan')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </a>
                </nav>

                <!-- Shortcut ke Layanan Walk-In -->
                <div class="p-3 border-t border-[#e1ede8] bg-[#f7faf9]">
                    <a href="{{ route('persuratan.create') }}" 
                       class="w-full px-3 py-2 rounded-xl bg-white hover:bg-[#edf5f2] text-[#114443] border border-[#e1ede8] text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        <span>Halaman Pelayanan</span>
                    </a>
                </div>
            </div>

            <!-- 2. Right Main Card Content -->
            <div class="lg:col-span-9 bg-white rounded-2xl border border-[#e1ede8] shadow-sm overflow-hidden">
                <!-- Top Card Header Pine Theme (Persis Referensi Screenshot) -->
                <div class="bg-[#1b5e5c] px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b-4 border-[#f59e0b]">
                    <h1 class="text-lg font-extrabold text-white tracking-wide">
                        {{ $cardTitle }}
                    </h1>

                    <div class="flex items-center gap-2.5">
                        {{ $cardActions ?? '' }}
                    </div>
                </div>

                <!-- Card Body Form Content -->
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
