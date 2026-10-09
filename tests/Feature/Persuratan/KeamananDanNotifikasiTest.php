<?php

namespace Tests\Feature\Persuratan;

use App\Models\CitizenProfile;
use App\Models\DesaProfile;
use App\Models\PengajuanSurat;
use App\Models\SuratTemplate;
use App\Models\User;
use App\Notifications\StatusPengajuanNotification;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KeamananDanNotifikasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected User $citizenA;
    protected User $citizenB;
    protected SuratTemplate $template;
    protected PengajuanSurat $pengajuanA;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('private');
        Storage::fake('public');

        $this->seed(RoleAndPermissionSeeder::class);
        DesaProfile::current();

        // 1. Staff Desa
        $this->staff = User::factory()->create([
            'name'      => 'Staf Pelayanan Keamanan',
            'email'     => 'staf.keamanan@desa.id',
            'is_active' => true,
        ]);
        $this->staff->assignRole('Staff Desa');

        // 2. Warga A
        $this->citizenA = User::factory()->create([
            'name'      => 'Ahmad Warga A',
            'email'     => 'ahmad@warga.id',
            'is_active' => true,
        ]);
        $this->citizenA->assignRole('Masyarakat');

        $profileA = CitizenProfile::create([
            'user_id'           => $this->citizenA->id,
            'nik'               => '3201010101900010',
            'nama_lengkap'      => 'Ahmad Warga A',
            'alamat'            => 'RT 01 RW 01 Desa Sukamaju',
            'tempat_lahir'      => 'Sukabumi',
            'tanggal_lahir'     => '1990-01-01',
            'jenis_kelamin'     => 'L',
            'status_verifikasi' => 'terverifikasi',
        ]);

        // 3. Warga B (attacker / separate citizen)
        $this->citizenB = User::factory()->create([
            'name'      => 'Bambang Warga B',
            'email'     => 'bambang@warga.id',
            'is_active' => true,
        ]);
        $this->citizenB->assignRole('Masyarakat');

        CitizenProfile::create([
            'user_id'           => $this->citizenB->id,
            'nik'               => '3201010101900020',
            'nama_lengkap'      => 'Bambang Warga B',
            'alamat'            => 'RT 02 RW 02 Desa Sukamaju',
            'tempat_lahir'      => 'Sukabumi',
            'tanggal_lahir'     => '1992-02-02',
            'jenis_kelamin'     => 'L',
            'status_verifikasi' => 'terverifikasi',
        ]);

        // 4. Template Surat
        $this->template = SuratTemplate::create([
            'kode_surat'          => 'SKTM',
            'nama_surat'          => 'Surat Keterangan Tidak Mampu',
            'penomoran_format'    => '400/{no}/{kode}/{bulan}/{tahun}',
            'template_blade'      => 'sktm',
            'is_active'           => true,
            'is_online_available' => true,
        ]);

        // 5. Pengajuan milik Warga A
        $this->pengajuanA = PengajuanSurat::create([
            'nomor_pengajuan'    => 'PGJ-2026-000777',
            'user_id'            => $this->citizenA->id,
            'citizen_profile_id' => $profileA->id,
            'surat_template_id'  => $this->template->id,
            'pemohon_nama'       => 'Ahmad Warga A',
            'pemohon_nik'        => '3201010101900010',
            'pemohon_alamat'     => 'RT 01 RW 01 Desa Sukamaju',
            'form_data_json'     => ['keperluan' => 'Bantuan Beasiswa Pendidikan'],
            'dokumen_path_json'  => [
                [
                    'path'          => 'pengajuan/'.$this->citizenA->id.'/kk_ahmad.pdf',
                    'original_name' => 'kk_ahmad.pdf',
                    'mime'          => 'application/pdf',
                ]
            ],
            'status'             => 'menunggu',
        ]);

        Storage::disk('private')->put('pengajuan/'.$this->citizenA->id.'/kk_ahmad.pdf', 'dummy pdf content');
    }

    /**
     * Test 1: Citizen receives database notification upon status update & data doesn't leak NIK.
     */
    public function test_citizen_receives_database_notification_without_sensitive_data(): void
    {
        $this->actingAs($this->staff)->post(route('persuratan.antrean.status', $this->pengajuanA), [
            'action'           => 'perlu_perbaikan',
            'catatan_petugas'  => 'Foto scan kartu keluarga buram',
            'pesan_ke_pemohon' => 'Mohon unggah ulang scan KK yang terbaca jelas.',
        ]);

        $this->assertDatabaseHas('pengajuan_surat', [
            'id'     => $this->pengajuanA->id,
            'status' => 'perlu_perbaikan',
        ]);

        // Cek notification di tabel notifications
        $this->assertEquals(1, $this->citizenA->notifications()->count());
        $notif = $this->citizenA->notifications()->first();

        $this->assertEquals('perlu_perbaikan', $notif->data['status']);
        $this->assertEquals('Perlu Perbaikan Berkas', $notif->data['title']);
        $this->assertStringContainsString('Mohon unggah ulang scan KK', $notif->data['message']);

        // Data Privacy Check: NIK and sensitive documents must NOT exist in notification payload
        $this->assertArrayNotHasKey('nik', $notif->data);
        $this->assertArrayNotHasKey('pemohon_nik', $notif->data);
        $this->assertArrayNotHasKey('dokumen', $notif->data);
        $this->assertArrayNotHasKey('password', $notif->data);
    }

    /**
     * Test 2: Citizen receives database notification when official letter is issued.
     */
    public function test_citizen_receives_notification_when_letter_is_issued(): void
    {
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuanA), [
            'catatan_petugas'  => 'Berkas lengkap dan sah',
            'pesan_ke_pemohon' => 'Surat Anda telah resmi diterbitkan, silakan unduh di portal.',
        ]);

        $notif = $this->citizenA->notifications()->latest()->first();
        $this->assertNotNull($notif);
        $this->assertEquals('selesai', $notif->data['status']);
        $this->assertEquals('Surat Resmi Selesai Diterbitkan', $notif->data['title']);
        $this->assertStringContainsString('Surat Anda telah resmi diterbitkan', $notif->data['message']);
    }

    /**
     * Test 3: Citizen can browse notification list, mark as read, and mark all as read.
     */
    public function test_citizen_can_read_and_manage_notifications(): void
    {
        // Beri 2 notifikasi ke Warga A
        $this->citizenA->notify(new StatusPengajuanNotification($this->pengajuanA, 'Notifikasi 1'));
        $this->citizenA->notify(new StatusPengajuanNotification($this->pengajuanA, 'Notifikasi 2'));

        $this->assertEquals(2, $this->citizenA->unreadNotifications()->count());

        // Buka halaman notifikasi
        $response = $this->actingAs($this->citizenA)->get(route('masyarakat.notifikasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Pemberitahuan');
        $response->assertSee('Notifikasi 1');
        $response->assertSee('Notifikasi 2');

        // Baca 1 notifikasi
        $notif1 = $this->citizenA->unreadNotifications()->first();
        $readResponse = $this->actingAs($this->citizenA)->get(route('masyarakat.notifikasi.read', $notif1->id));
        $readResponse->assertRedirect(route('masyarakat.pengajuan.show', $this->pengajuanA));

        $this->assertEquals(1, $this->citizenA->unreadNotifications()->count());

        // Tandai semua dibaca
        $this->actingAs($this->citizenA)->post(route('masyarakat.notifikasi.read-all'));
        $this->assertEquals(0, $this->citizenA->unreadNotifications()->count());
    }

    /**
     * Test 4: IDOR Protection — Citizen B cannot view Citizen A's submission, notification, or private files.
     */
    public function test_idor_protection_prevents_unauthorized_cross_account_access(): void
    {
        // 1. Warga B mencoba melihat detail pengajuan milik Warga A
        $response = $this->actingAs($this->citizenB)->get(route('masyarakat.pengajuan.show', $this->pengajuanA));
        $response->assertStatus(403);

        // 2. Warga B mencoba mengunduh dokumen privat milik Warga A
        $responseDoc = $this->actingAs($this->citizenB)->get(route('masyarakat.pengajuan.dokumen.download', [
            'pengajuan' => $this->pengajuanA,
            'index'     => 0,
        ]));
        $responseDoc->assertStatus(403);

        // 3. Warga B mencoba menandai notifikasi milik Warga A
        $this->citizenA->notify(new StatusPengajuanNotification($this->pengajuanA, 'Pesan Rahasia'));
        $notifA = $this->citizenA->notifications()->first();

        $responseNotif = $this->actingAs($this->citizenB)->get(route('masyarakat.notifikasi.read', $notifA->id));
        $responseNotif->assertStatus(404); // Scoped to user notifications, should not be found
    }

    /**
     * Test 5: Unauthenticated guest redirected to login for portal masyarakat.
     */
    public function test_guest_is_redirected_to_login_on_portal_masyarakat(): void
    {
        $this->get(route('masyarakat.dashboard'))->assertRedirect(route('login'));
        $this->get(route('masyarakat.pengajuan.index'))->assertRedirect(route('login'));
        $this->get(route('masyarakat.notifikasi.index'))->assertRedirect(route('login'));
        $this->get(route('masyarakat.profil'))->assertRedirect(route('login'));
    }

    /**
     * Test 6: File upload security — rejects disallowed extensions and executable files.
     */
    public function test_file_upload_security_blocks_malicious_executable_files(): void
    {
        $maliciousFile = UploadedFile::fake()->create('exploit.php', 100, 'application/x-php');

        $response = $this->actingAs($this->citizenA)->post(route('masyarakat.pengajuan.store'), [
            'surat_template_id' => $this->template->id,
            'keperluan'         => 'Test Keamanan File Upload',
            'dokumen'           => [$maliciousFile],
        ]);

        $response->assertSessionHasErrors('dokumen.0');
    }
}
