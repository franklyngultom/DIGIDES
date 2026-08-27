# Front-End Design System & Custom Shadcn UI Rules

**Konteks:** Standar pengembangan antarmuka (UI/UX) aplikasi DIGIDES v2 mengacu pada referensi visual dashboard bertema *Eco-Smart Forest / Emerald / Lime*.

---

## 1. Prinsip Utama Desain Visual

1. **Inspirasi Referensi Visual:**
   - Dominasi warna hijau hutan (*Pine/Dark Teal* `#0c3837`, `#114443`), hijau zamrud segar (*Emerald* `#10b981`, `#34d399`), hijau sage lembut (*Sage* `#4fa394`), serta aksen lime cerah (*Chartreuse/Lime* `#d4ed31`, `#e2f48f`).
   - Card berlatar putih bersih (`#ffffff`) dengan border sangat lembut (`#e1ede8`) dan sudut membulat lebar (`rounded-3xl` / 24px).
   - Bentuk tombol & badge status mengusung geometri *pill-shape* (`rounded-full`).

2. **Kustomisasi Komponen Shadcn UI:**
   - Gunakan komponen berarsitektur Shadcn UI yang dikustomisasi untuk ekosistem Blade + Tailwind CSS.
   - **Tombol (Buttons):** Hindari tombol kotak bersudut tajam. Selalu gunakan `rounded-full`, padding horizontal proporsional (`px-5 py-2.5`), dan efek transisi hover halus.
   - **Tabel Data:** Baris tabel dengan hover effect lembut (`hover:bg-[#f7faf9]`), borderless cell dengan padding nyaman (`py-3.5 px-4`), dan tombol aksi berupa ikon pill-shaped.
   - **Modal / Dialog:** Gunakan backdrop blur (`backdrop-blur-sm bg-[#082424]/40`) dan transisi fade/scale yang memikat.
   - **Privacy Mode Toggle:** Komponen switch khusus di atas tabel kependudukan untuk menyamarkan 6 digit tengah NIK dan nomor kontak.

3. **Struktur Layout 3 Kolom:**
   - **Sidebar Ikon Kiri (Slim Sidebar):** Ikon vertikal ramping dengan indikator aktif berlingkar hijau lime.
   - **Workspace Tengah (Center Feed):** Berisi metrik produktivitas harian, kartu sparkline grafik, progress ring roket statistik, timeline jadwal/Gantt pelayanan, dan daftar aktivitas.
   - **Panel Kanan (Right Widget):** Widget profil pengguna (Jack Grealish / Staff Desa), jam layanan kantor (*Work Start 09:00 am - Work End 05:00 pm*), dan kartu ilustrasi lanskap pemandangan alam (*Scenic Landscape Art Card*).

4. **Tipografi & Ikonografi:**
   - Font sans-serif modern berkualitas tinggi (`Plus Jakarta Sans`, `Inter`, atau `Instrument Sans`).
   - Ikonografi konsisten menggunakan Lucide Icons / Heroicons (outline style dengan stroke 1.75px).

5. **Responsivitas & Micro-Animations:**
   - Tampilan wajib adaptif dari resolusi $1280 \times 720$ hingga layar monitor desktop besar $1920 \times 1080$.
   - Terapkan micro-interaction pada hover tombol, transisi tab, dan feedback saat scan QR presensi.
