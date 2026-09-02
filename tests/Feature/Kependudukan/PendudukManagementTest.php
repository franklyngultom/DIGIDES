<?php

namespace Tests\Feature\Kependudukan;

use App\Models\Penduduk;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendudukManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->admin = User::factory()->create(['email' => 'admin@test.id']);
        $this->admin->assignRole('Admin Desa');

        $this->staff = User::factory()->create(['email' => 'staff@test.id']);
        $this->staff->assignRole('Staff Desa');
    }

    private function pendudukData(array $overrides = []): array
    {
        return array_merge([
            'nik'                   => '3202111504700001',
            'no_kk'                 => '3202111504700001',
            'nama_lengkap'          => 'Asep Suhendar',
            'tempat_lahir'          => 'Sukabumi',
            'tanggal_lahir'         => '1980-01-01',
            'jenis_kelamin'         => 'L',
            'agama'                 => 'Islam',
            'status_perkawinan'     => 'kawin',
            'status_dalam_keluarga' => 'kepala_keluarga',
            'alamat_lengkap'        => 'Kp. Cikole RT 001 RW 002',
            'rt'                    => '001',
            'rw'                    => '002',
            'sumber_data'           => 'manual',
            'status_penduduk'       => 'tetap',
        ], $overrides);
    }

    public function test_guest_cannot_access_kependudukan(): void
    {
        $this->get(route('kependudukan.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_view_buku_induk_penduduk(): void
    {
        $this->actingAs($this->staff)
            ->get(route('kependudukan.index'))
            ->assertOk()
            ->assertSee('Buku Induk Kependudukan');
    }

    public function test_staff_can_create_penduduk_with_valid_nik(): void
    {
        $this->actingAs($this->staff)
            ->post(route('kependudukan.store'), $this->pendudukData())
            ->assertRedirect();

        $this->assertDatabaseHas('penduduks', ['nik' => '3202111504700001']);
    }

    public function test_nik_must_be_exactly_16_digits(): void
    {
        $this->actingAs($this->staff)
            ->post(route('kependudukan.store'), $this->pendudukData(['nik' => '12345']))
            ->assertSessionHasErrors('nik');
    }

    public function test_nik_must_be_numeric_only(): void
    {
        $this->actingAs($this->staff)
            ->post(route('kependudukan.store'), $this->pendudukData(['nik' => '320211ABCD000001']))
            ->assertSessionHasErrors('nik');
    }

    public function test_nik_must_be_unique(): void
    {
        Penduduk::create($this->pendudukData());

        $this->actingAs($this->staff)
            ->post(route('kependudukan.store'), $this->pendudukData(['nama_lengkap' => 'Orang Lain']))
            ->assertSessionHasErrors('nik');
    }

    public function test_admin_can_show_penduduk_detail(): void
    {
        $penduduk = Penduduk::create($this->pendudukData());

        $this->actingAs($this->admin)
            ->get(route('kependudukan.show', $penduduk))
            ->assertOk()
            ->assertSee($penduduk->nama_lengkap);
    }

    public function test_staff_can_update_penduduk_data(): void
    {
        $penduduk = Penduduk::create($this->pendudukData());

        $this->actingAs($this->staff)
            ->put(route('kependudukan.update', $penduduk), $this->pendudukData(['pekerjaan' => 'Guru']))
            ->assertRedirect(route('kependudukan.show', $penduduk));

        $this->assertDatabaseHas('penduduks', ['id' => $penduduk->id, 'pekerjaan' => 'Guru']);
    }

    public function test_admin_can_delete_penduduk(): void
    {
        $penduduk = Penduduk::create($this->pendudukData());

        $this->actingAs($this->admin)
            ->delete(route('kependudukan.destroy', $penduduk))
            ->assertRedirect(route('kependudukan.index'));

        $this->assertDatabaseMissing('penduduks', ['id' => $penduduk->id]);
    }

    public function test_masked_nik_returns_correct_format(): void
    {
        $penduduk = Penduduk::create($this->pendudukData(['nik' => '3202111504700001']));

        $this->assertEquals('320211******0001', $penduduk->masked_nik);
    }

    public function test_age_category_is_computed_correctly(): void
    {
        $balita = Penduduk::create($this->pendudukData([
            'nik' => '3202115504210001',
            'tanggal_lahir' => now()->subYears(3)->format('Y-m-d'),
        ]));
        $this->assertEquals('Balita', $balita->kategori_usia);

        $lansia = Penduduk::create($this->pendudukData([
            'nik' => '3202111504500002',
            'tanggal_lahir' => '1950-01-01',
        ]));
        $this->assertEquals('Lansia', $lansia->kategori_usia);
    }

    public function test_staff_can_export_buku_induk_to_pdf(): void
    {
        Penduduk::create($this->pendudukData());

        $response = $this->actingAs($this->staff)
            ->get(route('kependudukan.export-pdf'));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_staff_can_export_buku_induk_to_excel_and_csv(): void
    {
        Penduduk::create($this->pendudukData());

        $excelResponse = $this->actingAs($this->staff)
            ->get(route('kependudukan.export-excel'));
        $excelResponse->assertOk();
        $this->assertStringContainsString('text/csv', $excelResponse->headers->get('content-type'));

        $csvResponse = $this->actingAs($this->staff)
            ->get(route('kependudukan.export-csv'));
        $csvResponse->assertOk();
    }

    public function test_staff_can_download_import_template(): void
    {
        $response = $this->actingAs($this->staff)
            ->get(route('kependudukan.import-template'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_staff_can_import_residents_from_csv(): void
    {
        $csvContent = "\xEF\xBB\xBFnik,no_kk,nama_lengkap,tempat_lahir,tanggal_lahir,jenis_kelamin,agama,status_perkawinan,status_dalam_keluarga,alamat_lengkap,rt,rw,status_penduduk\n"
            . "3202110101900001,3202110101900000,Budi Santoso,Sukabumi,1990-05-10,L,Islam,kawin,kepala_keluarga,Jl. Cikole,001,002,tetap\n";

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($this->staff)
            ->post(route('kependudukan.import'), ['file' => $file]);

        $response->assertRedirect(route('kependudukan.index'));
        $this->assertDatabaseHas('penduduks', [
            'nik' => '3202110101900001',
            'nama_lengkap' => 'Budi Santoso',
        ]);
    }

    public function test_admin_can_bulk_delete_residents(): void
    {
        $p1 = Penduduk::create($this->pendudukData(['nik' => '3202110101800001']));
        $p2 = Penduduk::create($this->pendudukData(['nik' => '3202110101800002']));

        $response = $this->actingAs($this->admin)
            ->post(route('kependudukan.bulk-delete'), [
                'ids' => "{$p1->id},{$p2->id}",
            ]);

        $response->assertRedirect(route('kependudukan.index'));
        $this->assertDatabaseMissing('penduduks', ['id' => $p1->id]);
        $this->assertDatabaseMissing('penduduks', ['id' => $p2->id]);
    }
}

