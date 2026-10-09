<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\CitizenProfile;
use App\Models\DesaProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CitizenProfileController extends Controller
{
    /**
     * Display citizen's personal profile.
     */
    public function show(): View
    {
        $user = Auth::user();
        $desa = DesaProfile::current();
        $profile = $user->citizenProfile;

        return view('masyarakat.profile', compact('user', 'desa', 'profile'));
    }

    /**
     * Update citizen profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->citizenProfile;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'no_kk' => ['nullable', 'string', 'digits:16', 'regex:/^[0-9]{16}$/'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'agama' => ['nullable', 'string', 'max:30'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'status_perkawinan' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'dusun' => ['nullable', 'string', 'max:100'],
            'foto_ktp' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'], // Max 3MB
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'no_kk.digits' => 'Nomor Kartu Keluarga (KK) harus 16 digit.',
            'foto_ktp.max' => 'Ukuran foto KTP maksimal 3MB.',
            'foto_ktp.image' => 'File foto KTP harus berupa gambar valid (JPG, PNG, WebP).',
        ]);

        // Update User Model
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        // Handle KTP Upload if provided
        $ktpPath = $profile?->foto_ktp_path;
        if ($request->hasFile('foto_ktp')) {
            if ($ktpPath && Storage::disk('public')->exists($ktpPath)) {
                Storage::disk('public')->delete($ktpPath);
            }
            $ktpPath = $request->file('foto_ktp')->store('citizen/ktp', 'public');
        }

        // Update or Create Citizen Profile
        if ($profile) {
            $profile->update([
                'nama_lengkap' => $request->name,
                'no_kk' => $request->no_kk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'dusun' => $request->dusun,
                'foto_ktp_path' => $ktpPath,
            ]);
        }

        activity('citizen_profile')
            ->causedBy($user)
            ->performedOn($user)
            ->log("Warga {$user->name} memperbarui data profil akun.");

        return redirect()->route('masyarakat.profil')
            ->with('success', 'Data profil Anda berhasil disimpan dan diperbarui.');
    }

    /**
     * Update citizen password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->log("Warga {$user->name} mengubah kata sandi akun.");

        return redirect()->route('masyarakat.profil')
            ->with('success', 'Kata sandi akun Anda berhasil diperbarui.');
    }
}
