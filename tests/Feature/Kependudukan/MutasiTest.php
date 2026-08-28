<?php

namespace Tests\Feature\Kependudukan;

use App\Models\Penduduk;
use App\Models\PendudukMutasi;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MutasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected Penduduk $penduduk;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->staff = User::factory()->create();
        $this->staff->assignRole('Staff Desa');

        $this->penduduk = Penduduk::create([
            'nik'                   => '3202111504700001',
            'no_kk'                 => '3202110000000001',
            'nama_lengkap'          => 'Yudi Permana',
            'tempat_lahir'          => 'Sukabumi',
            'tanggal_lahir'         => '1990-06-01',
            'jenis_kelamin'         => 'L',
            'agama'                 => 'Islam',
            'status_perkawinan'     => 'kawin',
            'status_dalam_keluarga' => 'kepala_keluarga',
            'alamat_lengkap'        => 'Kp. Cikole RT 001 RW 002',
            'rt'                    => '001',
            'rw'                    => '002',
            'sumber_data'           => 'manual',
            'status_penduduk'       => 'tetap',
        ]);
    }

    public function test_staff_can_record_pindah_keluar_mutasi(): void
    {
        $this->actingAs($this->staff)
            ->post(route('kependudukan.mutasi.store', $this->penduduk), [
                'jenis_mutasi'   => 'pindah_keluar',
                'tanggal_mutasi' => '2026-01-15',
                'keterangan'     => 'Pindah ke Kota Bogor.',
            ])
            ->assertRedirect(route('kependudukan.show', $this->penduduk));

        $this->assertDatabaseHas('penduduk_mutasis', [
            'penduduk_id'  => $this->penduduk->id,
            'jenis_mutasi' => 'pindah_keluar',
        ]);

        // Status should be updated to 'pindah'
        $this->assertEquals('pindah', $this->penduduk->fresh()->status_penduduk);
    }

    public function test_recording_mati_mutasi_updates_status_to_meninggal(): void
    {
        $this->actingAs($this->staff)
            ->post(route('kependudukan.mutasi.store', $this->penduduk), [
                'jenis_mutasi'   => 'mati',
                'tanggal_mutasi' => '2026-02-10',
                'keterangan'     => 'Meninggal dunia karena sakit.',
            ])
            ->assertRedirect();

        $this->assertEquals('meninggal', $this->penduduk->fresh()->status_penduduk);
        $this->assertDatabaseHas('penduduk_mutasis', [
            'penduduk_id'  => $this->penduduk->id,
            'jenis_mutasi' => 'mati',
        ]);
    }

    public function test_mutasi_must_not_be_future_dated(): void
    {
        $this->actingAs($this->staff)
            ->post(route('kependudukan.mutasi.store', $this->penduduk), [
                'jenis_mutasi'   => 'lahir',
                'tanggal_mutasi' => now()->addDays(30)->format('Y-m-d'),
            ])
            ->assertSessionHasErrors('tanggal_mutasi');
    }

    public function test_mutasi_label_accessor_returns_correct_bahasa(): void
    {
        $mutasi = PendudukMutasi::create([
            'penduduk_id'    => $this->penduduk->id,
            'jenis_mutasi'   => 'pindah_masuk',
            'tanggal_mutasi' => '2026-01-01',
            'created_by'     => $this->staff->id,
        ]);

        $this->assertEquals('Pindah Masuk', $mutasi->jenis_mutasi_label);
    }

    public function test_staff_can_view_buku_register_mutasi(): void
    {
        $this->actingAs($this->staff)
            ->get(route('kependudukan.mutasi.index'))
            ->assertOk()
            ->assertSee('Buku Register Mutasi Penduduk');
    }
}
