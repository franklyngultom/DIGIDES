<?php

namespace Database\Seeders;

use App\Models\Aparatur;
use App\Models\Institution;
use App\Models\InstitutionActivity;
use App\Models\InstitutionAgenda;
use App\Models\InstitutionDecision;
use App\Models\InstitutionMember;
use App\Models\Penduduk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $penduduks = Penduduk::limit(30)->get();

        // Seed Aparatur Pemerintah Desa if empty
        if (Aparatur::count() === 0 && $penduduks->isNotEmpty()) {
            $aparatList = [
                ['jabatan' => 'Kepala Desa', 'nip' => '197505102005011002', 'status_kepegawaian' => 'perangkat_desa'],
                ['jabatan' => 'Sekretaris Desa', 'nip' => '198208152008022001', 'status_kepegawaian' => 'pns'],
                ['jabatan' => 'Kaur Keuangan', 'nip' => '198811202010011004', 'status_kepegawaian' => 'perangkat_desa'],
                ['jabatan' => 'Kaur Umum & Perencanaan', 'nip' => '199003122015022003', 'status_kepegawaian' => 'perangkat_desa'],
                ['jabatan' => 'Kasi Pemerintahan', 'nip' => '198604052009011007', 'status_kepegawaian' => 'perangkat_desa'],
                ['jabatan' => 'Kasi Kesejahteraan & Pelayanan', 'nip' => '199201252018032001', 'status_kepegawaian' => 'pppk'],
                ['jabatan' => 'Kepala Dusun 1 (Cikole)', 'nip' => null, 'status_kepegawaian' => 'perangkat_desa'],
                ['jabatan' => 'Kepala Dusun 2 (Sukamaju)', 'nip' => null, 'status_kepegawaian' => 'perangkat_desa'],
            ];

            foreach ($aparatList as $idx => $ap) {
                if (isset($penduduks[$idx])) {
                    Aparatur::create([
                        'penduduk_id' => $penduduks[$idx]->id,
                        'nip' => $ap['nip'],
                        'jabatan' => $ap['jabatan'],
                        'qr_token' => Str::random(32),
                        'jam_masuk_standar' => '08:00:00',
                        'jam_pulang_standar' => '16:00:00',
                        'toleransi_terlambat_menit' => 15,
                        'status_kepegawaian' => $ap['status_kepegawaian'],
                        'status_aktif' => true,
                    ]);
                }
            }
        }

        $institutionsData = [
            [
                'nama_lembaga' => 'Badan Permusyawaratan Desa',
                'singkatan' => 'BPD',
                'slug' => 'bpd',
                'kategori' => 'pemerintahan',
                'nomor_sk_pendirian' => '140/01/SK-BPD/2024',
                'tanggal_sk' => '2024-01-15',
                'deskripsi' => 'Lembaga permusyawaratan desa yang menampung aspirasi masyarakat dan mengawasi jalannya pemerintahan desa.',
                'alamat_sekretariat' => 'Gedung BPD Lantai 2, Kantor Desa',
                'urutan' => 1,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Ketua BPD', 'nama' => 'Drs. H. Mulyadi, M.Si', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Wakil Ketua BPD', 'nama' => 'Ir. Bambang Sutrisno', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Sekretaris BPD', 'nama' => 'Siti Nurhaliza, S.Pd', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Ketua Bidang Pemerintahan', 'nama' => 'Agus Priyanto', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Ketua Bidang Pembangunan', 'nama' => 'Hendro Prasetyo', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                ],
                'decisions' => [
                    ['nomor' => '140/01/KEP-BPD/2026', 'tanggal' => '2026-01-20', 'tentang' => 'Persetujuan Penetapan Rancangan APBDes Tahun Anggaran 2026', 'uraian' => 'Menyetujui rancangan peraturan desa tentang APBDes 2026.', 'tahun' => 2026],
                    ['nomor' => '140/02/KEP-BPD/2026', 'tanggal' => '2026-02-15', 'tentang' => 'Hasil Evaluasi Kinerja Pemerintahan Desa Semester II', 'uraian' => 'Penyampaian rekomendasi hasil monitoring kegiatan desa.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Musyawarah Desa Pembahasan RKPDes 2026', 'tanggal' => '2026-01-10', 'lokasi' => 'Balai Desa', 'penanggung_jawab' => 'Ketua BPD', 'anggaran' => 3500000, 'sumber' => 'APBDes - DDS', 'output' => 'Berita acara kesepakatan RKPDes 2026', 'tahun' => 2026],
                    ['nama' => 'Monitoring Proyek Rabat Beton Dusun 2', 'tanggal' => '2026-02-22', 'lokasi' => 'Dusun Sukamaju', 'penanggung_jawab' => 'Ketua Bidang Pembangunan', 'anggaran' => 750000, 'sumber' => 'Operasional BPD', 'output' => 'Laporan fisik progress 75%', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-05', 'waktu' => '09:00 WIB', 'nama' => 'Rapat Dengar Pendapat Warga RW 03', 'tempat' => 'Aula Balai Desa', 'peserta' => 'Anggota BPD, Pengurus RT/RW', 'pembahasan' => 'Aspirasi perbaikan saluran drainase utama.', 'status' => 'rencana', 'tahun' => 2026],
                    ['tanggal' => '2026-03-12', 'waktu' => '13:30 WIB', 'nama' => 'Sidang Pleno LKPJ Kepala Desa Akhir Tahun', 'tempat' => 'Gedung Serbaguna', 'peserta' => 'BPD & Pemdes', 'pembahasan' => 'Pembahasan laporan keterangan pertanggungjawaban kades.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Badan Usaha Milik Desa',
                'singkatan' => 'BUMDes',
                'slug' => 'bumdes',
                'kategori' => 'ekonomi',
                'nomor_sk_pendirian' => '140/05/SK-BUMDES/2023',
                'tanggal_sk' => '2023-03-10',
                'deskripsi' => 'Badan usaha desa yang mengelola unit usaha perdagangan, penyewaan alat pertanian, dan air bersih desa.',
                'alamat_sekretariat' => 'Ruko Sentra Ekonomi Desa Unit 1-2',
                'urutan' => 2,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Direktur Utama', 'nama' => 'H. Gunawan Saputra, SE', 'periode_mulai' => 2023, 'periode_selesai' => 2028],
                    ['jabatan' => 'Sekretaris', 'nama' => 'Rina Marlina, A.Md', 'periode_mulai' => 2023, 'periode_selesai' => 2028],
                    ['jabatan' => 'Bendahara', 'nama' => 'Wahyu Hidayat', 'periode_mulai' => 2023, 'periode_selesai' => 2028],
                    ['jabatan' => 'Manajer Unit Usaha Air Bersih', 'nama' => 'Teguh Wicaksono', 'periode_mulai' => 2023, 'periode_selesai' => 2028],
                ],
                'decisions' => [
                    ['nomor' => '01/SK-DIR/BUMDES/2026', 'tanggal' => '2026-01-05', 'tentang' => 'Penetapan Tarif Layanan Air Bersih Desa', 'uraian' => 'Penyesuaian tarif air bersih per meter kubik.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Pemasangan Pipa Distribusi Air Bersih RW 04', 'tanggal' => '2026-02-10', 'lokasi' => 'RW 04 Dusun Mekar', 'penanggung_jawab' => 'Manajer Unit Air', 'anggaran' => 18500000, 'sumber' => 'Kas Usaha BUMDes', 'output' => '50 sambungan rumah baru terpasang', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-15', 'waktu' => '10:00 WIB', 'nama' => 'Musyawarah Kerja Tahunan BUMDes', 'tempat' => 'Kantor BUMDes', 'peserta' => 'Direksi & Dewan Pengawas', 'pembahasan' => 'Laporan keuangan triwulan I & rencana ekspansi minimarket desa.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Koperasi Desa',
                'singkatan' => 'Kopdes',
                'slug' => 'kopdes',
                'kategori' => 'ekonomi',
                'nomor_sk_pendirian' => '140/09/SK-KOPDES/2022',
                'tanggal_sk' => '2022-06-20',
                'deskripsi' => 'Koperasi simpan pinjam dan pengadaan pupuk/saprotan untuk kelompok tani dan pelaku UMKM desa.',
                'alamat_sekretariat' => 'Jl. Raya Desa No. 45',
                'urutan' => 3,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Ketua Koperasi', 'nama' => 'Deden Kurniawan, S.Pt', 'periode_mulai' => 2022, 'periode_selesai' => 2027],
                    ['jabatan' => 'Sekretaris', 'nama' => 'Yanti Sumarni', 'periode_mulai' => 2022, 'periode_selesai' => 2027],
                    ['jabatan' => 'Bendahara', 'nama' => 'Eka Pratama', 'periode_mulai' => 2022, 'periode_selesai' => 2027],
                ],
                'decisions' => [
                    ['nomor' => '02/KEP/KOPDES/2026', 'tanggal' => '2026-01-18', 'tentang' => 'Penyaluran Pinjaman Bergulir UMKM', 'uraian' => 'Persetujuan modal kerja untuk 25 pelaku usaha mikro.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Distribusi Pupuk Bersubsidi Musim Tanam I', 'tanggal' => '2026-02-05', 'lokasi' => 'Gudang Koperasi', 'penanggung_jawab' => 'Ketua Koperasi', 'anggaran' => 45000000, 'sumber' => 'Modal Koperasi & Gapoktan', 'output' => '15 ton pupuk tersalurkan ke 8 kelompok tani', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-20', 'waktu' => '09:00 WIB', 'nama' => 'Rapat Anggota Tahunan (RAT) 2026', 'tempat' => 'Balai Desa', 'peserta' => 'Seluruh Anggota Koperasi', 'pembahasan' => 'Pembagian SHU dan pertanggungjawaban pengurus.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Lembaga Pemberdayaan Masyarakat Desa',
                'singkatan' => 'LPMD',
                'slug' => 'lpmd',
                'kategori' => 'kemasyarakatan',
                'nomor_sk_pendirian' => '140/03/SK-LPMD/2024',
                'tanggal_sk' => '2024-02-01',
                'deskripsi' => 'Wadah yang dibentuk atas prakarsa masyarakat sebagai mitra pemerintah desa dalam menampung dan mewujudkan aspirasi serta kebutuhan masyarakat di bidang pembangunan.',
                'alamat_sekretariat' => 'Kantor Desa Ruang Sayap Barat',
                'urutan' => 4,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Ketua LPMD', 'nama' => 'H. Suryadi Ahmad', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Sekretaris', 'nama' => 'Fauzi Rahman, ST', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Bendahara', 'nama' => 'Hj. Rokayah', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Seksi Pembangunan Gotong Royong', 'nama' => 'Kusnadi', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                ],
                'decisions' => [
                    ['nomor' => '140/01/SK-LPMD/2026', 'tanggal' => '2026-01-12', 'tentang' => 'Jadwal Gerakan Gotong Royong Kebersihan Lingkungan', 'uraian' => 'Penetapan jadwal gotong royong massal tiap Minggu pagi.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Bakti Gotong Royong Normalisasi Saluran Irigasi', 'tanggal' => '2026-02-18', 'lokasi' => 'Saluran Sekunder Dusun 1', 'penanggung_jawab' => 'Seksi Pembangunan', 'anggaran' => 2000000, 'sumber' => 'Swadaya & APBDes', 'output' => 'Pembersihan endapan lumpur sepanjang 800 meter', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-08', 'waktu' => '08:00 WIB', 'nama' => 'Koordinasi Penataan Taman Desa Ramah Anak', 'tempat' => 'Lokasi Lapangan Desa', 'peserta' => 'Pengurus LPMD & Tokoh Masyarakat', 'pembahasan' => 'Rencana swadaya penanaman pohon peneduh dan bangku taman.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Pemberdayaan Kesejahteraan Keluarga',
                'singkatan' => 'PKK',
                'slug' => 'pkk',
                'kategori' => 'kemasyarakatan',
                'nomor_sk_pendirian' => '140/02/SK-PKK/2024',
                'tanggal_sk' => '2024-01-20',
                'deskripsi' => 'Gerakan pemberdayaan wanita dan keluarga untuk mewujudkan keluarga sejahtera, sehat, dan berdaya.',
                'alamat_sekretariat' => 'Gedung Sekretariat TP-PKK Desa',
                'urutan' => 5,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Ketua TP PKK Desa', 'nama' => 'Ny. Hj. Endang Sulastri', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Wakil Ketua', 'nama' => 'Ny. Ratna Dewi', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Sekretaris', 'nama' => 'Ny. Dewi Sartika, S.Pd', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Bendahara', 'nama' => 'Ny. Sri Wahyuni', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Ketua Pokja I (Penghayatan Pancasila)', 'nama' => 'Ny. Maryati', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Ketua Pokja II (Pendidikan & Keterampilan)', 'nama' => 'Ny. Fitri Handayani', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Ketua Pokja III (Pangan, Sandang, Perumahan)', 'nama' => 'Ny. Anisa Rahma', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                    ['jabatan' => 'Ketua Pokja IV (Kesehatan, Kelestarian LH)', 'nama' => 'Ny. dr. Laila Nur', 'periode_mulai' => 2024, 'periode_selesai' => 2030],
                ],
                'decisions' => [
                    ['nomor' => '01/KEP/PKK-DESA/2026', 'tanggal' => '2026-01-10', 'tentang' => 'Program Kerja 10 Pokok PKK Tahun 2026', 'uraian' => 'Rencana aksi pembinaan keluarga dan pencegahan stunting.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Pelatihan Pembuatan Olahan Pangan Lokal & MP-ASI', 'tanggal' => '2026-02-14', 'lokasi' => 'Aula Balai Desa', 'penanggung_jawab' => 'Pokja III & IV', 'anggaran' => 4200000, 'sumber' => 'APBDes Bidang Pemberdayaan', 'output' => '45 kader PKK terlatih mengolah pangan bergizi', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-10', 'waktu' => '13:00 WIB', 'nama' => 'Pertemuan Rutin Bulanan PKK & Arisan', 'tempat' => 'Sekretariat PKK', 'peserta' => 'Pengurus & Ketua Dasawisma', 'pembahasan' => 'Evaluasi lomba halaman asri teratur indah nyaman (HATINYA PKK).', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Pos Pelayanan Terpadu',
                'singkatan' => 'Posyandu',
                'slug' => 'posyandu',
                'kategori' => 'kesehatan',
                'nomor_sk_pendirian' => '140/04/SK-POSYANDU/2024',
                'tanggal_sk' => '2024-02-10',
                'deskripsi' => 'Lembaga kesehatan berbasis masyarakat yang melayani pemantauan tumbuh kembang balita, ibu hamil, lansia, dan pencegahan stunting.',
                'alamat_sekretariat' => 'Poskesdes / Posyandu Kasih Ibu RW 02',
                'urutan' => 6,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Koordinator Kader Posyandu Desa', 'nama' => 'Bdn. Nining Suhartini, S.Tr.Keb', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Sekretaris / Pengelola Data KMS', 'nama' => 'Nurul Aini', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Bendahara & Logistik PMT', 'nama' => 'Siti Khotimah', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Kader Penimbangan Balita', 'nama' => 'Wulandari', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Kader Posyandu Lansia', 'nama' => 'Rukmini', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                ],
                'decisions' => [
                    ['nomor' => '140/01/KEP-POSYANDU/2026', 'tanggal' => '2026-01-08', 'tentang' => 'Jadwal Layanan Posyandu Balita & Posyandu Lansia 5 Dusun', 'uraian' => 'Penetapan tanggal giliran penimbangan serentak tiap bulan.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Bulan Timbang & Pemberian Vitamin A Balita', 'tanggal' => '2026-02-12', 'lokasi' => 'Posyandu Mawar 1 s/d 5', 'penanggung_jawab' => 'Koordinator Kader', 'anggaran' => 6500000, 'sumber' => 'BOK Puskesmas & APBDes', 'output' => '320 balita tertimbang, 310 balita menerima vitamin A', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-18', 'waktu' => '08:30 WIB', 'nama' => 'Pemeriksaan Kesehatan Berkala & Senam Lansia', 'tempat' => 'Posyandu RW 03', 'peserta' => 'Lansia se-desa & Tim Medis Puskesmas', 'pembahasan' => 'Cek tensi, gula darah, dan edukasi pola makan lansia.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Karang Taruna',
                'singkatan' => 'Karang Taruna',
                'slug' => 'karang-taruna',
                'kategori' => 'kemasyarakatan',
                'nomor_sk_pendirian' => '140/06/SK-KT/2024',
                'tanggal_sk' => '2024-03-01',
                'deskripsi' => 'Organisasi kepemudaan desa yang bergerak di bidang olahraga, kesenian, penanggulangan masalah sosial, dan kewirausahaan pemuda.',
                'alamat_sekretariat' => 'Gedung Pemuda / Lapangan Desa',
                'urutan' => 7,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Ketua Karang Taruna', 'nama' => 'Rizky Firmansyah, S.Kom', 'periode_mulai' => 2024, 'periode_selesai' => 2027],
                    ['jabatan' => 'Wakil Ketua', 'nama' => 'Dimas Aditya', 'periode_mulai' => 2024, 'periode_selesai' => 2027],
                    ['jabatan' => 'Sekretaris', 'nama' => 'Bayu Nugroho', 'periode_mulai' => 2024, 'periode_selesai' => 2027],
                    ['jabatan' => 'Bendahara', 'nama' => 'Sinta Maharani', 'periode_mulai' => 2024, 'periode_selesai' => 2027],
                    ['jabatan' => 'Koordinator Olahraga & Seni', 'nama' => 'Aldi Pratama', 'periode_mulai' => 2024, 'periode_selesai' => 2027],
                ],
                'decisions' => [
                    ['nomor' => '01/KT-DESA/KEP/2026', 'tanggal' => '2026-01-25', 'tentang' => 'Pembentukan Panitia Turnamen Bola Voli Antar Dusun', 'uraian' => 'Penetapan panitia pelaksana turnamen tahunan.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Pelatihan Digital Marketing bagi Pemuda Pelaku Usaha', 'tanggal' => '2026-02-20', 'lokasi' => 'Lab Komputer Kantor Desa', 'penanggung_jawab' => 'Ketua Karang Taruna', 'anggaran' => 3000000, 'sumber' => 'APBDes Pembinaan Kepemudaan', 'output' => '30 pemuda desa mampu membuat konten medsos promosi produk', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-22', 'waktu' => '15:30 WIB', 'nama' => 'Technical Meeting Turnamen Voli Cup 2026', 'tempat' => 'Tribun Lapangan Desa', 'peserta' => 'Official 8 Tim Dusun', 'pembahasan' => 'Drawing grup dan penetapan regulasi pertandingan.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
            [
                'nama_lembaga' => 'Lembaga Musyawarah Perwakilan (LMP)',
                'singkatan' => 'LMP',
                'slug' => 'lmp',
                'kategori' => 'kemasyarakatan',
                'nomor_sk_pendirian' => '140/07/SK-LMP/2024',
                'tanggal_sk' => '2024-03-15',
                'deskripsi' => 'Lembaga perwakilan pemangku adat, tokoh agama, dan tokoh masyarakat untuk musyawarah mufakat penyelesaian perselisihan dan pelestarian adat istiadat desa.',
                'alamat_sekretariat' => 'Balai Musyawarah Adat Desa',
                'urutan' => 8,
                'is_active' => true,
                'members' => [
                    ['jabatan' => 'Ketua LMP / Pemangku Adat', 'nama' => 'K.H. Ahmad Dahlan', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Wakil Ketua', 'nama' => 'Drs. H. Syarifudin', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Sekretaris', 'nama' => 'Ust. Marzuki', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Anggota Keterwakilan Dusun 1', 'nama' => 'H. Abdul Somad', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                    ['jabatan' => 'Anggota Keterwakilan Dusun 2', 'nama' => 'H. Maimun', 'periode_mulai' => 2024, 'periode_selesai' => 2029],
                ],
                'decisions' => [
                    ['nomor' => '01/LMP-DESA/2026', 'tanggal' => '2026-01-30', 'tentang' => 'Panduan Pelestarian Tradisi Sedekah Bumi & Bersih Desa', 'uraian' => 'Penetapan tata cara ritual adat dan kesenian tradisional.', 'tahun' => 2026],
                ],
                'activities' => [
                    ['nama' => 'Musyawarah Mediasi Batas Tanah Adat & Fasum Warga', 'tanggal' => '2026-02-16', 'lokasi' => 'Balai Adat Desa', 'penanggung_jawab' => 'Ketua LMP', 'anggaran' => 800000, 'sumber' => 'Kas Operasional LMP', 'output' => 'Surat kesepakatan damai bermaterai antar pihak', 'tahun' => 2026],
                ],
                'agendas' => [
                    ['tanggal' => '2026-03-25', 'waktu' => '19:30 WIB', 'nama' => 'Musyawarah Persiapan Acara Adat Ruwatan Desa', 'tempat' => 'Serambi Masjid Jami Desa', 'peserta' => 'Pengurus LMP, Tokoh Agama, RT/RW', 'pembahasan' => 'Penjadwalan doa bersama dan santunan anak yatim se-desa.', 'status' => 'rencana', 'tahun' => 2026],
                ]
            ],
        ];

        foreach ($institutionsData as $item) {
            $institution = Institution::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'nama_lembaga' => $item['nama_lembaga'],
                    'singkatan' => $item['singkatan'],
                    'kategori' => $item['kategori'],
                    'nomor_sk_pendirian' => $item['nomor_sk_pendirian'],
                    'tanggal_sk' => $item['tanggal_sk'],
                    'deskripsi' => $item['deskripsi'],
                    'alamat_sekretariat' => $item['alamat_sekretariat'],
                    'urutan' => $item['urutan'],
                    'is_active' => $item['is_active'],
                ]
            );

            // Seed Members
            foreach ($item['members'] as $idx => $m) {
                $penduduk = $penduduks[$idx % max(1, $penduduks->count())] ?? null;
                InstitutionMember::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'nama_lengkap' => $m['nama'],
                    ],
                    [
                        'penduduk_id' => $penduduk?->id,
                        'nik' => $penduduk?->nik ?? ('3202' . rand(100000000000, 999999999999)),
                        'jabatan' => $m['jabatan'],
                        'nomor_sk_pengangkatan' => $item['nomor_sk_pendirian'],
                        'tanggal_sk' => $item['tanggal_sk'],
                        'periode_mulai' => $m['periode_mulai'],
                        'periode_selesai' => $m['periode_selesai'],
                        'kontak' => '0812' . rand(10000000, 99999999),
                        'status_aktif' => true,
                    ]
                );
            }

            // Seed Decisions
            foreach ($item['decisions'] as $d) {
                InstitutionDecision::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'nomor_keputusan' => $d['nomor'],
                    ],
                    [
                        'tanggal_keputusan' => $d['tanggal'],
                        'tentang' => $d['tentang'],
                        'uraian_singkat' => $d['uraian'],
                        'tahun' => $d['tahun'],
                    ]
                );
            }

            // Seed Activities
            foreach ($item['activities'] as $a) {
                InstitutionActivity::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'nama_kegiatan' => $a['nama'],
                    ],
                    [
                        'tanggal_kegiatan' => $a['tanggal'],
                        'lokasi' => $a['lokasi'],
                        'penanggung_jawab' => $a['penanggung_jawab'],
                        'anggaran' => $a['anggaran'],
                        'sumber_dana' => $a['sumber'],
                        'output_hasil' => $a['output'],
                        'tahun' => $a['tahun'],
                    ]
                );
            }

            // Seed Agendas
            foreach ($item['agendas'] as $ag) {
                InstitutionAgenda::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'nama_agenda' => $ag['nama'],
                    ],
                    [
                        'tanggal_agenda' => $ag['tanggal'],
                        'waktu' => $ag['waktu'],
                        'tempat' => $ag['tempat'],
                        'peserta' => $ag['peserta'],
                        'pembahasan' => $ag['pembahasan'],
                        'status' => $ag['status'],
                        'tahun' => $ag['tahun'],
                    ]
                );
            }
        }
    }
}
