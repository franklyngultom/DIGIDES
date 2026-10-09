<?php

namespace Tests\Feature\Persuratan;

use App\Models\CitizenProfile;
use App\Models\DesaProfile;
use App\Models\PengajuanSurat;
use App\Models\SuratTemplate;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanAntreanTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected User $warga;
    protected SuratTemplate $template;
    protected PengajuanSurat $pengajuan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        DesaProfile::current();

        // 1. Staff Desa
        $this->staff = User::factory()->create([
            'name' => 'Staf Pelayanan',
            'email' => 'staf@desa.id',
            'is_active' => true,
        ]);
        $this->staff->assignRole('Staff Desa');

        // 2. Akun Masyarakat
        $this->warga = User::factory()->create([
            'name' => 'Ahmad Warga',
            'email' => 'ahmad@warga.id',
            'is_active' => true,
        ]);
        $this->warga->assignRole('Masyarakat');

        $profile = CitizenProfile::create([
            'user_id' => $this->warga->id,
            'nik' => '3201010101900001',
            'nama_lengkap' => 'Ahmad Warga',
            'alamat' => 'RT 01 RW 02 Desa Sukamaju',
        ]);

        // 3. Surat Template
        $this->template = SuratTemplate::create([
            'kode_surat' => 'SKTM',
            'nama_surat' => 'Surat Keterangan Tidak Mampu',
            'penomoran_format' => '{nomor}/SKTM/{bulan}/{tahun}',
            'template_blade' => 'sktm',
            'is_active' => true,
            'is_online_available' => true,
        ]);

        // 4. Sample Pengajuan
        $this->pengajuan = PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ-2026-000001',
            'user_id' => $this->warga->id,
            'citizen_profile_id' => $profile->id,
            'surat_template_id' => $this->template->id,
            'pemohon_nama' => 'Ahmad Warga',
            'pemohon_nik' => '3201010101900001',
            'pemohon_alamat' => 'RT 01 RW 02',
            'form_data_json' => ['keperluan' => 'Permohonan beasiswa kuliah'],
            'status' => 'menunggu',
        ]);
    }

    public function test_staff_can_view_pengajuan_queue(): void
    {
        $response = $this->actingAs($this->staff)->get(route('persuratan.antrean.index'));

        $response->assertOk();
        $response->assertSee('Antrean Pengajuan Surat Online');
        $response->assertSee('PGJ-2026-000001');
        $response->assertSee('Ahmad Warga');
    }

    public function test_citizen_cannot_access_staff_queue(): void
    {
        $response = $this->actingAs($this->warga)->get(route('persuratan.antrean.index'));

        $response->assertForbidden();
    }

    public function test_staff_can_view_pengajuan_detail(): void
    {
        $response = $this->actingAs($this->staff)->get(route('persuratan.antrean.show', $this->pengajuan));

        $response->assertOk();
        $response->assertSee('PGJ-2026-000001');
        $response->assertSee('Permohonan beasiswa kuliah');
        $response->assertSee('Tindakan Petugas Desa');
    }

    public function test_staff_can_update_status_to_diproses(): void
    {
        $response = $this->actingAs($this->staff)->post(route('persuratan.antrean.status', $this->pengajuan), [
            'action' => 'diproses',
            'catatan_petugas' => 'Berkas lengkap, sedang diteliti.',
            'pesan_ke_pemohon' => 'Permohonan Anda sedang kami verifikasi.',
        ]);

        $response->assertRedirect(route('persuratan.antrean.show', $this->pengajuan));
        $response->assertSessionHas('success');

        $this->pengajuan->refresh();
        $this->assertEquals('diproses', $this->pengajuan->status);
        $this->assertEquals($this->staff->id, $this->pengajuan->diproses_oleh);

        // Pastikan tercatat di audit trail logs
        $this->assertDatabaseHas('pengajuan_surat_logs', [
            'pengajuan_surat_id' => $this->pengajuan->id,
            'status_sebelum' => 'menunggu',
            'status_sesudah' => 'diproses',
        ]);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        // Dari 'menunggu' tidak boleh langsung ke 'selesai'
        $response = $this->actingAs($this->staff)->post(route('persuratan.antrean.status', $this->pengajuan), [
            'action' => 'selesai',
        ]);

        // Error message dalam session
        $response->assertSessionHas('error');

        $this->pengajuan->refresh();
        $this->assertEquals('menunggu', $this->pengajuan->status);
    }
}
