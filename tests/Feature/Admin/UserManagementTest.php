<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->staff = User::where('email', 'staff@desa.id')->first();
    }

    public function test_admin_can_view_user_management_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna', false);
        $response->assertSee($this->staff->name);
    }

    public function test_unauthorized_user_cannot_access_user_management(): void
    {
        // Staff without user.view permission
        $response = $this->actingAs($this->staff)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_staff_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Staff Baru Pelayanan',
            'email' => 'staffbaru@desa.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081233445566',
            'role' => 'Staff Desa',
            'permissions' => ['persuratan.create', 'persuratan.view'],
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'staffbaru@desa.id',
            'name' => 'Staff Baru Pelayanan',
        ]);

        $createdUser = User::where('email', 'staffbaru@desa.id')->first();
        $this->assertTrue($createdUser->hasRole('Staff Desa'));
        $this->assertTrue($createdUser->hasPermissionTo('persuratan.create'));
    }

    public function test_admin_can_update_user(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->staff), [
            'name' => 'Jack Grealish Updated',
            'email' => 'staff@desa.id',
            'phone' => '0899999999',
            'role' => 'Staff Desa',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $this->staff->id,
            'name' => 'Jack Grealish Updated',
            'phone' => '0899999999',
        ]);
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $this->assertTrue($this->staff->is_active);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.toggle-status', $this->staff));

        $response->assertRedirect();
        $this->assertFalse($this->staff->fresh()->is_active);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }
}
