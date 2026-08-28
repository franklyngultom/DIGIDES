<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Superadmin (Admin Desa)
        $admin = User::updateOrCreate(
            ['email' => 'admin@desa.id'],
            [
                'name' => 'Administrator Desa Sukamaju',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Admin Desa']);

        // 2. Akun Staff Operasional (Staff Desa - Jack Grealish)
        $staff = User::updateOrCreate(
            ['email' => 'staff@desa.id'],
            [
                'name' => 'Jack Grealish',
                'password' => Hash::make('password'),
                'phone' => '081298765432',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $staff->syncRoles(['Staff Desa']);
    }
}
