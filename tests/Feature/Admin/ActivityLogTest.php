<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
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

    public function test_admin_can_view_activity_logs(): void
    {
        activity('system_test')
            ->causedBy($this->admin)
            ->log('Melakukan uji coba pencatatan log');

        $response = $this->actingAs($this->admin)->get(route('admin.audit.index'));

        $response->assertStatus(200);
        $response->assertSee('Melakukan uji coba pencatatan log');
    }

    public function test_user_activity_is_logged_on_login(): void
    {
        $this->post('/login', [
            'email' => 'admin@desa.id',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'auth',
            'causer_id' => $this->admin->id,
        ]);
    }
}
