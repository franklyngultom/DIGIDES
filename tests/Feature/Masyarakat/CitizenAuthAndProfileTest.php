<?php

namespace Tests\Feature\Masyarakat;

use App\Models\CitizenProfile;
use App\Models\DesaProfile;
use App\Models\Penduduk;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CitizenAuthAndProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        DesaProfile::current();
    }

    public function test_citizen_can_register_and_gets_masyarakat_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'nik' => '3202110101900001',
            'email' => 'budi@warga.id',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/masyarakat/dashboard');

        $user = User::where('email', 'budi@warga.id')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Masyarakat'));
        $this->assertFalse($user->hasRole('Admin Desa'));
        $this->assertFalse($user->hasRole('Staff Desa'));

        $profile = $user->citizenProfile;
        $this->assertNotNull($profile);
        $this->assertEquals('3202110101900001', $profile->nik);
    }

    public function test_citizen_registration_auto_links_with_existing_penduduk_data(): void
    {
        $penduduk = Penduduk::create([
            'nik' => '3202110202950002',
            'no_kk' => '3202110202950000',
            'nama_lengkap' => 'Siti Nurhaliza',
            'tempat_lahir' => 'Sukabumi',
            'tanggal_lahir' => '1995-02-02',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'Guru',
            'status_perkawinan' => 'kawin',
            'alamat_lengkap' => 'Jl. Mawar No. 10',
            'rt' => '001',
            'rw' => '002',
            'status_penduduk' => 'tetap',
        ]);

        $response = $this->post('/register', [
            'name' => 'Siti Nurhaliza',
            'nik' => '3202110202950002',
            'email' => 'siti@warga.id',
            'phone' => '081298765432',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/masyarakat/dashboard');

        $user = User::where('email', 'siti@warga.id')->first();
        $profile = $user->citizenProfile;

        $this->assertEquals($penduduk->id, $profile->penduduk_id);
        $this->assertEquals('terverifikasi', $profile->status_verifikasi);
    }

    public function test_masyarakat_user_is_redirected_away_from_internal_dashboard(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Masyarakat');
        CitizenProfile::create([
            'user_id' => $user->id,
            'nik' => '3202110303920003',
            'nama_lengkap' => $user->name,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect('/masyarakat/dashboard');
    }

    public function test_masyarakat_user_cannot_access_admin_user_management(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Masyarakat');
        CitizenProfile::create([
            'user_id' => $user->id,
            'nik' => '3202110404930004',
            'nama_lengkap' => $user->name,
        ]);

        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_masyarakat_user_cannot_access_kependudukan_data(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Masyarakat');
        CitizenProfile::create([
            'user_id' => $user->id,
            'nik' => '3202110505940005',
            'nama_lengkap' => $user->name,
        ]);

        $response = $this->actingAs($user)->get('/kependudukan');
        $response->assertStatus(403);
    }

    public function test_masyarakat_can_view_and_update_own_profile(): void
    {
        $user = User::factory()->create(['name' => 'Ahmad Dani', 'phone' => '081233334444', 'is_active' => true]);
        $user->assignRole('Masyarakat');
        $profile = CitizenProfile::create([
            'user_id' => $user->id,
            'nik' => '3202110606910006',
            'nama_lengkap' => 'Ahmad Dani',
            'alamat' => 'Alamat Awal',
        ]);

        $responseGet = $this->actingAs($user)->get('/masyarakat/profil');
        $responseGet->assertStatus(200);
        $responseGet->assertSeeText('3202110606910006');

        $responsePut = $this->actingAs($user)->put('/masyarakat/profil', [
            'name' => 'Ahmad Dani Updated',
            'phone' => '081299998888',
            'alamat' => 'Jl. Kenanga No. 5',
            'rt' => '003',
            'rw' => '004',
            'pekerjaan' => 'Pedagang',
        ]);

        $responsePut->assertRedirect('/masyarakat/profil');

        $this->assertDatabaseHas('citizen_profiles', [
            'user_id' => $user->id,
            'alamat' => 'Jl. Kenanga No. 5',
            'rt' => '003',
            'rw' => '004',
            'pekerjaan' => 'Pedagang',
        ]);
    }

    public function test_masyarakat_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
            'is_active' => true,
        ]);
        $user->assignRole('Masyarakat');
        CitizenProfile::create([
            'user_id' => $user->id,
            'nik' => '3202110707900007',
            'nama_lengkap' => $user->name,
        ]);

        $response = $this->actingAs($user)->put('/masyarakat/profil/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect('/masyarakat/profil');
        $this->assertTrue(Hash::check('newpassword456', $user->fresh()->password));
    }
}
