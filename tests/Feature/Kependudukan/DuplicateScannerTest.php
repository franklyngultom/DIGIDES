<?php

namespace Tests\Feature\Kependudukan;

use App\Models\Penduduk;
use App\Models\User;
use App\Services\Kependudukan\DuplicateScannerService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateScannerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin Desa');
    }

    private function makePenduduk(string $nik, array $overrides = []): Penduduk
    {
        return Penduduk::create(array_merge([
            'nik'                   => $nik,
            'no_kk'                 => '3202110000000001',
            'nama_lengkap'          => 'Warga Test ' . $nik,
            'tempat_lahir'          => 'Sukabumi',
            'tanggal_lahir'         => '1990-01-01',
            'jenis_kelamin'         => 'L',
            'agama'                 => 'Islam',
            'status_perkawinan'     => 'belum_kawin',
            'status_dalam_keluarga' => 'kepala_keluarga',
            'alamat_lengkap'        => 'Jl. Cikole No. 1',
            'rt'                    => '001',
            'rw'                    => '001',
            'sumber_data'           => 'manual',
            'status_penduduk'       => 'tetap',
        ], $overrides));
    }

    public function test_scanner_detects_duplicate_niks(): void
    {
        // Insert via DB to bypass unique constraint in model (testing the scanner logic)
        \DB::table('penduduks')->insert([
            ['nik' => '3202111504700001', 'no_kk' => '3202110000000001', 'nama_lengkap' => 'Asep A', 'tempat_lahir' => 'Sukabumi', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'belum_kawin', 'status_dalam_keluarga' => 'kepala_keluarga', 'kewarganegaraan' => 'WNI', 'alamat_lengkap' => 'Jl. A', 'rt' => '001', 'rw' => '001', 'sumber_data' => 'manual', 'status_penduduk' => 'tetap', 'created_at' => now(), 'updated_at' => now()],
            ['nik' => '3202111504700001', 'no_kk' => '3202110000000002', 'nama_lengkap' => 'Asep B', 'tempat_lahir' => 'Sukabumi', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'belum_kawin', 'status_dalam_keluarga' => 'anak', 'kewarganegaraan' => 'WNI', 'alamat_lengkap' => 'Jl. B', 'rt' => '002', 'rw' => '001', 'sumber_data' => 'manual', 'status_penduduk' => 'tetap', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $scanner = app(DuplicateScannerService::class);
        $duplicates = $scanner->scanDuplicates();

        $this->assertCount(1, $duplicates);
        $this->assertEquals('3202111504700001', $duplicates->first()['nik']);
        $this->assertEquals(2, $duplicates->first()['count']);
    }

    public function test_scanner_detects_format_anomalies(): void
    {
        // Insert invalid format NIK via raw DB
        \DB::table('penduduks')->insert([
            'nik' => '12345', // Too short
            'no_kk' => '3202110000000001',
            'nama_lengkap' => 'Data Rusak',
            'tempat_lahir' => 'Sukabumi',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'belum_kawin',
            'status_dalam_keluarga' => 'kepala_keluarga',
            'kewarganegaraan' => 'WNI',
            'alamat_lengkap' => 'Jl. Test',
            'rt' => '001',
            'rw' => '001',
            'sumber_data' => 'manual',
            'status_penduduk' => 'tetap',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $scanner = app(DuplicateScannerService::class);
        $anomali = $scanner->scanAnomaliFormat();

        $this->assertCount(1, $anomali);
        $this->assertEquals('12345', $anomali->first()->nik);
    }

    public function test_full_scan_returns_correct_structure(): void
    {
        $scanner = app(DuplicateScannerService::class);
        $result = $scanner->fullScan();

        $this->assertArrayHasKey('duplicates', $result);
        $this->assertArrayHasKey('anomali', $result);
        $this->assertArrayHasKey('total_issues', $result);
        $this->assertEquals(0, $result['total_issues']);
    }

    public function test_admin_can_access_duplicate_scanner_page(): void
    {
        $this->actingAs($this->admin)
            ->get(route('kependudukan.duplicates'))
            ->assertOk()
            ->assertSee('Pemindai Duplikasi');
    }

    public function test_scanner_shows_no_issues_when_data_is_clean(): void
    {
        $this->makePenduduk('3202111504700001');
        $this->makePenduduk('3202110101800002');

        $scanner = app(DuplicateScannerService::class);
        $result = $scanner->fullScan();

        $this->assertEquals(0, $result['total_issues']);
        $this->assertEquals(0, $result['duplicates']->count());
        $this->assertEquals(0, $result['anomali']->count());
    }
}
