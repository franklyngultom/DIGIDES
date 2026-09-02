<?php

namespace Tests\Feature\Dashboard;

use App\Models\Schedule;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->user = User::factory()->create(['email' => 'admin@test.id']);
        $this->user->assignRole('Admin Desa');
    }

    public function test_user_can_view_dashboard_with_schedules(): void
    {
        Schedule::create([
            'title' => 'Rapat Koordinasi Mingguan',
            'tag' => 'Administrasi',
            'time' => '10:00 WIB',
            'pic' => 'Sekretaris Desa',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Upcoming Schedule');
        $response->assertSee('Rapat Koordinasi Mingguan');
    }

    public function test_user_can_create_new_schedule(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('schedules.store'), [
                'title' => 'Penyaluran BLT Dana Desa',
                'tag' => 'Keuangan',
                'time' => '08:30 WIB',
                'pic' => 'Kaur Keuangan',
                'description' => 'Penyaluran tahap 1 untuk 50 KPM',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('schedules', [
            'title' => 'Penyaluran BLT Dana Desa',
            'tag' => 'Keuangan',
            'pic' => 'Kaur Keuangan',
        ]);
    }

    public function test_user_can_update_schedule(): void
    {
        $schedule = Schedule::create([
            'title' => 'Jadwal Lama',
            'tag' => 'Persuratan',
            'time' => '09:00 WIB',
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('schedules.update', $schedule), [
                'title' => 'Jadwal Baru Terupdate',
                'tag' => 'Kependudukan',
                'time' => '13:00 WIB',
                'pic' => 'Staff Pelayanan',
                'description' => 'Update deskripsi baru',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'title' => 'Jadwal Baru Terupdate',
            'tag' => 'Kependudukan',
        ]);
    }

    public function test_user_can_delete_schedule(): void
    {
        $schedule = Schedule::create([
            'title' => 'Jadwal yang akan dihapus',
            'tag' => 'Umum',
            'time' => '16:00 WIB',
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('schedules.destroy', $schedule));

        $response->assertRedirect();
        $this->assertDatabaseMissing('schedules', [
            'id' => $schedule->id,
        ]);
    }
}
