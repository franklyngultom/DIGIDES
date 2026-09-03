@props([
    'user' => null,
    'desa' => null,
    'class' => '',
])

@php
$currentUser = $user ?? auth()->user();
$desaProfile = $desa ?? \App\Models\DesaProfile::current();
$isStaff = $currentUser && $currentUser->hasRole('Staff Desa');
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center ' . $class]) }}>
    <div class="relative w-22 h-22 rounded-full border-2 border-[#d4ed31] p-1 mb-3 shadow-lg group">
        <img src="{{ $currentUser?->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=114443&color=d4ed31' }}" 
             class="w-full h-full rounded-full object-cover shadow-inner" 
             alt="{{ $currentUser?->name ?? 'User Avatar' }}">
        
        <span class="absolute bottom-1 right-1 w-4 h-4 bg-[#10b981] border-2 border-[#114443] rounded-full" title="Online"></span>

        @if($isStaff)
        <button type="button" 
                @click="$dispatch('open-avatar-modal')"
                class="absolute inset-0 rounded-full bg-black/55 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-all duration-200 cursor-pointer text-white"
                title="Ganti Foto Profil">
            <svg class="w-5 h-5 text-[#d4ed31] drop-shadow-xs mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-[9px] font-bold text-[#d4ed31] uppercase tracking-wider">Ganti Foto</span>
        </button>
        @endif
    </div>
    
    <div class="flex items-center gap-1.5 justify-center">
        <h2 class="text-lg font-bold text-white tracking-tight">{{ $currentUser?->name ?? 'Aparatur Desa' }}</h2>
        @if($isStaff)
        <button type="button" 
                @click="$dispatch('open-avatar-modal')"
                class="text-[#8bc3b8] hover:text-[#d4ed31] transition-colors cursor-pointer p-0.5"
                title="Ganti Foto Profil Staff">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </button>
        @endif
    </div>

    <p class="text-xs text-[#8bc3b8] mt-0.5">{{ $currentUser?->role_name ?? 'Staf Pelayanan' }}</p>
    <span class="inline-flex items-center gap-1 mt-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#d4ed31] text-[#0c3837]">
        {{ $desaProfile?->nama_desa ?? 'Desa Digital' }}
    </span>
</div>
