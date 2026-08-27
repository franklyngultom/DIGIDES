---
name: front-end-design
description: Standar dan panduan implementasi Front-End Design DIGIDES v2 berbasis Custom Shadcn UI, Tailwind CSS, Blade Components, dan Estetika Hijau Alami (Pine/Emerald/Lime) sesuai referensi dashboard visual.
---

# Front-End Design Skill (DIGIDES Custom Shadcn UI)

Skill ini memberikan panduan komprehensif, arsitektur komponen, serta implementasi siap pakai untuk membangun antarmuka pengguna (UI/UX) sistem DIGIDES yang modern, bersih, dan memukau (*WOW factor*) sesuai gambar referensi dashboard visual.

---

## 1. Desain Sistem & Identitas Visual

### Palet Warna Resmi (Nature Eco Forest Palette)
- **Deep Pine (Warna Identitas Utama & Tombol Primer):** `#0C3837`, `#114443`, `#1B5E5C`
- **Fresh Emerald & Sage (Aksen Sekunder & Kurva Sparkline):** `#10B981`, `#34D399`, `#4FA394`, `#8BC3B8`, `#E2F0ED`
- **Neon Lime / Chartreuse (Highlight Pill, Badge Produktif & Sparkline):** `#D4ED31`, `#E2F48F`, `#A3E635`, `#F6FCE2`
- **Surface & Backgrounds:**
  - Workspace Background: `#F7FAF9`
  - Card Surface: `#FFFFFF`
  - Soft Container: `#EDF5F2`
  - Border Subtle: `#E1EDE8`
- **Teks & Kontras:**
  - Teks Utama: `#0F172A` (Slate 900)
  - Teks Muted: `#64748B` (Slate 500)
  - Teks Putih: `#FFFFFF`

---

## 2. Struktur Layout 3 Kolom Modern (Dashboard Blueprint)

```html
<div class="min-h-screen bg-[#f7faf9] text-[#0f172a] flex">
    <!-- 1. Left Mini Sidebar -->
    <aside class="w-20 bg-white border-r border-[#e1ede8] flex flex-col items-center py-6 gap-8 shrink-0">
        <!-- Logo Desa -->
        <div class="w-12 h-12 rounded-2xl bg-[#e2f0ed] flex items-center justify-center text-[#114443]">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
        </div>

        <!-- Nav Ikon -->
        <nav class="flex flex-col gap-5 items-center flex-1">
            <a href="#" class="w-12 h-12 rounded-2xl bg-[#114443] text-[#d4ed31] flex items-center justify-center shadow-sm">
                <!-- Dashboard Icon -->
            </a>
            <a href="#" class="w-12 h-12 rounded-2xl text-[#64748b] hover:bg-[#f7faf9] hover:text-[#114443] flex items-center justify-center transition-colors">
                <!-- Kependudukan Icon -->
            </a>
            <!-- Icon lainnya: Persuratan, Kelembagaan, Keuangan, Absensi -->
        </nav>

        <!-- Settings Icon Bottom -->
        <div class="flex flex-col gap-4">
            <a href="#" class="w-12 h-12 rounded-2xl text-[#64748b] hover:text-[#114443] flex items-center justify-center">
                <!-- Cog Icon -->
            </a>
        </div>
    </aside>

    <!-- 2. Main Workspace (Center Feed) -->
    <main class="flex-1 p-8 overflow-y-auto space-y-8">
        <!-- Top Search Bar -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#0c3837] tracking-tight">Working Productivity</h1>
                <p class="text-sm text-[#64748b] mt-1">Let's check your village service progress</p>
            </div>
            <div class="relative w-80">
                <input type="text" placeholder="Search for anything..." 
                       class="w-full pl-11 pr-4 py-3 bg-white border border-[#e1ede8] rounded-2xl text-sm focus:outline-none focus:border-[#10b981] shadow-sm">
                <svg class="w-5 h-5 text-[#94a3b8] absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Metric Cards with Sparklines -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Lime Accent -->
            <div class="bg-gradient-to-r from-[#e2f48f] to-[#f4fce0] p-6 rounded-3xl border border-[#d4ed31]/40 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <div class="bg-white/80 backdrop-blur-sm px-3.5 py-1.5 rounded-2xl text-center shadow-xs">
                        <span class="text-xs font-semibold text-[#64748b] block">Mon</span>
                        <span class="text-xl font-bold text-[#0c3837]">18</span>
                    </div>
                    <span class="px-3 py-1 bg-white/70 backdrop-blur-sm rounded-full text-xs font-bold text-[#0c3837]">86% Productive</span>
                </div>
                <!-- Sparkline curve SVG -->
                <div class="grid grid-cols-2 gap-4 mt-4 pt-3 border-t border-[#0c3837]/10">
                    <div>
                        <span class="text-xs text-[#0c3837]/70 block font-medium">Productive Time</span>
                        <span class="text-lg font-bold text-[#0c3837]">5h 12m</span>
                    </div>
                    <div>
                        <span class="text-xs text-[#0c3837]/70 block font-medium">Time at Work</span>
                        <span class="text-lg font-bold text-[#0c3837]">5h 45m</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Sage Green -->
            <div class="bg-[#4fa394] text-white p-6 rounded-3xl shadow-sm relative overflow-hidden">
                <!-- Info Mon 19, 72% Productive, 4h 10m / 6h 30m -->
            </div>

            <!-- Card 3: Deep Dark Pine -->
            <div class="bg-[#0c3837] text-white p-6 rounded-3xl shadow-sm relative overflow-hidden">
                <!-- Info Mon 20, 60% Productive, 3h 05m / 7h 10m -->
            </div>
        </div>

        <!-- Middle Section: Statistics Ring & Upcoming Schedule -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Circular Progress Ring Widget -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm">
                <h3 class="text-lg font-bold text-[#0c3837] mb-6">Statistics on July</h3>
                <!-- Ring SVG and Task Progress Bars -->
            </div>

            <!-- Upcoming Schedule List -->
            <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#0c3837] mb-4">Upcoming Schedule</h3>
                    <!-- Schedule items -->
                </div>
                <button class="w-full mt-6 py-3.5 bg-[#114443] hover:bg-[#0c3837] text-white font-semibold rounded-full shadow-sm transition-all text-sm">
                    See All Activity
                </button>
            </div>
        </div>

        <!-- Timeline Gantt Activity Section -->
        <div class="bg-white p-6 rounded-3xl border border-[#e1ede8] shadow-sm">
            <h3 class="text-lg font-bold text-[#0c3837] mb-4">Upcoming Activity</h3>
            <!-- Horizontal Gantt activity bars -->
        </div>
    </main>

    <!-- 3. Right Intelligence & Profile Panel -->
    <aside class="w-88 bg-gradient-to-b from-[#114443] to-[#0c3837] text-white p-6 flex flex-col justify-between shrink-0">
        <!-- Profile Widget -->
        <div class="flex flex-col items-center text-center pt-4">
            <div class="w-20 h-20 rounded-full border-2 border-[#d4ed31] p-1 mb-3">
                <img src="/images/avatar-jack.png" class="w-full h-full rounded-full object-cover" alt="User Avatar">
            </div>
            <h2 class="text-xl font-bold text-white">Jack Grealish</h2>
            <p class="text-xs text-[#8bc3b8] mt-0.5">Staff Pelayanan Kantor Desa</p>
            <button class="mt-4 px-6 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-xs font-semibold tracking-wide transition-colors">
                Edit Profile
            </button>
        </div>

        <!-- Working Hours Widget -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 my-6 border border-white/10">
            <span class="text-xs text-[#8bc3b8] font-medium block mb-2 text-center">Working hours:</span>
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-white/15 rounded-xl py-2 px-1">
                    <span class="text-[10px] text-[#8bc3b8] block uppercase">Work Start</span>
                    <span class="text-sm font-bold text-white">09:00 am</span>
                </div>
                <div class="bg-white/15 rounded-xl py-2 px-1">
                    <span class="text-[10px] text-[#8bc3b8] block uppercase">Work End</span>
                    <span class="text-sm font-bold text-white">05:00 pm</span>
                </div>
            </div>
        </div>

        <!-- Scenic Landscape Artwork Card -->
        <div class="relative rounded-2xl overflow-hidden h-56 border border-white/15 shadow-inner">
            <img src="/images/sukabumi-scenic.png" class="w-full h-full object-cover" alt="Sukabumi Scenic Artwork">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0c3837]/90 via-transparent to-transparent flex flex-col justify-end p-4">
                <h4 class="text-xl font-extrabold text-white tracking-wide">Sukabumi City</h4>
                <p class="text-xs text-[#8bc3b8]">Sukabumi, Indonesia • GMT+7</p>
            </div>
        </div>
    </aside>
</div>
```

---

## 3. Komponen Khusus DIGIDES (Blade Implementation)

### 1. Privacy Toggle Switch (Sembunyikan NIK)
```html
<div x-data="{ privacyMode: true }" class="flex items-center gap-3">
    <button @click="privacyMode = !privacyMode; document.body.classList.toggle('privacy-active')" 
            :class="privacyMode ? 'bg-[#10b981]' : 'bg-slate-300'"
            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
        <span :class="privacyMode ? 'translate-x-5' : 'translate-x-0'" 
              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
    </button>
    <span class="text-xs font-semibold text-[#0c3837]" x-text="privacyMode ? 'Privacy Mode Active (NIK Disembunyikan)' : 'Privacy Mode Nonaktif'"></span>
</div>
```

### 2. Modal Pratinjau Dokumen PDF
- Gunakan container modal berlatar `backdrop-blur-md bg-[#082424]/40` dengan container `rounded-3xl border border-[#e1ede8]`.
- Iframe responsive 100% height untuk rendering cepat file PDF.
