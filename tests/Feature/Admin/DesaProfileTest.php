<?php

namespace Tests\Feature\Admin;

use App\Models\DesaProfile;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DesaProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
    }

    public function test_admin_can_view_desa_profile(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.desa.index'));

        $response->assertStatus(200);
        $response->assertSee('Desa Sukamaju');
        $response->assertSee('Foto Lanskap Desa');
    }

    public function test_admin_can_update_desa_profile(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.desa.update'), [
            'nama_desa' => 'Desa Sukamaju Mandiri',
            'kode_desa' => '3202112001',
            'kecamatan' => 'Cikole',
            'kabupaten' => 'Sukabumi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '43113',
            'alamat_kantor' => 'Jl. Raya Sukamaju No. 99',
            'nama_kades' => 'Dr. H. Rahmat Hidayat, M.Si',
        ]);

        $response->assertRedirect(route('admin.desa.index'));
        $this->assertDatabaseHas('desa_profiles', [
            'nama_desa' => 'Desa Sukamaju Mandiri',
            'nama_kades' => 'Dr. H. Rahmat Hidayat, M.Si',
            'alamat_kantor' => 'Jl. Raya Sukamaju No. 99',
        ]);
    }

    public function test_admin_can_upload_and_delete_foto_desa_via_profile_update(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('pemandangan-desa.jpg', 1200, 800);

        $response = $this->actingAs($this->admin)->put(route('admin.desa.update'), [
            'nama_desa' => 'Desa Sukamaju',
            'kode_desa' => '3202112001',
            'kecamatan' => 'Cikole',
            'kabupaten' => 'Sukabumi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '43113',
            'alamat_kantor' => 'Jl. Raya Sukamaju No. 01',
            'nama_kades' => 'H. Rahmat Hidayat, S.IP',
            'foto_desa' => $file,
        ]);

        $response->assertRedirect(route('admin.desa.index'));

        $desa = DesaProfile::current();
        $this->assertNotNull($desa->foto_desa_path);
        $this->assertTrue($desa->has_custom_foto_desa);
        Storage::disk('public')->assertExists($desa->foto_desa_path);

        // Delete photo via hapus_foto_desa flag
        $deleteResponse = $this->actingAs($this->admin)->put(route('admin.desa.update'), [
            'nama_desa' => 'Desa Sukamaju',
            'kode_desa' => '3202112001',
            'kecamatan' => 'Cikole',
            'kabupaten' => 'Sukabumi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '43113',
            'alamat_kantor' => 'Jl. Raya Sukamaju No. 01',
            'nama_kades' => 'H. Rahmat Hidayat, S.IP',
            'hapus_foto_desa' => '1',
        ]);

        $deleteResponse->assertRedirect(route('admin.desa.index'));
        $desa->refresh();
        $this->assertNull($desa->foto_desa_path);
        $this->assertFalse($desa->has_custom_foto_desa);
    }

    public function test_admin_can_update_foto_desa_via_quick_endpoint(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('scenic-quick.png', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.desa.foto.update'), [
            'foto_desa' => $file,
        ]);

        $response->assertSessionHas('success');

        $desa = DesaProfile::current();
        $this->assertNotNull($desa->foto_desa_path);
        Storage::disk('public')->assertExists($desa->foto_desa_path);
    }

    public function test_admin_can_delete_foto_desa_via_quick_endpoint(): void
    {
        Storage::fake('public');

        $desa = DesaProfile::current();
        $desa->update(['foto_desa_path' => 'desa/test-scenic.jpg']);
        Storage::disk('public')->put('desa/test-scenic.jpg', 'content');

        $response = $this->actingAs($this->admin)->delete(route('admin.desa.foto.delete'));

        $response->assertSessionHas('success');

        $desa->refresh();
        $this->assertNull($desa->foto_desa_path);
        Storage::disk('public')->assertMissing('desa/test-scenic.jpg');
    }
}
