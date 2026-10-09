<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CitizenProfile;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request for Citizen (Masyarakat).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'digits:16', 'regex:/^[0-9]{16}$/', 'unique:citizen_profiles,nik'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'Nomor Induk Kependudukan (NIK) 16 digit wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 digit angka.',
            'nik.regex' => 'NIK hanya boleh berupa deretan angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem DIGIDES.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp / HP aktif wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Selalu tetapkan Role 'Masyarakat' untuk registrasi publik
            if (Role::where('name', 'Masyarakat')->exists()) {
                $user->assignRole('Masyarakat');
            }

            // Hubungkan dengan data kependudukan jika NIK ditemukan di Buku Induk Penduduk
            $penduduk = Penduduk::where('nik', $request->nik)->first();

            CitizenProfile::create([
                'user_id' => $user->id,
                'penduduk_id' => $penduduk?->id,
                'nik' => $request->nik,
                'no_kk' => $penduduk?->no_kk,
                'nama_lengkap' => $penduduk?->nama_lengkap ?? $request->name,
                'tempat_lahir' => $penduduk?->tempat_lahir,
                'tanggal_lahir' => $penduduk?->tanggal_lahir,
                'jenis_kelamin' => $penduduk?->jenis_kelamin,
                'alamat' => $penduduk?->alamat_lengkap,
                'rt' => $penduduk?->rt,
                'rw' => $penduduk?->rw,
                'dusun' => $penduduk?->dusun,
                'pekerjaan' => $penduduk?->pekerjaan,
                'agama' => $penduduk?->agama,
                'status_perkawinan' => $penduduk?->status_perkawinan,
                'status_verifikasi' => $penduduk ? 'terverifikasi' : 'menunggu_verifikasi',
                'verified_at' => $penduduk ? now() : null,
                'catatan_verifikasi' => $penduduk ? 'Otomatis terverifikasi via Buku Induk Penduduk Desa' : 'Menunggu validasi berkas oleh petugas pelayanan desa',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        $user->update([
            'last_login_at' => now(),
        ]);

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->log("Warga {$user->name} (NIK: {$request->nik}) berhasil mendaftar akun portal masyarakat.");

        return redirect()->route('masyarakat.dashboard')
            ->with('success', "Pendaftaran berhasil! Selamat datang di Portal Layanan Mandiri, {$user->name}.");
    }
}
