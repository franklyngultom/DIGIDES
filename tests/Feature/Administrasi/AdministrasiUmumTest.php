<?php

namespace Tests\Feature\Administrasi;

use App\Models\BukuAgenda;
use App\Models\BukuAnggaranDesa;
use App\Models\BukuEkspedisi;
use App\Models\BukuInventarisAset;
use App\Models\BukuKeputusanKades;
use App\Models\BukuLembaranDesa;
use App\Models\BukuPeraturanDesa;
use App\Models\BukuTanahDesa;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdministrasiUmumTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->staff = User::where('email', 'staff@desa.id')->first();
    }

    public function test_admin_can_access_administrasi_hub_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('administrasi.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Buku Register Administrasi Umum');
        $response->assertSeeText('Peraturan di Desa');
        $response->assertSeeText('Keputusan Kades');
        $response->assertSeeText('Inventaris & Aset');
        $response->assertSeeText('Tanah di Desa');
        $response->assertSeeText('Anggaran Desa');
        $response->assertSeeText('Lembaran Desa');
        $response->assertSeeText('Agenda Surat');
        $response->assertSeeText('Buku Ekspedisi');
    }

    public function test_admin_can_crud_buku_peraturan_desa_and_export_pdf(): void
    {
        Storage::fake('public');

        // 1. Create
        $pdfFile = UploadedFile::fake()->create('perdes.pdf', 500, 'application/pdf');
        $response = $this->actingAs($this->admin)->post(route('administrasi.peraturan-desa.store'), [
            'tahun'              => 2026,
            'jenis_peraturan'    => 'perdes',
            'nomor_ditetapkan'   => '01/PERDES/2026',
            'tanggal_ditetapkan' => '2026-01-15',
            'tentang'            => 'Perdes APBDes Tahun Anggaran 2026',
            'uraian_singkat'     => 'Menetapkan APBDes 2026',
            'file_pdf'           => $pdfFile,
        ]);

        $response->assertRedirect(route('administrasi.peraturan-desa.index'));
        $this->assertDatabaseHas('buku_peraturan_desa', [
            'nomor_ditetapkan' => '01/PERDES/2026',
            'tentang'          => 'Perdes APBDes Tahun Anggaran 2026',
        ]);

        $peraturan = BukuPeraturanDesa::first();

        // 2. Edit / Update
        $response = $this->actingAs($this->admin)->put(route('administrasi.peraturan-desa.update', $peraturan), [
            'tahun'              => 2026,
            'jenis_peraturan'    => 'perdes',
            'nomor_ditetapkan'   => '01/PERDES/2026-REV',
            'tanggal_ditetapkan' => '2026-01-20',
            'tentang'            => 'Perdes APBDes Tahun Anggaran 2026 Revisi',
        ]);

        $response->assertRedirect(route('administrasi.peraturan-desa.index'));
        $this->assertDatabaseHas('buku_peraturan_desa', [
            'nomor_ditetapkan' => '01/PERDES/2026-REV',
        ]);

        // 3. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.peraturan-desa.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 4. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.peraturan-desa.destroy', $peraturan));
        $response->assertRedirect(route('administrasi.peraturan-desa.index'));
        $this->assertDatabaseMissing('buku_peraturan_desa', ['id' => $peraturan->id]);
    }

    public function test_admin_can_crud_buku_keputusan_kades_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.keputusan-kades.store'), [
            'tahun'             => 2026,
            'nomor_keputusan'   => '141.1/SK-01/2026',
            'tanggal_keputusan' => '2026-02-01',
            'tentang'           => 'Pembentukan Panitia Pengadaan Barang Desa',
            'uraian_singkat'    => 'Menunjuk 5 staf panitia',
        ]);

        $response->assertRedirect(route('administrasi.keputusan-kades.index'));
        $this->assertDatabaseHas('buku_keputusan_kades', [
            'nomor_keputusan' => '141.1/SK-01/2026',
        ]);

        $sk = BukuKeputusanKades::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.keputusan-kades.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.keputusan-kades.destroy', $sk));
        $response->assertRedirect(route('administrasi.keputusan-kades.index'));
        $this->assertDatabaseMissing('buku_keputusan_kades', ['id' => $sk->id]);
    }

    public function test_admin_can_crud_buku_inventaris_aset_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.inventaris-aset.store'), [
            'tahun_pengadaan'   => 2026,
            'jenis_barang'      => 'Laptop Asus Core i7',
            'kode_barang'       => 'AST-2026-001',
            'identitas_barang'  => 'RAM 16GB SSD 512GB Warna Hitam',
            'asal_usul'         => 'apbdes',
            'harga_perolehan'   => 15000000,
            'kondisi'           => 'baik',
            'lokasi_penempatan' => 'Ruang Pelayanan Kantor Desa',
        ]);

        $response->assertRedirect(route('administrasi.inventaris-aset.index'));
        $this->assertDatabaseHas('buku_inventaris_aset', [
            'jenis_barang' => 'Laptop Asus Core i7',
            'kode_barang'  => 'AST-2026-001',
        ]);

        $aset = BukuInventarisAset::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.inventaris-aset.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.inventaris-aset.destroy', $aset));
        $response->assertRedirect(route('administrasi.inventaris-aset.index'));
        $this->assertDatabaseMissing('buku_inventaris_aset', ['id' => $aset->id]);
    }

    public function test_admin_can_crud_buku_tanah_desa_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.tanah-desa.store'), [
            'jenis_tanah'               => 'tanah_kas_desa',
            'nomor_sertifikat_letter_c' => 'SHM-DS-01',
            'nama_pemilik_asal'         => 'Pemerintah Desa Sukamaju',
            'luas_m2'                   => 5000,
            'kelas_tanah'               => 'D.I',
            'lokasi_blok'               => 'Blok Babakan Persil 12',
            'peruntukan_saat_ini'       => 'Kantor Desa dan Balai Warga',
            'patok_tanda_batas'         => 'Batas Utara Jalan, Selatan Saluran',
        ]);

        $response->assertRedirect(route('administrasi.tanah-desa.index'));
        $this->assertDatabaseHas('buku_tanah_desa', [
            'nomor_sertifikat_letter_c' => 'SHM-DS-01',
        ]);

        $tanah = BukuTanahDesa::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.tanah-desa.export-pdf'));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.tanah-desa.destroy', $tanah));
        $response->assertRedirect(route('administrasi.tanah-desa.index'));
        $this->assertDatabaseMissing('buku_tanah_desa', ['id' => $tanah->id]);
    }

    public function test_admin_can_crud_buku_anggaran_desa_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.anggaran-desa.store'), [
            'tahun'              => 2026,
            'jenis_dokumen'      => 'apbdes',
            'nomor_perdes'       => '03/2026',
            'tanggal_penetapan'  => '2025-12-31',
            'total_pendapatan'   => 1200000000,
            'total_belanja'      => 1150000000,
            'total_pembiayaan'   => 50000000,
            'keterangan'         => 'APBDes Murni 2026',
        ]);

        $response->assertRedirect(route('administrasi.anggaran-desa.index'));
        $this->assertDatabaseHas('buku_anggaran_desa', [
            'nomor_perdes' => '03/2026',
        ]);

        $anggaran = BukuAnggaranDesa::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.anggaran-desa.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.anggaran-desa.destroy', $anggaran));
        $response->assertRedirect(route('administrasi.anggaran-desa.index'));
        $this->assertDatabaseMissing('buku_anggaran_desa', ['id' => $anggaran->id]);
    }

    public function test_admin_can_crud_buku_lembaran_desa_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.lembaran-desa.store'), [
            'tahun'                => 2026,
            'jenis'                => 'lembaran_desa',
            'nomor_seri'           => 'Seri A No. 01/2026',
            'tanggal_diundangkan'  => '2026-01-16',
            'judul'                => 'Lembaran Desa Tentang RKPDes 2026',
            'isi_singkat'          => 'Pengundangan Perdes RKPDes',
        ]);

        $response->assertRedirect(route('administrasi.lembaran-desa.index'));
        $this->assertDatabaseHas('buku_lembaran_desa', [
            'nomor_seri' => 'Seri A No. 01/2026',
        ]);

        $lembaran = BukuLembaranDesa::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.lembaran-desa.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.lembaran-desa.destroy', $lembaran));
        $response->assertRedirect(route('administrasi.lembaran-desa.index'));
        $this->assertDatabaseMissing('buku_lembaran_desa', ['id' => $lembaran->id]);
    }

    public function test_admin_can_crud_buku_agenda_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.buku-agenda.store'), [
            'jenis'                    => 'masuk',
            'nomor_urut'               => 1,
            'tahun'                    => 2026,
            'nomor_surat'              => '005/120/Kec/2026',
            'tanggal_surat'            => '2026-02-10',
            'tanggal_diterima_dikirim' => '2026-02-11',
            'asal_tujuan'              => 'Kecamatan Cikole',
            'perihal'                  => 'Undangan Rakor Penataan Batas Wilayah',
            'keterangan'               => 'Diteruskan ke Sekdes',
        ]);

        $response->assertRedirect(route('administrasi.buku-agenda.index'));
        $this->assertDatabaseHas('buku_agendas', [
            'nomor_surat' => '005/120/Kec/2026',
        ]);

        $agenda = BukuAgenda::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.buku-agenda.export-pdf', ['tahun' => 2026, 'jenis' => 'masuk']));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.buku-agenda.destroy', $agenda));
        $response->assertRedirect(route('administrasi.buku-agenda.index'));
        $this->assertDatabaseMissing('buku_agendas', ['id' => $agenda->id]);
    }

    public function test_admin_can_crud_buku_ekspedisi_and_export_pdf(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('administrasi.buku-ekspedisi.store'), [
            'nomor_urut'         => 1,
            'tahun'              => 2026,
            'tanggal_pengiriman' => '2026-02-12',
            'nomor_surat'        => '140/05/DS/2026',
            'tanggal_surat'      => '2026-02-12',
            'perihal'            => 'Laporan LPJ Dana Desa',
            'tujuan_penerima'    => 'Kantor Kecamatan Cikole',
            'petugas_pengirim'   => 'Ahmad Kurir',
            'catatan'            => 'Diterima oleh Ibu Rina Staf Umum',
        ]);

        $response->assertRedirect(route('administrasi.buku-ekspedisi.index'));
        $this->assertDatabaseHas('buku_ekspedisis', [
            'nomor_surat' => '140/05/DS/2026',
        ]);

        $ekspedisi = BukuEkspedisi::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('administrasi.buku-ekspedisi.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('administrasi.buku-ekspedisi.destroy', $ekspedisi));
        $response->assertRedirect(route('administrasi.buku-ekspedisi.index'));
        $this->assertDatabaseMissing('buku_ekspedisis', ['id' => $ekspedisi->id]);
    }

    public function test_staff_with_view_only_cannot_create_or_delete_registers(): void
    {
        // Staff has administrasi.view only, not administrasi.manage
        $response = $this->actingAs($this->staff)->get(route('administrasi.peraturan-desa.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->staff)->post(route('administrasi.peraturan-desa.store'), [
            'tahun'              => 2026,
            'jenis_peraturan'    => 'perdes',
            'nomor_ditetapkan'   => '99/TEST',
            'tanggal_ditetapkan' => '2026-01-01',
            'tentang'            => 'Test Unauthorized',
        ]);
        $response->assertStatus(403);
    }
}
