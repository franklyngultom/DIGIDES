<?php

namespace Tests\Feature\Pembangunan;

use App\Models\PembangunanInventarisHasil;
use App\Models\PembangunanProyek;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InventarisHasilPembangunanTest extends TestCase
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

    public function test_user_with_permission_can_view_inventaris_hasil_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('pembangunan.inventaris-hasil.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Buku Inventaris Hasil Pembangunan');
        $response->assertSeeText('Catat Inventaris Baru');
    }

    public function test_admin_can_create_new_inventaris_hasil_with_photos_and_bast(): void
    {
        Storage::fake('public');
        $fotoFake = UploadedFile::fake()->image('hasil_fisik.jpg');
        $bastFake = UploadedFile::fake()->create('bast_resmi.pdf', 300, 'application/pdf');

        $payload = [
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/099',
            'nama_hasil_pembangunan' => 'Gedung Serbaguna Dusun 3',
            'kategori_aset'           => 'bangunan_gedung',
            'volume'                  => '1 Unit Bangunan 10m x 15m',
            'lokasi'                  => 'Dusun 3 RT 01',
            'tanggal_serah_terima'    => '2026-04-10',
            'sumber_dana'             => 'DDS',
            'nilai_aset'              => 120000000,
            'kondisi'                 => 'baik',
            'status_pengelolaan'      => 'dikelola_desa',
            'penanggung_jawab'        => 'Kaur Pembangunan',
            'keterangan'              => 'Gedung pertemuan dan pos pelayanan terpadu',
            'foto_hasil'              => $fotoFake,
            'file_bast'               => $bastFake,
        ];

        $response = $this->actingAs($this->admin)->post(route('pembangunan.inventaris-hasil.store'), $payload);

        $response->assertRedirect(route('pembangunan.inventaris-hasil.index', ['tahun' => 2026]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pembangunan_inventaris_hasil', [
            'nomor_inventaris'        => 'INV-BANG/2026/099',
            'nama_hasil_pembangunan' => 'Gedung Serbaguna Dusun 3',
            'nilai_aset'              => 120000000,
            'kondisi'                 => 'baik',
        ]);

        $item = PembangunanInventarisHasil::where('nomor_inventaris', 'INV-BANG/2026/099')->first();
        $this->assertNotNull($item->foto_hasil_path);
        $this->assertNotNull($item->file_bast_path);

        Storage::disk('public')->assertExists($item->foto_hasil_path);
        Storage::disk('public')->assertExists($item->file_bast_path);
    }

    public function test_admin_can_create_inventaris_from_completed_proyek(): void
    {
        $proyek = PembangunanProyek::create([
            'tahun_anggaran'     => 2026,
            'nama_kegiatan'      => 'Pembangunan Jembatan Gantung Dusun 2',
            'lokasi'             => 'Sungai Babakan',
            'volume'             => 'Panjang 30 Meter',
            'anggaran_biaya'     => 80000000,
            'realisasi_biaya'    => 78500000,
            'sumber_dana'        => 'DDS',
            'pelaksana_tpk'      => 'TPK Dusun 2',
            'status_progres'     => 'selesai',
            'persentase_selesai' => 100,
        ]);

        $createPageResponse = $this->actingAs($this->admin)->get(route('pembangunan.inventaris-hasil.create', ['proyek_id' => $proyek->id]));
        $createPageResponse->assertStatus(200);
        $createPageResponse->assertSeeText($proyek->nama_kegiatan);

        $payload = [
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/100',
            'nama_hasil_pembangunan' => $proyek->nama_kegiatan,
            'pembangunan_proyek_id'   => $proyek->id,
            'kategori_aset'           => 'jalan_jembatan',
            'volume'                  => $proyek->volume,
            'lokasi'                  => $proyek->lokasi,
            'tanggal_serah_terima'    => '2026-05-01',
            'sumber_dana'             => $proyek->sumber_dana,
            'nilai_aset'              => $proyek->realisasi_biaya,
            'kondisi'                 => 'baik',
            'status_pengelolaan'      => 'dikelola_desa',
            'penanggung_jawab'        => 'Kaur Pembangunan',
        ];

        $response = $this->actingAs($this->admin)->post(route('pembangunan.inventaris-hasil.store'), $payload);

        $response->assertRedirect(route('pembangunan.inventaris-hasil.index', ['tahun' => 2026]));
        $this->assertDatabaseHas('pembangunan_inventaris_hasil', [
            'nomor_inventaris'      => 'INV-BANG/2026/100',
            'pembangunan_proyek_id' => $proyek->id,
            'nilai_aset'            => 78500000,
        ]);

        $this->assertNotNull($proyek->fresh()->inventarisHasil);
    }

    public function test_user_can_view_inventaris_hasil_detail(): void
    {
        $item = PembangunanInventarisHasil::create([
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/101',
            'nama_hasil_pembangunan' => 'Sarana MCK Posyandu',
            'kategori_aset'           => 'sarana_air_bersih',
            'volume'                  => '1 Unit',
            'lokasi'                  => 'Dusun 1 RT 02',
            'tanggal_serah_terima'    => '2026-01-20',
            'sumber_dana'             => 'ADD',
            'nilai_aset'              => 25000000,
            'kondisi'                 => 'rusak_ringan',
            'status_pengelolaan'      => 'diserahkan_ke_masyarakat',
            'penanggung_jawab'        => 'Ketua RW 01',
            'created_by'              => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('pembangunan.inventaris-hasil.show', $item));

        $response->assertStatus(200);
        $response->assertSeeText('INV-BANG/2026/101');
        $response->assertSeeText('Sarana MCK Posyandu');
        $response->assertSeeText('Rusak Ringan');
        $response->assertSeeText('Diserahkan ke Masyarakat');
    }

    public function test_admin_can_update_inventaris_hasil(): void
    {
        $item = PembangunanInventarisHasil::create([
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/102',
            'nama_hasil_pembangunan' => 'Jalan Setapak Lama',
            'kategori_aset'           => 'jalan_jembatan',
            'volume'                  => '200m',
            'lokasi'                  => 'Dusun 2',
            'sumber_dana'             => 'DDS',
            'nilai_aset'              => 30000000,
            'kondisi'                 => 'baik',
            'status_pengelolaan'      => 'dikelola_desa',
            'created_by'              => $this->admin->id,
        ]);

        $updatePayload = [
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/102-REV',
            'nama_hasil_pembangunan' => 'Jalan Setapak Rabat Diperbaiki',
            'kategori_aset'           => 'jalan_jembatan',
            'volume'                  => '250m',
            'lokasi'                  => 'Dusun 2 RT 03',
            'tanggal_serah_terima'    => '2026-03-01',
            'sumber_dana'             => 'PAD',
            'nilai_aset'              => 35000000,
            'kondisi'                 => 'baik',
            'status_pengelolaan'      => 'diserahkan_ke_masyarakat',
            'penanggung_jawab'        => 'Ketua RW 02',
        ];

        $response = $this->actingAs($this->admin)->put(route('pembangunan.inventaris-hasil.update', $item), $updatePayload);

        $response->assertRedirect(route('pembangunan.inventaris-hasil.show', $item));
        $this->assertDatabaseHas('pembangunan_inventaris_hasil', [
            'id'                      => $item->id,
            'nomor_inventaris'        => 'INV-BANG/2026/102-REV',
            'nama_hasil_pembangunan' => 'Jalan Setapak Rabat Diperbaiki',
            'nilai_aset'              => 35000000,
        ]);
    }

    public function test_admin_can_export_inventaris_hasil_pdf(): void
    {
        PembangunanInventarisHasil::create([
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/103',
            'nama_hasil_pembangunan' => 'Pipa Saluran Irigasi Tersier',
            'kategori_aset'           => 'irigasi_sanitasi',
            'volume'                  => '500m',
            'lokasi'                  => 'Blok Sawah Krajan',
            'sumber_dana'             => 'DDS',
            'nilai_aset'              => 55000000,
            'kondisi'                 => 'baik',
            'status_pengelolaan'      => 'dikelola_desa',
            'created_by'              => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('pembangunan.inventaris-hasil.export-pdf', ['tahun' => 2026]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_delete_inventaris_hasil(): void
    {
        $item = PembangunanInventarisHasil::create([
            'tahun_anggaran'          => 2026,
            'nomor_inventaris'        => 'INV-BANG/2026/104',
            'nama_hasil_pembangunan' => 'Pos Ronda Hapus',
            'kategori_aset'           => 'fasilitas_umum',
            'volume'                  => '1 Unit',
            'lokasi'                  => 'RT 05',
            'sumber_dana'             => 'Swadaya',
            'nilai_aset'              => 10000000,
            'kondisi'                 => 'rusak_berat',
            'status_pengelolaan'      => 'diserahkan_ke_masyarakat',
            'created_by'              => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('pembangunan.inventaris-hasil.destroy', $item));

        $response->assertRedirect(route('pembangunan.inventaris-hasil.index', ['tahun' => 2026]));
        $this->assertDatabaseMissing('pembangunan_inventaris_hasil', ['id' => $item->id]);
    }
}
