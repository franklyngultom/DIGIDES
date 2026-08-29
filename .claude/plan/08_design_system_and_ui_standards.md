# Plan 08: Standar Desain Sistem & Kustomisasi Shadcn UI (DIGIDES Theme)

**Modul:** Design System Standard, Custom Shadcn UI Library, Emerald/Pine/Lime Theme, Layout 3-Kolom  
**Referensi Visual:** [Dashboard Reference Image](file:///c:/laragon/www/DIGIDES/.claude/plan/PRD_DIGIDES_v2_Final.md) (Working Productivity / DIGIDES Nature-Eco Modern Aesthetic)

---

## 1. Konsep & Filosofi Desain

Berdasarkan referensi visual dashboard yang disertakan:
- **Suasana Visual (Look & Feel):** Segar, bersih, modern, dan bernuansa hijau alami (*Eco-smart Government Workspace*).
- **Karakteristik Elemen:**
  - *Sudut Membulat Halus:* `rounded-2xl` (16px) hingga `rounded-3xl` (24px) untuk card & panel, serta `rounded-full` (pill shape) untuk tombol & badge status.
  - *Bayangan Lembut (Soft Shadow):* `shadow-sm` hingga `shadow-md` dengan rona warna pine/emerald sangat tipis (`rgba(13, 56, 56, 0.05)`).
  - *Struktur 3 Kolom Responsif:*
    1. **Kolom Kiri:** Mini Sidebar Ikon navigasi vertikal.
    2. **Kolom Tengah (Main Workspace):** Header pencarian, kartu produktivitas harian, ring statistik bulanan, grafik timeline/Gantt jadwal pelayanan, dan daftar kegiatan.
    3. **Kolom Kanan (Right Widget Panel):** Profil aparatur dengan avatar, jam layanan kantor (*Work Start 09:00 am - Work End 05:00 pm*), dan kartu lanskap ilustrasi wilayah (*Scenic Village Landscape Card*).

---

## 2. Palet Warna & Token Desain (Color Tokens)

```css
:root {
  /* Primary Pine & Deep Forest (Identitas Utama, Sidebar Active, Tombol Primer) */
  --color-pine-900: #082424;
  --color-pine-800: #0c3837;
  --color-pine-700: #114443;
  --color-pine-600: #1b5e5c;

  /* Secondary Fresh Emerald & Sage Green (Garis kurva, status aktif, hover) */
  --color-emerald-500: #10b981;
  --color-emerald-400: #34d399;
  --color-sage-500: #4fa394;
  --color-sage-300: #8bc3b8;
  --color-sage-100: #e2f0ed;

  /* Accent Lime & Chartreuse (Highlight Pill, Sparkline Curve, Alert Positif) */
  --color-lime-400: #d4ed31;
  --color-lime-300: #e2f48f;
  --color-lime-200: #edf8ba;
  --color-lime-100: #f6fce2;

  /* Neutral Backgrounds & Surface Cards */
  --color-bg-workspace: #f7faf9;
  --color-surface-card: #ffffff;
  --color-surface-soft: #edf5f2;
  --color-border-subtle: #e1ede8;

  /* Text & Typography */
  --color-text-main: #0f172a;
  --color-text-muted: #64748b;
  --color-text-light: #94a3b8;
  --color-text-on-dark: #ffffff;
}
```

---

## 3. Komponen Shadcn UI yang Dikustomisasi

| Komponen Shadcn | Kustomisasi Khusus DIGIDES (Sesuai Referensi Visual) |
|---|---|
| **`Card`** | `bg-white rounded-3xl border border-[#e1ede8] p-6 shadow-sm` |
| **`Button`** | Default: `rounded-full bg-[#114443] text-white hover:bg-[#0c3837] px-6 py-2.5 font-medium transition-all shadow-sm`<br>Accent Lime: `bg-[#d4ed31] text-[#0c3837] hover:bg-[#c3de26] rounded-full`<br>Ghost: `hover:bg-[#eef6f3] text-[#114443] rounded-full` |
| **`Badge / Pill`** | `rounded-full px-3 py-1 text-xs font-semibold`<br>- Active/Productive: `bg-[#e2f48f] text-[#0c3837]`<br>- Progress: `bg-[#e2f0ed] text-[#114443]`<br>- Neutral: `bg-slate-100 text-slate-700` |
| **`Input`** | `rounded-2xl border-[#e1ede8] bg-[#f7faf9] focus:bg-white focus:border-[#10b981] px-4 py-2.5` dengan ikon prefix |
| **`Table`** | Borderless table rows, rounded container, hover highlight `hover:bg-[#f7faf9]`, sticky header dengan switch `Privacy Mode NIK` |
| **`Modal / Dialog`** | Backdrop blur (`backdrop-blur-sm bg-[#082424]/40`), container `rounded-3xl shadow-2xl border border-[#e1ede8]` |
| **`Progress Ring`** | Circular SVG ring dengan stroke gradient Sage-to-Emerald dan roket/ikon di bagian tengah |
| **`Gantt / Activity Bar`** | Garis timeline horizontal dengan milestone dots dan pill card aktivitas (*"Pelayanan Surat", "Rapat BPD", "Verifikasi Berkas"*) |
| **`Scenic Card`** | Card pemandangan wilayah desa dengan gradien pine-to-emerald, tipografi elegan ("Desa Digital Sukamaju • Sukabumi, Indonesia • GMT+7") |

---

## 4. Rincian Layout Antarmuka Utama

```text
+---+---------------------------------------------------------+-------------------------+
|   | [Search for anything...                              Q] |   [ > ]             [...] |
| I |                                                         |                         |
| K | Working Productivity                                    |        ( Avatar )       |
| O | Let's check your village service progress               |      Jack Grealish      |
| N |                                                         |   Staff Pelayanan Umum  |
|   | +-----------------------------------------------------+ |    [ Edit Profile ]     |
| S | | 18 | Productive (86%)  | 5h 12m    | 5h 45m         | |                         |
| I | |    | (Lime Green Card with Sparkline Curve)         | |      Working hours:     |
| D | +-----------------------------------------------------+ | +----------+----------+ |
| E | | 19 | Productive (72%)  | 4h 10m    | 6h 30m         | | |Work Start| Work End | |
| B | |    | (Sage Green Card with Sparkline Curve)         | | | 08:00 am | 16:00 pm | |
| A | +-----------------------------------------------------+ | +----------+----------+ |
| R | | 20 | Productive (60%)  | 3h 05m    | 7h 10m         | |                         |
|   | |    | (Dark Teal Card with Sparkline Curve)          | | +---------------------+ |
|   | +-----------------------------------------------------+ | |                     | |
|   |                                                         | |   SUKABUMI CITY     | |
|   | Statistics on July         Upcoming Schedule            | |   Desa Sukamaju     | |
|   | [  (O) Rocket Ring  ]      [#] Pelayanan Surat SKU      | |                     | |
|   | Tasks: -------- 40%        [#] Verifikasi NIK Ganda     | | (Scenic Vector Art  | |
|   | Selesai: ------ 80%        [#] Cetak Buku Ekspedisi     | |  with Nature Hues)  | |
|   | Jam Layanan: -- 25%        [   See All Activity   ]     | |                     | |
|   |                                                         | +---------------------+ |
|   | Upcoming Activity (Gantt-like Service Timeline)         |                         |
+---+---------------------------------------------------------+-------------------------+
```

---

## 5. Standardisasi Blade Component Library

Untuk efisiensi kode, berikut komponen Blade standar yang telah dibangun & terverifikasi:
- [x] `<x-ui.card>` (dengan varian default, lime, sage, pine, soft, glass)
- [x] `<x-ui.button>` (dengan varian primary/pine, lime, emerald, secondary/outline, ghost, danger)
- [x] `<x-ui.badge>` (dengan varian lime, emerald, sage, pine, amber, rose, slate serta opsi dot indicator)
- [x] `<x-ui.stat-card>` (dengan sparkline curve SVG dinamis, badge tanggal & metrik waktu produktif)
- [x] `<x-ui.progress-ring>` (dengan visual ring SVG donut gauge, persentase & label efisiensi)
- [x] `<x-ui.activity-timeline>` (linimasa timeline Gantt horizontal/vertikal dengan milestone dots & pill tags)
- [x] `<x-ui.profile-widget>` (widget profil aparatur dengan avatar lime border & status)
- [x] `<x-ui.scenic-card>` (kartu ilustrasi lanskap pemandangan desa & detail geolocation)
- [x] `<x-ui.modal-preview>` (modal dialog pratinjau dokumen dengan backdrop blur & iframe responsif)
- [x] `<x-ui.privacy-toggle>` (switch toggle mode privasi meja pelayanan untuk penyamaran NIK)

---

## 6. Rencana Pengujian (Verification Plan)

- [x] Kompilasi Blade component library tanpa error sintaks.
- [x] Render halaman Dashboard Produktivitas dengan layout 3-kolom dan komponen visual lengkap.
- [x] Pengujian unit & feature test untuk seluruh varian komponen UI (73 passed tests).

