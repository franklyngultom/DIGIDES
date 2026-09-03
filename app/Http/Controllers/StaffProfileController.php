<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StaffProfileController extends Controller
{
    /**
     * Update the authenticated staff member's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Hanya staf desa yang diperbolehkan mengubah foto profil lewat endpoint ini
        if (!$user->hasRole('Staff Desa')) {
            abort(403, 'Akses edit foto profil dashboard hanya diizinkan untuk Staff Desa.');
        }

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'avatar.required' => 'Silakan pilih berkas foto profil yang ingin diunggah.',
            'avatar.image' => 'Berkas yang diunggah harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus bertipe JPG, JPEG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran file foto maksimal adalah 2MB.',
        ]);

        // Hapus foto profil lama dari storage jika sebelumnya sudah pernah upload
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Simpan foto baru ke folder avatars di disk public
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar_path' => $path,
        ]);

        activity('user_management')
            ->causedBy($user)
            ->performedOn($user)
            ->log("Staff {$user->name} berhasil memperbarui foto profil.");

        return redirect()->back()->with('success', 'Foto profil Anda berhasil diperbarui!');
    }

    /**
     * Remove the authenticated staff member's avatar.
     */
    public function deleteAvatar(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('Staff Desa')) {
            abort(403, 'Akses edit foto profil dashboard hanya diizinkan untuk Staff Desa.');
        }

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->update([
            'avatar_path' => null,
        ]);

        activity('user_management')
            ->causedBy($user)
            ->performedOn($user)
            ->log("Staff {$user->name} menghapus foto profil dan kembali ke avatar default.");

        return redirect()->back()->with('info', 'Foto profil berhasil dihapus dan dikembalikan ke avatar bawaan.');
    }
}
