<?php

namespace Tests\Feature\Keuangan;

use App\Models\DesaProfile;
use App\Models\KeuanganApbdes;
use App\Models\KeuanganKasTransaksi;
use App\Models\PembangunanKader;
use App\Models\PembangunanProyek;
use App\Models\Penduduk;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KeuanganPembangunanTest extends TestCase
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

    public function test_admin_can_access_keuangan_hub_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('keuangan.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Pengelolaan & Transparansi Keuangan Desa');
        $response->assertSeeText('Master & Realisasi APBDes');
        $response->assertSeeText('Buku Kas Umum (BKU)');
        $response->assertSeeText('Buku Kas Bank Desa');
    }

    public function test_admin_can_crud_apbdes_and_export_pdf(): void
    {
        // 1. Create APBDes item
        $response = $this->actingAs($this->admin)->post(route('keuangan.apbdes.store'), [
            'tahun_anggaran' => 2026,
            'kode_rekening'  => '5.2.1.01',
            'jenis'          => 'belanja',
            'bidang'         => 'Bidang 2: Pembangunan Desa',
            'uraian'         => 'Belanja Aspal Hotmix Jalan Usaha Tani',
            'anggaran'       => 150000000,
            'realisasi'      => 75000000,
            'sumber_dana'    => 'DDS',
        ]);

        $response->assertRedirect(route('keuangan.apbdes.index', ['tahun' => 2026]));
        $this->assertDatabaseHas('keuangan_apbdes', [
            'kode_rekening' => '5.2.1.01',
            'anggaran'      => 150000000,
        ]);

        $apbdes = KeuanganApbdes::first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('keuangan.apbdes.update', $apbdes), [
            'tahun_anggaran' => 2026,
            'kode_rekening'  => '5.2.1.01',
            'jenis'          => 'belanja',
            'bidang'         => 'Bidang 2: Pembangunan Desa',
            'uraian'         => 'Belanja Aspal Hotmix Jalan Usaha Tani (Revisi)',
            'anggaran'       => 160000000,
            'realisasi'      => 80000000,
            'sumber_dana'    => 'DDS',
        ]);

        $response->assertRedirect(route('keuangan.apbdes.index', ['tahun' => 2026]));
        $this->assertDatabaseHas('keuangan_apbdes', [
            'anggaran' => 160000000,
        ]);

        // 3. Export PDF
        $response = $this->actingAs($this->admin)->get(route('keuangan.apbdes.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 4. Delete
        $response = $this->actingAs($this->admin)->delete(route('keuangan.apbdes.destroy', $apbdes));
        $response->assertRedirect(route('keuangan.apbdes.index', ['tahun' => 2026]));
        $this->assertDatabaseMissing('keuangan_apbdes', ['id' => $apbdes->id]);
    }

    public function test_admin_can_crud_kas_umum_with_running_balance_and_export_pdf(): void
    {
        Storage::fake('public');

        // 1. Create Transaction 1 (Penerimaan Kas Tunai)
        $this->actingAs($this->admin)->post(route('keuangan.kas.store'), [
            'kategori_kas'   => 'tunai',
            'jenis_pembantu' => 'umum',
            'buku_kas_type'  => 'umum',
            'tahun_anggaran' => 2026,
            'tanggal'        => '2026-01-10',
            'nomor_bukti'    => 'BKM-01/DDS/2026',
            'uraian'         => 'Pencairan Dana Desa Tahap I',
            'penerimaan'     => 100000000,
            'pengeluaran'    => 0,
            'sumber_dana'    => 'DDS',
        ]);

        $tx1 = KeuanganKasTransaksi::where('nomor_bukti', 'BKM-01/DDS/2026')->first();
        $this->assertEquals(100000000, $tx1->saldo);
        $this->assertEquals('tunai', $tx1->kategori_kas);
        $this->assertEquals('umum', $tx1->jenis_pembantu);

        // 2. Create Transaction 2 (Pengeluaran Kas Tunai dengan jenis pembantu panjar)
        $fileBukti = UploadedFile::fake()->create('kwitansi.pdf', 300, 'application/pdf');
        $this->actingAs($this->admin)->post(route('keuangan.kas.store'), [
            'kategori_kas'   => 'tunai',
            'jenis_pembantu' => 'panjar',
            'buku_kas_type'  => 'umum',
            'tahun_anggaran' => 2026,
            'tanggal'        => '2026-01-15',
            'nomor_bukti'    => 'BKK-01/DDS/2026',
            'uraian'         => 'Pembelian Semen & Batu Proyek',
            'penerimaan'     => 0,
            'pengeluaran'    => 35000000,
            'sumber_dana'    => 'DDS',
            'file_bukti'     => $fileBukti,
        ]);

        $tx2 = KeuanganKasTransaksi::where('nomor_bukti', 'BKK-01/DDS/2026')->first();
        $this->assertEquals(65000000, $tx2->saldo);
        $this->assertEquals('panjar', $tx2->jenis_pembantu);

        // 3. Create Transaction 3 (Mutasi Kas Bank / Saldo - terpisah dari tunai)
        $this->actingAs($this->admin)->post(route('keuangan.kas.store'), [
            'kategori_kas'   => 'bank',
            'jenis_pembantu' => null,
            'buku_kas_type'  => 'bank',
            'tahun_anggaran' => 2026,
            'tanggal'        => '2026-01-20',
            'nomor_bukti'    => 'TRF-01/2026',
            'uraian'         => 'Transfer Bank BJB Kasda',
            'penerimaan'     => 200000000,
            'pengeluaran'    => 0,
            'sumber_dana'    => 'DDS',
        ]);

        $txBank = KeuanganKasTransaksi::where('nomor_bukti', 'TRF-01/2026')->first();
        $this->assertEquals(200000000, $txBank->saldo);
        $this->assertEquals('bank', $txBank->kategori_kas);

        // 4. Export PDF
        $response = $this->actingAs($this->admin)->get(route('keuangan.kas.export-pdf', ['type' => 'umum', 'tahun' => 2026]));
        $response->assertStatus(200);

        // 5. Delete Transaction 1 and verify recalculated balance on Transaction 2 (Tunai only, Bank unchanged)
        $this->actingAs($this->admin)->delete(route('keuangan.kas.destroy', $tx1));
        $tx2->refresh();
        $this->assertEquals(-35000000, $tx2->saldo);

        $txBank->refresh();
        $this->assertEquals(200000000, $txBank->saldo);
    }

    public function test_admin_can_access_pembangunan_hub_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('pembangunan.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Pembangunan Infrastruktur & Pemberdayaan Masyarakat');
        $response->assertSeeText('Kegiatan Pembangunan Fisik');
        $response->assertSeeText('Inventaris Hasil Pembangunan');
        $response->assertSeeText('Kader Pemberdayaan (KPM)');
    }

    public function test_admin_can_crud_proyek_pembangunan_and_export_pdf(): void
    {
        Storage::fake('public');

        $fotoNol = UploadedFile::fake()->image('titik_nol.jpg');
        $fileRab = UploadedFile::fake()->create('rab_proyek.pdf', 500, 'application/pdf');

        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('pembangunan.proyek.store'), [
            'tahun_anggaran'     => 2026,
            'nama_kegiatan'      => 'Pembangunan Posyandu Dusun 2',
            'lokasi'             => 'Dusun Babakan RT 02',
            'volume'             => 'Gedung 6m x 8m',
            'anggaran_biaya'     => 85000000,
            'realisasi_biaya'    => 40000000,
            'sumber_dana'        => 'Dana Desa (DDS)',
            'pelaksana_tpk'      => 'TPK Dusun 2',
            'status_progres'     => 'proses',
            'persentase_selesai' => 50,
            'manfaat_warga'      => 'Warga ibu dan balita RW 02',
            'foto_titik_nol'     => $fotoNol,
            'file_rab'           => $fileRab,
        ]);

        $proyek = PembangunanProyek::first();
        $response->assertRedirect(route('pembangunan.proyek.show', $proyek));
        $this->assertDatabaseHas('pembangunan_proyek', [
            'nama_kegiatan'      => 'Pembangunan Posyandu Dusun 2',
            'persentase_selesai' => 50,
        ]);

        // 2. View Show
        $response = $this->actingAs($this->admin)->get(route('pembangunan.proyek.show', $proyek));
        $response->assertStatus(200);
        $response->assertSeeText('Pembangunan Posyandu Dusun 2');

        // 3. Export PDF
        $response = $this->actingAs($this->admin)->get(route('pembangunan.proyek.export-pdf', ['tahun' => 2026]));
        $response->assertStatus(200);

        // 4. Delete
        $response = $this->actingAs($this->admin)->delete(route('pembangunan.proyek.destroy', $proyek));
        $response->assertRedirect(route('pembangunan.proyek.index', ['tahun' => 2026]));
        $this->assertDatabaseMissing('pembangunan_proyek', ['id' => $proyek->id]);
    }

    public function test_admin_can_crud_kader_desa_and_export_pdf(): void
    {
        // Seed a resident
        $penduduk = Penduduk::create([
            'nik'                    => '3202111234560001',
            'no_kk'                  => '3202111234560000',
            'nama_lengkap'           => 'Siti Aminah',
            'tempat_lahir'           => 'Sukabumi',
            'tanggal_lahir'          => '1992-05-14',
            'jenis_kelamin'          => 'P',
            'agama'                  => 'Islam',
            'status_perkawinan'      => 'kawin',
            'status_dalam_keluarga'  => 'istri',
            'pekerjaan'              => 'Ibu Rumah Tangga',
            'pendidikan_terakhir'    => 'SMA / Sederajat',
            'alamat_lengkap'         => 'Kp. Sukamaju RW 02',
            'dusun'                  => 'Dusun 1',
            'rt'                     => '001',
            'rw'                     => '002',
            'status_penduduk'        => 'tetap',
        ]);

        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('pembangunan.kader.store'), [
            'penduduk_id'   => $penduduk->id,
            'jenis_kader'   => 'kpm_stunting',
            'jabatan'       => 'Kader Penanganan Stunting Desa',
            'nomor_sk'      => '141.1/SK-08/2026',
            'tanggal_sk'    => '2026-01-05',
            'honor_bulanan' => 350000,
            'keterangan'    => 'Bertugas di wilayah RW 01 - RW 04',
            'status_aktif'  => 1,
        ]);

        $response->assertRedirect(route('pembangunan.kader.index'));
        $this->assertDatabaseHas('pembangunan_kader', [
            'penduduk_id' => $penduduk->id,
            'jenis_kader' => 'kpm_stunting',
        ]);

        $kader = PembangunanKader::first();

        // 2. Export PDF
        $response = $this->actingAs($this->admin)->get(route('pembangunan.kader.export-pdf'));
        $response->assertStatus(200);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('pembangunan.kader.destroy', $kader));
        $response->assertRedirect(route('pembangunan.kader.index'));
        $this->assertDatabaseMissing('pembangunan_kader', ['id' => $kader->id]);
    }

    public function test_unauthorized_user_cannot_manage_keuangan_or_pembangunan(): void
    {
        // Staff has view-only permissions, not manage permissions
        $response = $this->actingAs($this->staff)->get(route('keuangan.apbdes.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->staff)->get(route('pembangunan.proyek.create'));
        $response->assertStatus(403);
    }
}
