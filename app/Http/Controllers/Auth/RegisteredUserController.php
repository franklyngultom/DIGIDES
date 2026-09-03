<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

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
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar dalam sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal berisi :min karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Berikan role default Staff Desa
        if (\Spatie\Permission\Models\Role::where('name', 'Staff Desa')->exists()) {
            $user->assignRole('Staff Desa');
        }

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        $user->update([
            'last_login_at' => now(),
        ]);

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->log("Pengguna baru {$user->name} berhasil mendaftarkan akun dan masuk.");

        return redirect()->route('dashboard')
            ->with('success', "Pendaftaran berhasil! Selamat datang di Sistem DIGIDES, {$user->name}.");
    }
}
