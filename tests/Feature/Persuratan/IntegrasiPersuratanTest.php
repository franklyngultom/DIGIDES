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
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IntegrasiPersuratanTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected User $warga;
    protected User $wargaLain;
    protected SuratTemplate $template;
    protected PengajuanSurat $pengajuan;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('private');

        $this->seed(RoleAndPermissionSeeder::class);
        DesaProfile::current();

        // 1. Staff Desa
        $this->staff = User::factory()->create([
            'name' => 'Staf Pelayanan',
            'email' => 'staf@desa.id',
            'is_active' => true,
        ]);
        $this->staff->assignRole('Staff Desa');

        // 2. Akun Warga Pemohon
        $this->warga = User::factory()->create([
            'name' => 'Budi Pemohon',
            'email' => 'budi@warga.id',
            'phone' => '081234567890',
            'is_active' => true,
        ]);
        $this->warga->assignRole('Masyarakat');

        $profile = CitizenProfile::create([
            'user_id' => $this->warga->id,
            'nik' => '3201010101900002',
            'nama_lengkap' => 'Budi Pemohon',
            'alamat' => 'RT 02 RW 03 Desa Sukamaju',
            'tempat_lahir' => 'Sukabumi',
            'tanggal_lahir' => '1990-05-15',
            'status_verifikasi' => 'terverifikasi',
        ]);

        // 3. Akun Warga Lain (untuk uji otorisasi isolasi data)
        $this->wargaLain = User::factory()->create([
            'name' => 'Warga Lain',
            'email' => 'lain@warga.id',
            'is_active' => true,
        ]);
        $this->wargaLain->assignRole('Masyarakat');

        // 4. Template Surat Keterangan Tidak Mampu
        $this->template = SuratTemplate::create([
            'kode_surat' => 'SKTM',
            'nama_surat' => 'Surat Keterangan Tidak Mampu',
            'penomoran_format' => '400/{no}/{kode}/{bulan}/{tahun}',
            'template_blade' => 'sktm',
            'is_active' => true,
            'is_online_available' => true,
        ]);

        // 5. Pengajuan Online Warga
        $this->pengajuan = PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ-2026-000888',
            'user_id' => $this->warga->id,
            'citizen_profile_id' => $profile->id,
            'surat_template_id' => $this->template->id,
            'pemohon_nama' => 'Budi Pemohon',
            'pemohon_nik' => '3201010101900002',
            'pemohon_alamat' => 'RT 02 RW 03 Desa Sukamaju',
            'pemohon_phone' => '081234567890',
            'form_data_json' => [
                'keperluan' => 'Permohonan Keringanan Biaya Rumah Sakit',
                'penghasilan' => 750000,
            ],
            'status' => 'diproses',
        ]);
    }

    public function test_staff_can_issue_official_letter_from_online_submission(): void
    {
        $response = $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan), [
            'catatan_petugas' => 'Berkas lengkap dan sah',
            'pesan_ke_pemohon' => 'Surat telah diterbitkan dan dapat diunduh.',
        ]);

        $response->assertRedirect(route('persuratan.antrean.show', $this->pengajuan));
        $response->assertSessionHas('success');

        // Pengajuan status selesai dan terhubung ke SuratArsip
        $this->pengajuan->refresh();
        $this->assertEquals('selesai', $this->pengajuan->status);
        $this->assertNotNull($this->pengajuan->surat_arsip_id);

        // SuratArsip tercatat
        $arsip = $this->pengajuan->suratArsip;
        $this->assertNotNull($arsip);
        $this->assertEquals($this->template->id, $arsip->surat_template_id);
        $this->assertEquals('terbit', $arsip->status);
        $this->assertStringContainsString('SKTM', $arsip->nomor_surat);
        $this->assertNotNull($arsip->file_pdf_path);
        Storage::disk('local')->assertExists($arsip->file_pdf_path);

        // Penduduk otomatis terhubung
        $this->assertDatabaseHas('penduduks', [
            'nik' => '3201010101900002',
            'nama_lengkap' => 'Budi Pemohon',
        ]);

        // Sinkronisasi otomatis ke Buku Ekspedisi
        $this->assertDatabaseHas('buku_ekspedisis', [
            'surat_arsip_id' => $arsip->id,
            'nomor_surat' => $arsip->nomor_surat,
        ]);

        // Sinkronisasi otomatis ke Buku Agenda Surat Keluar
        $this->assertDatabaseHas('buku_agendas', [
            'surat_arsip_id' => $arsip->id,
            'jenis' => 'keluar',
            'nomor_surat' => $arsip->nomor_surat,
        ]);
    }

    public function test_issuing_letter_is_idempotent_and_prevents_duplicate_records(): void
    {
        // Penerbitan pertama
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan));
        $this->pengajuan->refresh();
        $firstArsipId = $this->pengajuan->surat_arsip_id;

        $countArsip1 = SuratArsip::count();
        $countEkspedisi1 = BukuEkspedisi::count();
        $countAgenda1 = BukuAgenda::count();

        // Panggilan kedua (misal double submit)
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan));

        $this->assertEquals($countArsip1, SuratArsip::count(), 'SuratArsip tidak boleh terduplikasi.');
        $this->assertEquals($countEkspedisi1, BukuEkspedisi::count(), 'Buku Ekspedisi tidak boleh terduplikasi.');
        $this->assertEquals($countAgenda1, BukuAgenda::count(), 'Buku Agenda tidak boleh terduplikasi.');
        $this->assertEquals($firstArsipId, $this->pengajuan->fresh()->surat_arsip_id);
    }

    public function test_changing_status_to_selesai_automatically_issues_letter_if_not_yet_issued(): void
    {
        $response = $this->actingAs($this->staff)->post(route('persuratan.antrean.status', $this->pengajuan), [
            'action' => 'selesai',
            'catatan_petugas' => 'Selesai dan langsung terbitkan',
        ]);

        $response->assertRedirect(route('persuratan.antrean.show', $this->pengajuan));

        $this->pengajuan->refresh();
        $this->assertEquals('selesai', $this->pengajuan->status);
        $this->assertNotNull($this->pengajuan->surat_arsip_id);
        $this->assertNotNull($this->pengajuan->suratArsip);
    }

    public function test_citizen_can_download_their_own_issued_letter_pdf(): void
    {
        // Staff menerbitkan surat
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan));
        $this->pengajuan->refresh();

        // Warga pemilik mengunduh suratnya
        $response = $this->actingAs($this->warga)->get(route('masyarakat.pengajuan.surat.download', $this->pengajuan));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_other_citizen_cannot_download_different_users_letter_pdf(): void
    {
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan));
        $this->pengajuan->refresh();

        // Warga lain mencoba mengunduh
        $response = $this->actingAs($this->wargaLain)->get(route('masyarakat.pengajuan.surat.download', $this->pengajuan));
        $response->assertForbidden();
    }

    public function test_staff_can_download_letter_from_antrean_and_arsip(): void
    {
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan));
        $this->pengajuan->refresh();
        $arsip = $this->pengajuan->suratArsip;

        // Unduh dari antrean
        $respAntrean = $this->actingAs($this->staff)->get(route('persuratan.antrean.surat.download', $this->pengajuan));
        $respAntrean->assertOk();
        $respAntrean->assertHeader('content-type', 'application/pdf');

        // Unduh dari arsip
        $respArsip = $this->actingAs($this->staff)->get(route('persuratan.arsip.download', $arsip));
        $respArsip->assertOk();
        $respArsip->assertHeader('content-type', 'application/pdf');
    }

    public function test_public_lacak_surat_finds_by_nomor_surat_and_nomor_pengajuan(): void
    {
        $this->actingAs($this->staff)->post(route('persuratan.antrean.terbitkan', $this->pengajuan));
        $this->pengajuan->refresh();
        $nomorSurat = $this->pengajuan->suratArsip->nomor_surat;

        // Lacak dengan nomor surat resmi
        $responseSurat = $this->get(route('public.lacak-surat', ['nomor' => $nomorSurat]));
        $responseSurat->assertOk();
        $responseSurat->assertSee($nomorSurat);
        $responseSurat->assertSee('Budi Pemohon');

        // Lacak dengan nomor pengajuan registrasi
        $responsePengajuan = $this->get(route('public.lacak-surat', ['nomor' => $this->pengajuan->nomor_pengajuan]));
        $responsePengajuan->assertOk();
        $responsePengajuan->assertSee($this->pengajuan->nomor_pengajuan);
        $responsePengajuan->assertSee($nomorSurat);
    }

    public function test_staff_can_perform_walkin_service_and_records_to_arsip_and_ekspedisi(): void
    {
        $penduduk = Penduduk::create([
            'nik'             => '3201010101999999',
            'no_kk'           => '3201010000009999',
            'nama_lengkap'    => 'Warga Datang Langsung',
            'tempat_lahir'    => 'Sukabumi',
            'tanggal_lahir'   => '1995-01-01',
            'jenis_kelamin'   => 'L',
            'agama'           => 'Islam',
            'pekerjaan'       => 'Petani',
            'alamat_lengkap'  => 'Dusun 1 RT 01 RW 01',
            'rt'              => '001',
            'rw'              => '001',
            'status_penduduk' => 'tetap',
        ]);

        // Cek halaman create
        $respCreate = $this->actingAs($this->staff)->get(route('persuratan.create'));
        $respCreate->assertOk();
        $respCreate->assertSee('Pelayanan Surat Walk-In');

        // Kirim walk-in
        $respStore = $this->actingAs($this->staff)->post(route('persuratan.store'), [
            'template_id' => $this->template->id,
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Keperluan mendesak administrasi',
        ]);

        $respStore->assertRedirect(route('persuratan.arsip.index'));

        // Cek arsip
        $arsip = SuratArsip::where('penduduk_id', $penduduk->id)->latest()->first();
        $this->assertNotNull($arsip);
        $this->assertEquals('terbit', $arsip->status);

        // Cek ekspedisi & agenda tercatat
        $this->assertDatabaseHas('buku_ekspedisis', ['surat_arsip_id' => $arsip->id]);
        $this->assertDatabaseHas('buku_agendas', ['surat_arsip_id' => $arsip->id, 'jenis' => 'keluar']);

        // Cek halaman index arsip
        $respIndex = $this->actingAs($this->staff)->get(route('persuratan.arsip.index'));
        $respIndex->assertOk();
        $respIndex->assertSee($arsip->nomor_surat);
    }
}
