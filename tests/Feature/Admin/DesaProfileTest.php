<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
