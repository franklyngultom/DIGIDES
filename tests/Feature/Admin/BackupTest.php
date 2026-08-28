<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackupTest extends TestCase
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

    public function test_admin_can_view_backup_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.backup.index'));

        $response->assertStatus(200);
        $response->assertSee('Cadangan Basis Data', false);
    }

    public function test_admin_can_trigger_database_backup(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.backup.store'));

        $response->assertRedirect(route('admin.backup.index'));
        $this->assertDatabaseHas('backup_records', [
            'user_id' => $this->admin->id,
            'status' => 'success',
        ]);
    }
}
