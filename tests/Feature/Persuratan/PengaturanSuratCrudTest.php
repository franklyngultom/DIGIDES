<?php

namespace Tests\Feature\Persuratan;

use App\Models\Aparatur;
use App\Models\DesaProfile;
use App\Models\Penduduk;
use App\Models\SuratPenandatangan;
use App\Models\SuratPengaturan;
use App\Models\SuratTemplate;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanSuratCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $staffAuthorized;
    protected SuratTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->staff = User::where('email', 'staff@desa.id')->first();

        // Staf khusus dengan izin persuratan.pengaturan
        $this->staffAuthorized = User::factory()->create([
            'name'      => 'Staf Administrator Surat',
            'email'     => 'staf.pengaturan@desa.id',
            'is_active' => true,
        ]);
        $this->staffAuthorized->assignRole('Staff Desa');
        $this->staffAuthorized->givePermissionTo('persuratan.pengaturan');

        $this->template = SuratTemplate::create([
            'kode_surat'          => 'SKTM',
            'nama_surat'          => 'Surat Keterangan Tidak Mampu',
            'penomoran_format'    => '400/{no}/{kode}/{bulan}/{tahun}',
            'template_blade'      => 'sktm',
            'is_active'           => true,
            'is_online_available' => true,
        ]);
    }

    public function test_admin_can_access_all_five_setting_pages(): void
    {
        // 1. Pengaturan Surat
        $this->actingAs($this->admin)->get(route('persuratan.pengaturan.index'))
            ->assertStatus(200)
            ->assertSee('Pengaturan Surat')
            ->assertSee('Nomor HP/WA Pelayanan');

        // 2. Format Cetak Surat
        $this->actingAs($this->admin)->get(route('persuratan.pengaturan.format-cetak'))
            ->assertStatus(200)
            ->assertSee('Pengaturan Format Cetak Surat')
            ->assertSee('Ukuran Kertas');

        // 3. Penomoran Surat
        $this->actingAs($this->admin)->get(route('persuratan.pengaturan.penomoran'))
            ->assertStatus(200)
            ->assertSee('Sistem Penomoran Surat')
            ->assertSee('Inkremen Terakhir');

        // 4. Kop Surat
        $this->actingAs($this->admin)->get(route('persuratan.pengaturan.kop-surat'))
            ->assertStatus(200)
            ->assertSee('Pengaturan Kop Surat')
            ->assertSee('Edit Kop Surat');

        // 5. Manajemen Tanda Tangan
        $this->actingAs($this->admin)->get(route('persuratan.pengaturan.tanda-tangan'))
            ->assertStatus(200)
            ->assertSee('Manajemen Tanda Tangan')
            ->assertSee('Bisa Tanda Tangan');
    }

    public function test_staff_without_permission_is_forbidden_and_with_permission_is_allowed(): void
    {
        // Staf biasa tanpa permission -> 403 Forbidden
        $this->actingAs($this->staff)->get(route('persuratan.pengaturan.index'))
            ->assertStatus(403);

        // Staf dengan permission -> 200 OK
        $this->actingAs($this->staffAuthorized)->get(route('persuratan.pengaturan.index'))
            ->assertStatus(200);
    }

    public function test_tab1_crud_pengaturan_surat_update(): void
    {
        $response = $this->actingAs($this->staffAuthorized)->post(route('persuratan.pengaturan.update'), [
            'no_hp_pelayanan' => '82199887766',
            'email_pelayanan' => 'layanan.desa@gmail.com',
            'format_alamat'   => ['dusun', 'desa', 'kecamatan', 'kabupaten'],
        ]);

        $response->assertRedirect();
        $pengaturan = SuratPengaturan::current();

        $this->assertEquals('82199887766', $pengaturan->no_hp_pelayanan);
        $this->assertEquals('layanan.desa@gmail.com', $pengaturan->email_pelayanan);
        $this->assertEquals(['dusun', 'desa', 'kecamatan', 'kabupaten'], $pengaturan->format_alamat_json);
    }

    public function test_tab2_crud_format_cetak_global_and_per_template(): void
    {
        // 1. Update Global Format Cetak
        $this->actingAs($this->staffAuthorized)->post(route('persuratan.pengaturan.format-cetak.update'), [
            'ukuran_kertas'     => 'F4 / Folio (215mm x 330mm)',
            'margin_atas'       => 25,
            'margin_kanan'      => 15,
            'margin_bawah'      => 25,
            'margin_kiri'       => 15,
            'jenis_font'        => 'Times New Roman',
            'ukuran_font'       => '11pt',
            'tinggi_baris'      => '1.5pt',
            'munculkan_pangkat' => 'Ya',
            'munculkan_nip'     => 'Ya',
            'munculkan_qr'      => 'Ya',
        ])->assertRedirect();

        $pengaturan = SuratPengaturan::current();
        $this->assertEquals('F4 / Folio (215mm x 330mm)', $pengaturan->ukuran_kertas);
        $this->assertEquals('Times New Roman', $pengaturan->jenis_font);
        $this->assertEquals(25, $pengaturan->margin_atas);

        // 2. Override Format Cetak Per Template Surat
        $this->actingAs($this->staffAuthorized)->put(route('persuratan.pengaturan.format-cetak.template', $this->template), [
            'ukuran_kertas' => 'A4',
            'jenis_font'    => 'Arial',
        ])->assertRedirect();

        $this->template->refresh();
        $this->assertEquals('A4', $this->template->custom_print_settings_json['ukuran_kertas']);
    }

    public function test_tab3_crud_penomoran_surat_update(): void
    {
        $this->actingAs($this->staffAuthorized)->post(route('persuratan.pengaturan.penomoran.update'), [
            'aturan_penomoran'  => 'Berlaku Secara Umum',
            'format_nomor_umum' => '[nnn]/[kode]/DESA/[br]/[t]',
            'inkremen_terakhir' => 45,
        ])->assertRedirect();

        $pengaturan = SuratPengaturan::current();
        $this->assertEquals('Berlaku Secara Umum', $pengaturan->aturan_penomoran);
        $this->assertEquals('[nnn]/[kode]/DESA/[br]/[t]', $pengaturan->format_nomor_umum);
        $this->assertEquals(45, $pengaturan->inkremen_terakhir);
    }

    public function test_tab4_crud_kop_surat_update_and_logo(): void
    {
        $fakeLogo = UploadedFile::fake()->image('logo_kop.png', 200, 200);

        $this->actingAs($this->staffAuthorized)->post(route('persuratan.pengaturan.kop-surat.update'), [
            'kop_baris_1' => 'PEMERINTAH KABUPATEN ROKAN HILIR',
            'kop_baris_2' => 'KECAMATAN BAGAN SINEMBAH',
            'kop_baris_3' => 'DESA BALAM SEMPURNA',
            'kop_alamat'  => 'Jl. Jenderal Sudirman No. 99, Kode Pos: 28992',
            'logo'        => $fakeLogo,
        ])->assertRedirect();

        $pengaturan = SuratPengaturan::current();
        $this->assertEquals('PEMERINTAH KABUPATEN ROKAN HILIR', $pengaturan->kop_baris_1);
        $this->assertEquals('DESA BALAM SEMPURNA', $pengaturan->kop_baris_3);
        $this->assertNotNull($pengaturan->kop_logo_path);
        Storage::disk('public')->assertExists($pengaturan->kop_logo_path);
    }

    public function test_tab5_crud_manajemen_tanda_tangan(): void
    {
        // 1. CREATE signer baru
        $this->actingAs($this->staffAuthorized)->post(route('persuratan.pengaturan.tanda-tangan.store'), [
            'nama'              => 'Fauziana, S.Tr, Keb',
            'jabatan'           => 'Kepala Desa',
            'nip'               => '198001012010012001',
            'urutan'            => 1,
            'bisa_tanda_tangan' => 1,
        ])->assertRedirect();

        $signer = SuratPenandatangan::where('nama', 'Fauziana, S.Tr, Keb')->first();
        $this->assertNotNull($signer);
        $this->assertEquals('Kepala Desa', $signer->jabatan);
        $this->assertTrue($signer->bisa_tanda_tangan);

        // 2. UPDATE signer
        $this->actingAs($this->staffAuthorized)->put(route('persuratan.pengaturan.tanda-tangan.update', $signer), [
            'nama'              => 'Fauziana, S.Tr, Keb (Pj)',
            'jabatan'           => 'Pj. Kepala Desa',
            'nip'               => '198001012010012001',
            'urutan'            => 1,
            'bisa_tanda_tangan' => 1,
        ])->assertRedirect();

        $signer->refresh();
        $this->assertEquals('Fauziana, S.Tr, Keb (Pj)', $signer->nama);

        // 3. TOGGLE status tanda tangan
        $this->actingAs($this->staffAuthorized)->patch(route('persuratan.pengaturan.tanda-tangan.toggle', $signer))
            ->assertRedirect();

        $signer->refresh();
        $this->assertFalse($signer->bisa_tanda_tangan);

        // 4. SYNC dari tabel Aparatur
        $penduduk = Penduduk::create([
            'nik'               => '3201019999990088',
            'no_kk'             => '3201010000000088',
            'nama_lengkap'      => 'Sekdes Baru',
            'jenis_kelamin'     => 'L',
            'tempat_lahir'      => 'Sukabumi',
            'tanggal_lahir'     => '1985-05-05',
            'agama'             => 'Islam',
            'status_perkawinan' => 'kawin',
            'pekerjaan'         => 'Perangkat Desa',
            'kewarganegaraan'   => 'WNI',
            'alamat_lengkap'    => 'Jl. Desa No. 10',
            'status_dasar'      => 'hidup',
        ]);

        $aparatur = Aparatur::create([
            'penduduk_id'        => $penduduk->id,
            'nip'                => '198505052010011005',
            'jabatan'            => 'Sekretaris Desa',
            'qr_token'           => 'token-sekdes-12345',
            'jam_masuk_standar'  => '08:00:00',
            'jam_pulang_standar' => '16:00:00',
            'status_kepegawaian' => 'perangkat_desa',
            'status_aktif'       => true,
        ]);

        $this->actingAs($this->staffAuthorized)->post(route('persuratan.pengaturan.tanda-tangan.sync-aparatur'))
            ->assertRedirect();

        $this->assertDatabaseHas('surat_penandatangans', [
            'aparatur_id' => $aparatur->id,
            'nama'        => 'Sekdes Baru',
            'jabatan'     => 'Sekretaris Desa',
        ]);

        // 5. DELETE signer
        $this->actingAs($this->staffAuthorized)->delete(route('persuratan.pengaturan.tanda-tangan.destroy', $signer))
            ->assertRedirect();

        $this->assertDatabaseMissing('surat_penandatangans', [
            'id' => $signer->id,
        ]);
    }
}
