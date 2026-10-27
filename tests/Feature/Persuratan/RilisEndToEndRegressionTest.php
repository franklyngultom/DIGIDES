<?php

namespace Tests\Feature\Persuratan;

use App\Models\BukuAgenda;
use App\Models\BukuEkspedisi;
use App\Models\CitizenProfile;
use App\Models\DesaProfile;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\SuratArsip;
use App\Models\SuratTemplate;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RilisEndToEndRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected SuratTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('private');
        Storage::fake('public');

        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->staff = User::where('email', 'staff@desa.id')->first();

        // 1. Template Surat Keterangan Usaha (Fase 03)
        $this->template = SuratTemplate::create([
            'kode_surat' => 'SKU',
            'nama_surat' => 'Surat Keterangan Usaha',
            'deskripsi' => 'Surat keterangan kepemilikan usaha mikro/kecil warga desa.',
            'penomoran_format' => '{nomor}/SKU/{bulan}/{tahun}',
            'template_blade' => 'sku',
            'persyaratan_json' => ['KTP', 'Foto tempat usaha'],
            'dokumen_wajib_json' => ['KTP'],
            'estimasi_proses' => '1 hari kerja',
            'biaya' => 'Gratis',
            'is_online_available' => true,
            'is_active' => true,
        ]);
    }

    public function test_phase8_complete_e2e_lifecycle_and_regression(): void
    {
        // =========================================================================
        // 1. UJI PUBLIK: Halaman publik dapat diakses tanpa login (Fase 01)
        // =========================================================================
        $this->get('/')->assertStatus(200);
        $this->get('/profil-desa')->assertStatus(200);
        $this->get('/berita')->assertStatus(200);
        $this->get('/kontak')->assertStatus(200);
        $this->get('/layanan')->assertStatus(200)->assertSee('Surat Keterangan Usaha');
        $this->get('/lacak-surat')->assertStatus(200);

        // Siapkan data penduduk master di desa
        $nikWarga = '3201012304950001';
        $penduduk = Penduduk::create([
            'nik' => $nikWarga,
            'no_kk' => '3201010101010001',
            'nama_lengkap' => 'Warga Bahagia',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Sukamaju',
            'tanggal_lahir' => '1995-04-23',
            'agama' => 'Islam',
            'status_perkawinan' => 'kawin',
            'pekerjaan' => 'Wiraswasta',
            'kewarganegaraan' => 'WNI',
            'alamat_lengkap' => 'Jl. Mawar No. 12 RT 01 RW 02',
            'rt' => '01',
            'rw' => '02',
            'dusun' => 'Dusun I',
            'status_dasar' => 'hidup',
        ]);

        // =========================================================================
        // 2. UJI REGISTRASI & AUTENTIKASI WARGA (Fase 02)
        // =========================================================================
        $registerResponse = $this->post('/register', [
            'name' => 'Warga Bahagia',
            'nik' => $nikWarga,
            'email' => 'warga.bahagia@desa.id',
            'phone' => '081299887766',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $registerResponse->assertRedirect(route('masyarakat.dashboard'));
        $this->assertAuthenticated();

        $warga = User::where('email', 'warga.bahagia@desa.id')->first();
        $this->assertNotNull($warga);
        $this->assertTrue($warga->hasRole('Masyarakat'));

        // Cek citizen profile terbuat dan tertaut dengan data penduduk
        $profile = CitizenProfile::where('user_id', $warga->id)->first();
        $this->assertNotNull($profile);
        $this->assertEquals($penduduk->id, $profile->penduduk_id);
        $this->assertEquals($nikWarga, $profile->nik);

        // =========================================================================
        // 3. UJI ISOLASI AKSES: Warga DILARANG masuk area internal
        // =========================================================================
        $this->actingAs($warga)->get('/dashboard')->assertRedirect(route('masyarakat.dashboard'));
        $this->actingAs($warga)->get('/kependudukan')->assertStatus(403);
        $this->actingAs($warga)->get('/persuratan/antrean')->assertStatus(403);
        $this->actingAs($warga)->get('/admin/backup')->assertStatus(403);

        // Tamu dilarang masuk portal masyarakat
        $this->post('/logout');
        $this->assertGuest();
        $this->get('/masyarakat/dashboard')->assertRedirect(route('login'));

        // =========================================================================
        // 4. UJI PENGAJUAN SURAT ONLINE & VALIDASI UNGGAHAN (Fase 04)
        // =========================================================================
        $fileKtp = UploadedFile::fake()->create('ktp_warga.pdf', 300, 'application/pdf');
        $fileUsaha = UploadedFile::fake()->image('foto_usaha.jpg', 600, 400);

        $submitResponse = $this->actingAs($warga)->post(route('masyarakat.pengajuan.store'), [
            'surat_template_id' => $this->template->id,
            'keperluan' => 'Keperluan pengajuan kredit usaha mikro KUR BRI.',
            'dokumen' => [$fileKtp, $fileUsaha],
        ]);

        $submitResponse->assertRedirect();
        $pengajuan = PengajuanSurat::where('user_id', $warga->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('menunggu', $pengajuan->status);
        $this->assertNotEmpty($pengajuan->nomor_pengajuan);
        $this->assertCount(2, $pengajuan->dokumen_path_json);

        // =========================================================================
        // 5. UJI KEAMANAN IDOR: Warga Lain Ditolak Mengakses Data Permohonan (Fase 07)
        // =========================================================================
        $wargaLain = User::factory()->create(['is_active' => true]);
        $wargaLain->assignRole('Masyarakat');
        CitizenProfile::create([
            'user_id' => $wargaLain->id,
            'nik' => '3201019999990002',
            'nama_lengkap' => 'Warga Lain',
        ]);

        $this->actingAs($wargaLain)
            ->get(route('masyarakat.pengajuan.show', $pengajuan->id))
            ->assertStatus(403);

        $this->actingAs($wargaLain)
            ->get(route('masyarakat.pengajuan.dokumen.download', [$pengajuan->id, 0]))
            ->assertStatus(403);

        // Warga pemilik sah dapat mengunduh dokumen pendukungnya
        $this->actingAs($warga)
            ->get(route('masyarakat.pengajuan.dokumen.download', [$pengajuan->id, 0]))
            ->assertStatus(200);

        // =========================================================================
        // 6. UJI PEMROSESAN ANTREAN OLEH PETUGAS (Fase 05)
        // =========================================================================
        // Petugas mengubah status menjadi perlu_perbaikan
        $this->actingAs($this->staff)
            ->post(route('persuratan.antrean.status', $pengajuan->id), [
                'action' => 'perlu_perbaikan',
                'catatan_petugas' => 'Foto tempat usaha agak buram.',
                'pesan_ke_pemohon' => 'Mohon perjelas foto plang toko Anda.',
            ])
            ->assertRedirect();

        $pengajuan->refresh();
        $this->assertEquals('perlu_perbaikan', $pengajuan->status);
        $this->assertEquals(1, $warga->notifications()->count());

        // Petugas mengubah status menjadi diproses
        $this->actingAs($this->staff)
            ->post(route('persuratan.antrean.status', $pengajuan->id), [
                'action' => 'diproses',
                'catatan_petugas' => 'Berkas dikonfirmasi valid, mulai pembuatan naskah surat.',
            ])
            ->assertRedirect();

        $pengajuan->refresh();
        $this->assertEquals('diproses', $pengajuan->status);

        // =========================================================================
        // 7. UJI PENERBITAN SURAT RESMI, GENERATOR PDF, & BUKU REGISTER (Fase 06)
        // =========================================================================
        $terbitResponse = $this->actingAs($this->staff)
            ->post(route('persuratan.antrean.terbitkan', $pengajuan->id), [
                'catatan_petugas' => 'Surat selesai dan disahkan Kepala Desa.',
                'pesan_ke_pemohon' => 'Surat Keterangan Usaha Anda telah terbit dan siap digunakan.',
            ]);

        $terbitResponse->assertRedirect();

        $pengajuan->refresh();
        $this->assertEquals('selesai', $pengajuan->status);
        $this->assertNotNull($pengajuan->surat_arsip_id);

        $suratArsip = SuratArsip::find($pengajuan->surat_arsip_id);
        $this->assertNotNull($suratArsip);
        $this->assertEquals('terbit', $suratArsip->status);
        $this->assertNotEmpty($suratArsip->nomor_surat);
        $this->assertEquals($penduduk->id, $suratArsip->penduduk_id);

        // Verifikasi sinkronisasi otomatis ke Buku Agenda & Ekspedisi (tidak duplikat)
        $this->assertDatabaseHas('buku_agendas', [
            'surat_arsip_id' => $suratArsip->id,
            'jenis' => 'keluar',
        ]);
        $this->assertDatabaseHas('buku_ekspedisis', [
            'surat_arsip_id' => $suratArsip->id,
        ]);
        $this->assertEquals(1, BukuAgenda::where('surat_arsip_id', $suratArsip->id)->count());
        $this->assertEquals(1, BukuEkspedisi::where('surat_arsip_id', $suratArsip->id)->count());

        // =========================================================================
        // 8. UJI UNDUH SURAT OLEH WARGA & LACAK STATUS PUBLIK (Fase 07)
        // =========================================================================
        // Warga dapat mengunduh surat resmi yang diterbitkan
        $this->actingAs($warga)
            ->get(route('masyarakat.pengajuan.surat.download', $pengajuan->id))
            ->assertStatus(200);

        // Warga lain dilarang mengunduh surat ini
        $this->actingAs($wargaLain)
            ->get(route('masyarakat.pengajuan.surat.download', $pengajuan->id))
            ->assertStatus(403);

        // Lacak status publik dengan nomor pengajuan
        $this->get('/lacak-surat?nomor=' . $pengajuan->nomor_pengajuan)
            ->assertStatus(200)
            ->assertSee($pengajuan->nomor_pengajuan)
            ->assertSee('Selesai');

        // =========================================================================
        // 9. UJI PENCADANGAN DATABASE (ADMIN BACKUP PRE-DEPLOYMENT)
        // =========================================================================
        $this->actingAs($this->admin)
            ->post(route('admin.backup.store'))
            ->assertRedirect(route('admin.backup.index'));

        $this->assertDatabaseHas('backup_records', [
            'user_id' => $this->admin->id,
            'status' => 'success',
        ]);
    }
}
