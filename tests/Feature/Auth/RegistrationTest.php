<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(UserSeeder::class);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Alamat Email');
        $response->assertSee('Nomor Telepon / WhatsApp');
        $response->assertSee('showPass');
        $response->assertSee('showPassConfirm');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Staf Baru',
            'email' => 'budistaf@desa.id',
            'phone' => '081299988877',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Staf Baru',
            'email' => 'budistaf@desa.id',
            'phone' => '081299988877',
            'is_active' => true,
        ]);

        $user = User::where('email', 'budistaf@desa.id')->first();
        $this->assertTrue($user->hasRole('Staff Desa'));
    }

    public function test_registration_validation_fails_with_duplicate_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Duplikat Admin',
            'email' => 'admin@desa.id',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_validation_fails_with_unmatched_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'User Password Mismatch',
            'email' => 'mismatch@desa.id',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}
