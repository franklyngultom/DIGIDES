<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RetentionPruningTest extends TestCase
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

    public function test_prune_retention_command_dry_run_does_not_delete(): void
    {
        // 1. Buat notifikasi lama
        DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\StatusPengajuanNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $this->admin->id,
            'data' => json_encode(['pesan' => 'Notifikasi lama']),
            'read_at' => now()->subDays(200),
            'created_at' => now()->subDays(200),
            'updated_at' => now()->subDays(200),
        ]);

        // Jalankan dry-run
        $this->artisan('digides:prune-retention', ['--dry-run' => true])
            ->assertExitCode(0)
            ->expectsOutputToContain('Mode: [DRY-RUN]');

        // Pastikan tidak terhapus
        $this->assertEquals(1, DB::table('notifications')->count());
    }

    public function test_prune_retention_command_deletes_expired_read_notifications_and_logs(): void
    {
        // 1. Notifikasi lama yang sudah dibaca (> 180 hari) -> harus terhapus
        DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\StatusPengajuanNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $this->admin->id,
            'data' => json_encode(['pesan' => 'Notifikasi kadaluwarsa']),
            'read_at' => now()->subDays(200),
            'created_at' => now()->subDays(200),
            'updated_at' => now()->subDays(200),
        ]);

        // 2. Notifikasi belum dibaca -> TIDAK boleh terhapus
        DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\StatusPengajuanNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $this->admin->id,
            'data' => json_encode(['pesan' => 'Notifikasi belum dibaca']),
            'read_at' => null,
            'created_at' => now()->subDays(200),
            'updated_at' => now()->subDays(200),
        ]);

        // 3. Log aktivitas lama (> 730 hari) -> harus terhapus
        DB::table('activity_log')->insert([
            'log_name' => 'default',
            'description' => 'Aktivitas 3 tahun lalu',
            'created_at' => now()->subDays(800),
            'updated_at' => now()->subDays(800),
        ]);

        $this->artisan('digides:prune-retention')
            ->assertExitCode(0);

        // Hanya tersisa 1 notifikasi (yang belum dibaca)
        $this->assertEquals(1, DB::table('notifications')->count());
        $this->assertNull(DB::table('notifications')->first()->read_at);

        // Log 3 tahun lalu terhapus
        $this->assertDatabaseMissing('activity_log', [
            'description' => 'Aktivitas 3 tahun lalu',
        ]);
    }
}
