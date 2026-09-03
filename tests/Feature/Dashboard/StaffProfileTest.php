<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StaffProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
        $this->staff = User::where('email', 'staff@desa.id')->first();
    }

    public function test_staff_can_view_avatar_edit_section_on_dashboard(): void
    {
        $response = $this->actingAs($this->staff)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Staff Pelayanan Desa');
        $response->assertSee('Ubah Foto Profil Staff');
    }

    public function test_admin_does_not_see_staff_avatar_banner_on_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Ubah Foto Profil Staff');
    }

    public function test_staff_can_upload_new_profile_avatar(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('staff-photo.jpg', 400, 400);

        $response = $this->actingAs($this->staff)->post(route('staff.avatar.update'), [
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Foto profil Anda berhasil diperbarui!');

        $this->staff->refresh();
        $this->assertNotNull($this->staff->avatar_path);
        Storage::disk('public')->assertExists($this->staff->avatar_path);
    }

    public function test_staff_can_delete_custom_profile_avatar(): void
    {
        Storage::fake('public');

        $path = 'avatars/sample-avatar.jpg';
        Storage::disk('public')->put($path, 'dummy-content');

        $this->staff->update(['avatar_path' => $path]);

        $response = $this->actingAs($this->staff)->delete(route('staff.avatar.delete'));

        $response->assertRedirect();
        $response->assertSessionHas('info');

        $this->staff->refresh();
        $this->assertNull($this->staff->avatar_path);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_admin_cannot_access_staff_avatar_endpoint(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('admin-photo.jpg');

        $response = $this->actingAs($this->admin)->post(route('staff.avatar.update'), [
            'avatar' => $file,
        ]);

        $response->assertStatus(403);
    }

    public function test_avatar_upload_fails_with_invalid_file_type(): void
    {
        Storage::fake('public');
        $pdfFile = UploadedFile::fake()->create('document.pdf', 500);

        $response = $this->actingAs($this->staff)->post(route('staff.avatar.update'), [
            'avatar' => $pdfFile,
        ]);

        $response->assertSessionHasErrors('avatar');
    }
}
