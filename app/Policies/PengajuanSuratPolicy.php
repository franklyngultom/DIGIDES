<?php

namespace App\Policies;

use App\Models\PengajuanSurat;
use App\Models\User;

class PengajuanSuratPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Admin Desa', 'Staff Desa']) || $user->can('persuratan.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PengajuanSurat $pengajuan): bool
    {
        // Petugas berwenang
        if ($user->hasRole(['Admin Desa', 'Staff Desa']) || $user->can('persuratan.view')) {
            return true;
        }

        // Pemilik pengajuan (warga)
        return $pengajuan->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model status (Petugas only).
     */
    public function update(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->hasRole(['Admin Desa', 'Staff Desa']) || $user->can('persuratan.view');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PengajuanSurat $pengajuan): bool
    {
        return $user->hasRole('Admin Desa');
    }
}
