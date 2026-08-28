<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of staff and admin users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $roleFilter = $request->query('role');
        $statusFilter = $request->query('status');

        $query = User::with('roles', 'permissions')->latest();

        if ($search) {
            $query->search($search);
        }

        if ($roleFilter) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $roleFilter));
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('is_active', $statusFilter === '1');
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::all();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admin_count' => User::role('Admin Desa')->count(),
            'staff_count' => User::role('Staff Desa')->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats', 'search', 'roleFilter', 'statusFilter'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::all();
        $permissionsGrouped = $this->getGroupedPermissions();

        return view('admin.users.create', compact('roles', 'permissionsGrouped'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'avatar_path' => $avatarPath,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Assign primary role
            $user->syncRoles([$validated['role']]);

            // Assign direct permissions if custom chosen
            if (! empty($validated['permissions'])) {
                $user->syncPermissions($validated['permissions']);
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        $permissionsGrouped = $this->getGroupedPermissions();
        $userRole = $user->roles->first()?->name;
        $userDirectPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissionsGrouped', 'userRole', 'userDirectPermissions'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($request, $user) {
            $validated = $request->validated();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            if ($request->hasFile('avatar')) {
                if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                    Storage::disk('public')->delete($user->avatar_path);
                }
                $updateData['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user->update($updateData);

            // Sync role
            $user->syncRoles([$validated['role']]);

            // Sync permissions
            $user->syncPermissions($validated['permissions'] ?? []);
        });

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        activity('user_management')
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log("Status akun {$user->name} berhasil {$statusText}.");

        return redirect()->back()
            ->with('success', "Status akun {$user->name} berhasil {$statusText}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$name} telah berhasil dihapus dari sistem.");
    }

    /**
     * Group permissions logically for UI selection.
     */
    protected function getGroupedPermissions(): array
    {
        $permissions = Permission::all();

        $grouped = [
            'Profil Desa' => [],
            'Manajemen Pengguna' => [],
            'Kependudukan' => [],
            'Persuratan Walk-In' => [],
            'Kelembagaan Dinamis' => [],
            'Absensi Aparatur' => [],
            'Administrasi Umum' => [],
            'Keuangan Desa' => [],
            'Pembangunan Desa' => [],
            'Sistem & Audit' => [],
        ];

        foreach ($permissions as $perm) {
            $prefix = explode('.', $perm->name)[0];
            $group = match ($prefix) {
                'desa' => 'Profil Desa',
                'user' => 'Manajemen Pengguna',
                'kependudukan' => 'Kependudukan',
                'persuratan' => 'Persuratan Walk-In',
                'kelembagaan' => 'Kelembagaan Dinamis',
                'absensi' => 'Absensi Aparatur',
                'administrasi' => 'Administrasi Umum',
                'keuangan' => 'Keuangan Desa',
                'pembangunan' => 'Pembangunan Desa',
                'audit', 'backup' => 'Sistem & Audit',
                default => 'Lainnya'
            };

            $grouped[$group][] = $perm;
        }

        return array_filter($grouped);
    }
}
