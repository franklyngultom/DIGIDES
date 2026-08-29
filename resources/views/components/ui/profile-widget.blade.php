@props([
    'user' => null,
    'desa' => null,
    'class' => '',
])

@php
$currentUser = $user ?? auth()->user();
$desaProfile = $desa ?? \App\Models\DesaProfile::current();
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center ' . $class]) }}>
    <div class="relative w-22 h-22 rounded-full border-2 border-[#d4ed31] p-1 mb-3 shadow-lg group">
        <img src="{{ file_exists(public_path('images/avatar-jack.jpg')) && $currentUser?->email === 'staff@desa.id' ? asset('images/avatar-jack.jpg') : ($currentUser?->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=114443&color=d4ed31') }}" 
             class="w-full h-full rounded-full object-cover shadow-inner" 
             alt="{{ $currentUser?->name ?? 'User Avatar' }}">
        <span class="absolute bottom-1 right-1 w-4 h-4 bg-[#10b981] border-2 border-[#114443] rounded-full" title="Online"></span>
    </div>
    <h2 class="text-lg font-bold text-white tracking-tight">{{ $currentUser?->name ?? 'Aparatur Desa' }}</h2>
    <p class="text-xs text-[#8bc3b8] mt-0.5">{{ $currentUser?->role_name ?? 'Staf Pelayanan' }}</p>
    <span class="inline-flex items-center gap-1 mt-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#d4ed31] text-[#0c3837]">
        {{ $desaProfile?->nama_desa ?? 'Desa Digital' }}
    </span>
</div>
