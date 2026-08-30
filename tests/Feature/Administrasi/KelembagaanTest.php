<?php

namespace Tests\Feature\Administrasi;

use App\Models\Institution;
use App\Models\InstitutionActivity;
use App\Models\InstitutionAgenda;
use App\Models\InstitutionDecision;
use App\Models\InstitutionMember;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\InstitutionSeeder;
use Database\Seeders\PendudukSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelembagaanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Institution $bpd;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(PendudukSeeder::class);
        $this->seed(InstitutionSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->staff = User::where('email', 'staff@desa.id')->first();
        $this->bpd = Institution::where('slug', 'bpd')->first();
    }

    public function test_admin_can_manage_institution_member(): void
    {
        // 1. Create Member
        $response = $this->actingAs($this->admin)->post(route('administrasi.kelembagaan.members.store', ['institution' => $this->bpd->slug]), [
            'nama_lengkap' => 'Ahmad Junaedi, S.Sos',
            'jabatan' => 'Anggota Keterwakilan Dusun 3',
            'periode_mulai' => 2024,
            'periode_selesai' => 2030,
            'status_aktif' => 1,
        ]);

        $response->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->bpd->slug, 'tab' => 'anggota']));
        $this->assertDatabaseHas('institution_members', [
            'institution_id' => $this->bpd->id,
            'nama_lengkap' => 'Ahmad Junaedi, S.Sos',
            'jabatan' => 'Anggota Keterwakilan Dusun 3',
        ]);

        $member = InstitutionMember::where('nama_lengkap', 'Ahmad Junaedi, S.Sos')->first();

        // 2. Update Member
        $response = $this->actingAs($this->admin)->put(route('administrasi.kelembagaan.members.update', ['institution' => $this->bpd->slug, 'member' => $member->id]), [
            'nama_lengkap' => 'Ahmad Junaedi, S.Sos (Revisi)',
            'jabatan' => 'Wakil Ketua Bidang Pemerintahan',
            'periode_mulai' => 2024,
            'periode_selesai' => 2030,
            'status_aktif' => 1,
        ]);

        $response->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->bpd->slug, 'tab' => 'anggota']));
        $this->assertDatabaseHas('institution_members', [
            'id' => $member->id,
            'nama_lengkap' => 'Ahmad Junaedi, S.Sos (Revisi)',
        ]);

        // 3. Delete Member
        $response = $this->actingAs($this->admin)->delete(route('administrasi.kelembagaan.members.destroy', ['institution' => $this->bpd->slug, 'member' => $member->id]));
        $response->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->bpd->slug, 'tab' => 'anggota']));
        $this->assertDatabaseMissing('institution_members', ['id' => $member->id]);
    }

    public function test_admin_can_manage_institution_decision(): void
    {
        // 1. Create Decision
        $response = $this->actingAs($this->admin)->post(route('administrasi.kelembagaan.decisions.store', ['institution' => $this->bpd->slug]), [
            'nomor_keputusan' => '140/99/BPD/2026',
            'tanggal_keputusan' => '2026-02-28',
            'tentang' => 'Persetujuan Kerjasama Antar Desa',
            'uraian_singkat' => 'Menyetujui kerjasama pengolahan sampah',
            'tahun' => 2026,
        ]);

        $response->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->bpd->slug, 'tab' => 'keputusan']));
        $this->assertDatabaseHas('institution_decisions', [
            'nomor_keputusan' => '140/99/BPD/2026',
        ]);

        $decision = InstitutionDecision::where('nomor_keputusan', '140/99/BPD/2026')->first();

        // 2. Delete Decision
        $response = $this->actingAs($this->admin)->delete(route('administrasi.kelembagaan.decisions.destroy', ['institution' => $this->bpd->slug, 'decision' => $decision->id]));
        $this->assertDatabaseMissing('institution_decisions', ['id' => $decision->id]);
    }

    public function test_admin_can_manage_institution_activity_and_agenda(): void
    {
        // 1. Create Activity
        $response = $this->actingAs($this->admin)->post(route('administrasi.kelembagaan.activities.store', ['institution' => $this->bpd->slug]), [
            'nama_kegiatan' => 'Kunjungan Kerja BPD ke Dusun Cikole',
            'tanggal_kegiatan' => '2026-03-01',
            'lokasi' => 'Balai RW 01',
            'penanggung_jawab' => 'Ketua BPD',
            'anggaran' => 500000,
            'tahun' => 2026,
        ]);
        $response->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->bpd->slug, 'tab' => 'kegiatan']));
        $this->assertDatabaseHas('institution_activities', [
            'nama_kegiatan' => 'Kunjungan Kerja BPD ke Dusun Cikole',
        ]);

        // 2. Create Agenda
        $response = $this->actingAs($this->admin)->post(route('administrasi.kelembagaan.agendas.store', ['institution' => $this->bpd->slug]), [
            'nama_agenda' => 'Rapat Dengar Pendapat Warga',
            'tanggal_agenda' => '2026-03-10',
            'waktu' => '10:00 WIB',
            'tempat' => 'Aula Balai Desa',
            'status' => 'rencana',
            'tahun' => 2026,
        ]);
        $response->assertRedirect(route('administrasi.kelembagaan.show', ['institution' => $this->bpd->slug, 'tab' => 'agenda']));
        $this->assertDatabaseHas('institution_agendas', [
            'nama_agenda' => 'Rapat Dengar Pendapat Warga',
        ]);
    }
}
