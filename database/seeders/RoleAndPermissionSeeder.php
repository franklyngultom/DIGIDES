<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Matriks Permissions Lengkap DIGIDES v2
        $permissions = [
            // Profil Desa
            'desa.view',
            'desa.update',

            // Manajemen Pengguna & RBAC
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            // Kependudukan
            'kependudukan.view',
            'kependudukan.create',
            'kependudukan.edit',
            'kependudukan.delete',
            'kependudukan.verify',

            // Persuratan Walk-In & Ekspedisi
            'persuratan.view',
            'persuratan.create',
            'persuratan.print',

            // Dynamic Institution (Kelembagaan)
            'kelembagaan.view',
            'kelembagaan.manage_master',
            'kelembagaan.edit_content',

            // Absensi Aparatur
            'absensi.scan',
            'absensi.override',
            'absensi.rekap',

            // Administrasi Umum (8 Buku Register)
            'administrasi.view',
            'administrasi.manage',

            // Keuangan Desa (APBDes & Kas)
            'keuangan.view',
            'keuangan.manage',

            // Pembangunan Desa (RKP & Realisasi)
            'pembangunan.view',
            'pembangunan.manage',

            // Audit Trail & Backup System
            'audit.view',
            'backup.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Role Admin Desa (Superadmin - Semua Hak Akses)
        $adminRole = Role::firstOrCreate(['name' => 'Admin Desa', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // 3. Role Staff Desa (Operasional Pelayanan & Administrasi Terbatas)
        $staffRole = Role::firstOrCreate(['name' => 'Staff Desa', 'guard_name' => 'web']);
        $staffRole->syncPermissions([
            'desa.view',
            'kependudukan.view',
            'kependudukan.create',
            'kependudukan.edit',
            'persuratan.view',
            'persuratan.create',
            'persuratan.print',
            'kelembagaan.view',
            'kelembagaan.edit_content',
            'absensi.scan',
            'administrasi.view',
        ]);
    }
}
