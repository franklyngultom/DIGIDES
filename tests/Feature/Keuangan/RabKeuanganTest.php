<?php

namespace Tests\Feature\Keuangan;

use App\Models\DesaProfile;
use App\Models\KeuanganRab;
use App\Models\KeuanganRabItem;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RabKeuanganTest extends TestCase
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

    public function test_user_with_permission_can_view_rab_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('keuangan.rab.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Rencana Anggaran Biaya (RAB) Desa');
        $response->assertSeeText('Buat RAB Baru');
    }

    public function test_admin_can_create_new_rab_with_multiple_items(): void
    {
        Storage::fake('public');
        $fakeFile = UploadedFile::fake()->create('rab_teknis.pdf', 500, 'application/pdf');

        $payload = [
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/99/DDS/2026',
            'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
            'sub_bidang'        => 'Pekerjaan Umum',
            'nama_kegiatan'     => 'Pembangunan Jembatan Gantung Penghubung Dusun',
            'lokasi'            => 'Sungai Citarum Dusun 3',
            'waktu_pelaksanaan' => '90 Hari Kalender',
            'sumber_dana'       => 'DDS',
            'nama_ppkd'         => 'Irvan Hermawan, S.T.',
            'jabatan_ppkd'      => 'Kasi Kesejahteraan',
            'status'            => 'draft',
            'keterangan'        => 'Jembatan gantung panjang 40 meter',
            'file_lampiran'     => $fakeFile,
            'items'             => [
                [
                    'kategori'      => 'bahan_material',
                    'kode_rekening' => '5.2.1.01',
                    'uraian'        => 'Semen Portland 50kg',
                    'volume'        => 100,
                    'satuan'        => 'Zak',
                    'harga_satuan'  => 70000,
                    'keterangan'    => 'SNI',
                ],
                [
                    'kategori'      => 'upah_tenaga_kerja',
                    'kode_rekening' => '5.2.1.02',
                    'uraian'        => 'Upah Tukang Las',
                    'volume'        => 20,
                    'satuan'        => 'HOK',
                    'harga_satuan'  => 150000,
                    'keterangan'    => 'Tukang bersertifikat',
                ],
                [
                    'kategori'      => 'sewa_alat',
                    'kode_rekening' => '5.2.1.03',
                    'uraian'        => 'Sewa Genset & Trafo Las',
                    'volume'        => 10,
                    'satuan'        => 'Hari',
                    'harga_satuan'  => 200000,
                    'keterangan'    => 'Kapasitas 5000 watt',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('keuangan.rab.store'), $payload);

        $response->assertRedirect(route('keuangan.rab.index', ['tahun' => 2026]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('keuangan_rabs', [
            'nomor_rab'      => 'RAB/99/DDS/2026',
            'nama_kegiatan'  => 'Pembangunan Jembatan Gantung Penghubung Dusun',
            'total_anggaran' => (100 * 70000) + (20 * 150000) + (10 * 200000), // 7.000.000 + 3.000.000 + 2.000.000 = 12.000.000
            'status'         => 'draft',
        ]);

        $rab = KeuanganRab::where('nomor_rab', 'RAB/99/DDS/2026')->first();
        $this->assertCount(3, $rab->items);
        $this->assertNotNull($rab->file_lampiran_path);
        Storage::disk('public')->assertExists($rab->file_lampiran_path);
    }

    public function test_user_can_view_rab_detail_and_category_breakdown(): void
    {
        $rab = KeuanganRab::create([
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/TEST/2026',
            'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
            'nama_kegiatan'     => 'Pembuatan Drainase Dusun Krajan',
            'lokasi'            => 'Dusun Krajan',
            'waktu_pelaksanaan' => '30 Hari',
            'sumber_dana'       => 'ADD',
            'total_anggaran'    => 10000000,
            'status'            => 'disetujui',
            'created_by'        => $this->admin->id,
        ]);

        $rab->items()->create([
            'kategori'     => 'bahan_material',
            'uraian'       => 'Batu Belah',
            'volume'       => 20,
            'satuan'       => 'M3',
            'harga_satuan' => 250000,
            'total_harga'  => 5000000,
        ]);

        $rab->items()->create([
            'kategori'     => 'upah_tenaga_kerja',
            'uraian'       => 'Upah Tukang',
            'volume'       => 50,
            'satuan'       => 'HOK',
            'harga_satuan' => 100000,
            'total_harga'  => 5000000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('keuangan.rab.show', $rab));

        $response->assertStatus(200);
        $response->assertSeeText('Pembuatan Drainase Dusun Krajan');
        $response->assertSeeText('RAB/TEST/2026');
        $response->assertSeeText('Batu Belah');
        $response->assertSeeText('Upah Tukang');
    }

    public function test_admin_can_update_rab_and_sync_items(): void
    {
        $rab = KeuanganRab::create([
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/OLD/2026',
            'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
            'nama_kegiatan'     => 'Kegiatan Lama',
            'lokasi'            => 'Dusun 1',
            'waktu_pelaksanaan' => '30 Hari',
            'sumber_dana'       => 'DDS',
            'total_anggaran'    => 5000000,
            'status'            => 'draft',
            'created_by'        => $this->admin->id,
        ]);

        $updatePayload = [
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/UPDATED/2026',
            'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
            'nama_kegiatan'     => 'Kegiatan Diperbarui',
            'lokasi'            => 'Dusun 2',
            'waktu_pelaksanaan' => '60 Hari',
            'sumber_dana'       => 'PAD',
            'status'            => 'disetujui',
            'items'             => [
                [
                    'kategori'      => 'operasional',
                    'uraian'        => 'Pengadaan ATK & Laporan',
                    'volume'        => 5,
                    'satuan'        => 'Paket',
                    'harga_satuan'  => 500000,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('keuangan.rab.update', $rab), $updatePayload);

        $response->assertRedirect(route('keuangan.rab.show', $rab));
        $this->assertDatabaseHas('keuangan_rabs', [
            'id'             => $rab->id,
            'nomor_rab'      => 'RAB/UPDATED/2026',
            'nama_kegiatan'  => 'Kegiatan Diperbarui',
            'total_anggaran' => 2500000,
            'status'         => 'disetujui',
        ]);
        $this->assertDatabaseHas('keuangan_rab_items', [
            'keuangan_rab_id' => $rab->id,
            'uraian'          => 'Pengadaan ATK & Laporan',
            'total_harga'     => 2500000,
        ]);
    }

    public function test_admin_can_update_rab_status(): void
    {
        $rab = KeuanganRab::create([
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/STATUS/2026',
            'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
            'nama_kegiatan'     => 'Uji Status',
            'lokasi'            => 'Dusun 1',
            'waktu_pelaksanaan' => '10 Hari',
            'sumber_dana'       => 'DDS',
            'status'            => 'draft',
            'created_by'        => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('keuangan.rab.update-status', $rab), [
            'status' => 'disetujui',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('keuangan_rabs', [
            'id'     => $rab->id,
            'status' => 'disetujui',
        ]);
    }

    public function test_admin_can_export_rab_pdf(): void
    {
        $rab = KeuanganRab::create([
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/PDF/2026',
            'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
            'nama_kegiatan'     => 'Pembangunan Posyandu',
            'lokasi'            => 'Dusun 3',
            'waktu_pelaksanaan' => '45 Hari',
            'sumber_dana'       => 'DDS',
            'total_anggaran'    => 35000000,
            'status'            => 'disetujui',
            'created_by'        => $this->admin->id,
        ]);

        $rab->items()->create([
            'kategori'     => 'bahan_material',
            'uraian'       => 'Cat Dinding Vinilex',
            'volume'       => 10,
            'satuan'       => 'Pail',
            'harga_satuan' => 650000,
            'total_harga'  => 6500000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('keuangan.rab.export-pdf', $rab));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_delete_rab(): void
    {
        $rab = KeuanganRab::create([
            'tahun_anggaran'    => 2026,
            'nomor_rab'         => 'RAB/DELETE/2026',
            'bidang'            => 'Bidang 1: Penyelenggaraan Pemerintahan Desa',
            'nama_kegiatan'     => 'Kegiatan Dihapus',
            'lokasi'            => 'Kantor Desa',
            'waktu_pelaksanaan' => '10 Hari',
            'sumber_dana'       => 'PAD',
            'status'            => 'draft',
            'created_by'        => $this->admin->id,
        ]);

        $rab->items()->create([
            'kategori'     => 'operasional',
            'uraian'       => 'Konsumsi Rapat',
            'volume'       => 50,
            'satuan'       => 'Kotak',
            'harga_satuan' => 25000,
            'total_harga'  => 1250000,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('keuangan.rab.destroy', $rab));

        $response->assertRedirect(route('keuangan.rab.index', ['tahun' => 2026]));
        $this->assertDatabaseMissing('keuangan_rabs', ['id' => $rab->id]);
        $this->assertDatabaseMissing('keuangan_rab_items', ['keuangan_rab_id' => $rab->id]);
    }
}
