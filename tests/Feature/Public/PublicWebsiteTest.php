<?php

namespace Tests\Feature\Public;

use App\Models\DesaProfile;
use App\Models\VillageNews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DesaProfile::current();
    }

    public function test_public_home_page_can_be_rendered_without_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('Mitra Terpercaya');
        $response->assertSeeText('Layanan Persuratan Instan');
    }

    public function test_public_profil_page_can_be_rendered(): void
    {
        $response = $this->get('/profil-desa');

        $response->assertStatus(200);
        $response->assertSeeText('Profil & Struktur');
        $response->assertSeeText('Visi Pembangunan Desa');
    }

    public function test_public_layanan_page_can_be_rendered(): void
    {
        $response = $this->get('/layanan');

        $response->assertStatus(200);
        $response->assertSeeText('Katalog Layanan Surat Desa');
    }

    public function test_public_berita_list_and_detail_can_be_rendered(): void
    {
        $news = VillageNews::firstOrCreate(
            ['slug' => 'test-berita-desa'],
            [
                'judul' => 'Uji Coba Berita Publik Desa Sukamaju',
                'kategori' => 'Berita',
                'ringkasan' => 'Ringkasan berita uji coba.',
                'konten' => '<p>Konten berita uji coba.</p>',
                'penulis' => 'Admin Desa',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        $responseList = $this->get('/berita');
        $responseList->assertStatus(200);
        $responseList->assertSeeText('Berita & Pengumuman Desa');

        $responseDetail = $this->get('/berita/' . $news->slug);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSeeText($news->judul);
    }

    public function test_public_kontak_page_can_be_rendered(): void
    {
        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertSeeText('Kontak & Lokasi Kantor Desa');
    }

    public function test_public_lacak_surat_page_can_be_rendered(): void
    {
        $response = $this->get('/lacak-surat');

        $response->assertStatus(200);
        $response->assertSeeText('Lacak Status Permohonan Surat');
    }

    public function test_public_infografis_page_can_be_rendered(): void
    {
        $response = $this->get('/infografis');

        $response->assertStatus(200);
        $response->assertSeeText('Infografis Kependudukan & APBDes');
        $response->assertSeeText('Statistik Kependudukan');
    }

    public function test_public_listing_page_can_be_rendered(): void
    {
        $response = $this->get('/listing');

        $response->assertStatus(200);
        $response->assertSeeText('Listing Potensi & Fasilitas Desa');
    }

    public function test_public_idm_page_can_be_rendered(): void
    {
        $response = $this->get('/idm');

        $response->assertStatus(200);
        $response->assertSeeText('Indeks Desa Membangun (IDM)');
        $response->assertSeeText('DESA MANDIRI');
    }

    public function test_public_belanja_page_can_be_rendered(): void
    {
        $response = $this->get('/belanja');

        $response->assertStatus(200);
        $response->assertSeeText('Lapak Belanja UMKM & BUMDes');
    }

    public function test_public_ppid_page_can_be_rendered(): void
    {
        $response = $this->get('/ppid');

        $response->assertStatus(200);
        $response->assertSeeText('Pejabat Pengelola Informasi & Dokumentasi (PPID)');
    }

    public function test_public_pengaduan_can_be_submitted(): void
    {
        $response = $this->post('/pengaduan', [
            'nama_pelapor' => 'Warga Peduli',
            'kontak' => '081234567890',
            'kategori' => 'Pelayanan Publik',
            'isi_laporan' => 'Pengaduan uji coba terkait perbaikan lampu jalan dusun.',
        ]);

        $response->assertSessionHas('success');
    }
}
