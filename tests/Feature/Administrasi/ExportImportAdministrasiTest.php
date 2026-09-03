<?php

namespace Tests\Feature\Administrasi;

use App\Models\Aparatur;
use App\Models\BukuAgenda;
use App\Models\BukuAnggaranDesa;
use App\Models\BukuEkspedisi;
use App\Models\BukuInventarisAset;
use App\Models\BukuKeputusanKades;
use App\Models\BukuLembaranDesa;
use App\Models\BukuPeraturanDesa;
use App\Models\BukuTanahDesa;
use App\Models\Institution;
use App\Models\Penduduk;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\InstitutionSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ExportImportAdministrasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Institution $institution;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(InstitutionSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->institution = Institution::first();
    }

    public function test_export_pdf_and_excel_peraturan_desa(): void
    {
        BukuPeraturanDesa::create([
            'tahun' => 2026,
            'jenis_peraturan' => 'perdes',
            'nomor_ditetapkan' => '01/2026',
            'tanggal_ditetapkan' => '2026-01-10',
            'tentang' => 'APBDes 2026',
            'nomor_diundangkan' => '01/2026',
            'tanggal_diundangkan' => '2026-01-12',
            'created_by' => $this->admin->id,
        ]);

        $pdfResp = $this->actingAs($this->admin)->get(route('administrasi.peraturan-desa.export-pdf', ['tahun' => 2026]));
        $pdfResp->assertStatus(200);

        $excelResp = $this->actingAs($this->admin)->get(route('administrasi.peraturan-desa.export-excel', ['tahun' => 2026]));
        $excelResp->assertStatus(200);
        $excelResp->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_import_peraturan_desa(): void
    {
        $csvContent = "tahun,jenis_peraturan,nomor_ditetapkan,tanggal_ditetapkan,tentang,uraian_singkat,nomor_kesepakatan,nomor_dilaporkan,nomor_diundangkan,tanggal_diundangkan,keterangan\n";
        $csvContent .= "2026,perdes,02/2026,2026-02-01,Perdes RKPDes 2026,Rencana Kerja Pemerintah Desa,01/BPD/2026,140/01/2026,02/2026,2026-02-05,Berlaku\n";

        $file = UploadedFile::fake()->createWithContent('import_perdes.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('administrasi.peraturan-desa.import'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('administrasi.peraturan-desa.index'));
        $this->assertDatabaseHas('buku_peraturan_desa', [
            'nomor_ditetapkan' => '02/2026',
            'tentang' => 'Perdes RKPDes 2026',
        ]);
    }

    public function test_export_pdf_and_excel_keputusan_kades(): void
    {
        BukuKeputusanKades::create([
            'tahun' => 2026,
            'nomor_keputusan' => '141/01/KPTS/2026',
            'tanggal_keputusan' => '2026-01-05',
            'tentang' => 'Pembentukan Tim Pengadaan Barang Jasa',
            'uraian_singkat' => 'Penetapan panitia TPBJ desa',
            'created_by' => $this->admin->id,
        ]);

        $pdfResp = $this->actingAs($this->admin)->get(route('administrasi.keputusan-kades.export-pdf'));
        $pdfResp->assertStatus(200);

        $excelResp = $this->actingAs($this->admin)->get(route('administrasi.keputusan-kades.export-excel'));
        $excelResp->assertStatus(200);
    }

    public function test_export_and_import_inventaris_aset(): void
    {
        $csvContent = "tahun_pengadaan,jenis_barang,kode_barang,identitas_barang,asal_usul,harga_perolehan,kondisi,lokasi_penempatan\n";
        $csvContent .= "2026,Laptop Kantor,AST-01,Core i5 16GB,apbdes,12000000,baik,Sekretariat Desa\n";

        $file = UploadedFile::fake()->createWithContent('import_aset.csv', $csvContent);

        $resp = $this->actingAs($this->admin)->post(route('administrasi.inventaris-aset.import'), [
            'file' => $file,
        ]);

        $resp->assertRedirect(route('administrasi.inventaris-aset.index'));
        $this->assertDatabaseHas('buku_inventaris_aset', [
            'jenis_barang' => 'Laptop Kantor',
            'kode_barang' => 'AST-01',
        ]);

        $excelResp = $this->actingAs($this->admin)->get(route('administrasi.inventaris-aset.export-excel'));
        $excelResp->assertStatus(200);
    }

    public function test_export_and_import_aparatur(): void
    {
        $csvContent = "nik,nama_lengkap,nip,jabatan,status_kepegawaian,jam_masuk_standar,jam_pulang_standar,toleransi_terlambat_menit,status_aktif\n";
        $csvContent .= "3202111504709999,Hendra Wijaya,,Kasi Kesejahteraan,perangkat_desa,08:00:00,16:00:00,15,1\n";

        $file = UploadedFile::fake()->createWithContent('import_aparat.csv', $csvContent);

        $resp = $this->actingAs($this->admin)->post(route('administrasi.aparatur.import'), [
            'file' => $file,
        ]);

        $resp->assertRedirect(route('administrasi.aparatur.index'));
        $this->assertDatabaseHas('aparatur', [
            'jabatan' => 'Kasi Kesejahteraan',
        ]);

        $pdfResp = $this->actingAs($this->admin)->get(route('administrasi.aparatur.export-pdf'));
        $pdfResp->assertStatus(200);

        $excelResp = $this->actingAs($this->admin)->get(route('administrasi.aparatur.export-excel'));
        $excelResp->assertStatus(200);
    }

    public function test_export_and_import_kelembagaan(): void
    {
        $csvContent = "nik,nama_lengkap,jabatan,nomor_sk_pengangkatan,tanggal_sk,periode_mulai,periode_selesai,kontak,status_aktif,keterangan\n";
        $csvContent .= "3202111504708888,Siti Aminah,Sekretaris,141/02/SK/2026,2026-01-10,2026,2031,08123456789,1,Pengurus Inti\n";

        $file = UploadedFile::fake()->createWithContent('import_anggota.csv', $csvContent);

        $resp = $this->actingAs($this->admin)->post(route('administrasi.kelembagaan.import', ['institution' => $this->institution->slug]), [
            'file' => $file,
        ]);

        $resp->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->institution->slug, 'tab' => 'anggota']));
        $this->assertDatabaseHas('institution_members', [
            'institution_id' => $this->institution->id,
            'nama_lengkap' => 'Siti Aminah',
            'jabatan' => 'Sekretaris',
        ]);

        $pdfResp = $this->actingAs($this->admin)->get(route('administrasi.kelembagaan.export-pdf', ['institution' => $this->institution->slug]));
        $pdfResp->assertStatus(200);

        $excelResp = $this->actingAs($this->admin)->get(route('administrasi.kelembagaan.export-excel', ['institution' => $this->institution->slug]));
        $excelResp->assertStatus(200);
    }
}
