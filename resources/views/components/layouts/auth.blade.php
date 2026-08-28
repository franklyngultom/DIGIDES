<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DIGIDES') }} - Masuk Sistem Administrasi Desa</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS / Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-[#f7faf9] text-[#0f172a] min-h-screen selection:bg-[#d4ed31] selection:text-[#0c3837]">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-radial from-[#114443]/15 via-[#f7faf9] to-[#f7faf9]">
        <!-- Decorative Ambient Orbs -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-[#10b981]/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-[#d4ed31]/20 blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">
            {{ $slot }}
        </div>
    </div>

    <!-- Interactive Toasts -->
    <x-toast />
</body>
</html>
