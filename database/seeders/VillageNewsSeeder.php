<?php

namespace Database\Seeders;

use App\Models\VillageNews;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VillageNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'judul' => 'Pemerintah Desa Luncurkan Portal Pelayanan Mandiri Berbasis Digital',
                'slug' => 'pemerintah-desa-luncurkan-portal-pelayanan-mandiri',
                'kategori' => 'Berita',
                'ringkasan' => 'Warga kini dapat mengajukan permohonan surat keterangan dan administrasi secara online dari mana saja dan kapan saja.',
                'konten' => '<p>Dalam rangka meningkatkan efisiensi dan transparansi birokrasi pemerintahan desa, Pemerintah Desa secara resmi meluncurkan Portal Pelayanan Publik Digital DIGIDES v3.</p><p>Melalui inovasi ini, seluruh warga dapat membuat akun, melengkapi berkas persyaratan, dan mengajukan berbagai jenis surat keterangan seperti Surat Pengantar SKCK, Keterangan Usaha, Keterangan Domisili, hingga Surat Keterangan Tidak Mampu tanpa perlu mengantre lama di kantor desa.</p><p>Kepala Desa menyampaikan bahwa digitalisasi ini bertujuan untuk memangkas waktu tunggu pelayanan dari berhari-hari menjadi hitungan menit.</p>',
                'gambar_path' => 'images/public/layanan-kiosk.jpg',
                'penulis' => 'Humas Kantor Desa',
                'views_count' => 142,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Jadwal Musrenbangdes Tahun Anggaran Mendatang & Penjaringan Aspirasi Warga',
                'slug' => 'jadwal-musrenbangdes-tahun-anggaran-mendatang',
                'kategori' => 'Pengumuman',
                'ringkasan' => 'Undangan terbuka bagi seluruh perwakilan RW, tokoh masyarakat, dan kelompok pemuda untuk menghadiri Musyawarah Perencanaan Pembangunan Desa.',
                'konten' => '<p>Diberitahukan kepada seluruh warga masyarakat bahwa Musyawarah Perencanaan Pembangunan Desa (Musrenbangdes) akan dilaksanakan pada hari Senin depan bertempat di Balai Pertemuan Warga.</p><p>Agenda utama meliputi evaluasi realisasi APBDes tahun berjalan dan penetapan prioritas pembangunan infrastruktur jalan tani serta program pemberdayaan ekonomi UMKM desa.</p>',
                'gambar_path' => 'images/public/kantor-desa.jpg',
                'penulis' => 'Sekretariat Desa',
                'views_count' => 98,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Program Ketahanan Pangan: Panen Raya Padi Organik & Penguatan BUMDes',
                'slug' => 'program-ketahanan-pangan-panen-raya-padi-organik',
                'kategori' => 'Berita',
                'ringkasan' => 'Kolaborasi kelompok tani dan BUMDes sukses meningkatkan hasil panen gabah kering hingga 25% dengan sistem irigasi terpadu.',
                'konten' => '<p>Sektor pertanian desa mencatatkan pencapaian membanggakan melalui panen raya komoditas padi organik varietas unggul. Keberhasilan ini didukung oleh revitalisasi saluran irigasi tersier yang didanai melalui pos pembangunan desa.</p><p>Hasil panen akan diserap langsung oleh unit usaha BUMDes untuk dipasarkan dengan kemasan premium guna meningkatkan nilai tambah bagi para petani lokal.</p>',
                'gambar_path' => 'images/public/desa-alam.jpg',
                'penulis' => 'Tim Pengelola BUMDes',
                'views_count' => 210,
                'is_published' => true,
                'published_at' => now()->subDays(8),
            ],
        ];

        foreach ($articles as $article) {
            VillageNews::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
