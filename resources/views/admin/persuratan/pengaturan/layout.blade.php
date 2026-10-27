@props([
    'pageTitle' => 'Pengaturan Persuratan',
    'cardTitle' => 'Pengaturan Surat',
    'activeTab' => 'pengaturan',
    'cardActions' => null,
])

<x-persuratan.pengaturan-layout 
    :pageTitle="$pageTitle" 
    :cardTitle="$cardTitle" 
    :activeTab="$activeTab">
    @isset($cardActions)
        <x-slot:cardActions>{{ $cardActions }}</x-slot:cardActions>
    @endisset

    {{ $slot ?? '' }}
</x-persuratan.pengaturan-layout>
