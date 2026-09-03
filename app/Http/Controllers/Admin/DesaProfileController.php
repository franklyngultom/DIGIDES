<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DesaProfileUpdateRequest;
use App\Models\DesaProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DesaProfileController extends Controller
{
    /**
     * Display the village profile settings view.
     */
    public function index(): View
    {
        $desa = DesaProfile::current();

        return view('admin.desa.index', compact('desa'));
    }

    /**
     * Update the village profile.
     */
    public function update(DesaProfileUpdateRequest $request): RedirectResponse
    {
        $desa = DesaProfile::current();
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            if ($desa->logo_path && Storage::disk('public')->exists($desa->logo_path)) {
                Storage::disk('public')->delete($desa->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('desa', 'public');
        }

        // Handle delete village landscape photo
        if ($request->boolean('hapus_foto_desa')) {
            if ($desa->foto_desa_path && Storage::disk('public')->exists($desa->foto_desa_path)) {
                Storage::disk('public')->delete($desa->foto_desa_path);
            }
            $validated['foto_desa_path'] = null;
        } elseif ($request->hasFile('foto_desa')) {
            if ($desa->foto_desa_path && Storage::disk('public')->exists($desa->foto_desa_path)) {
                Storage::disk('public')->delete($desa->foto_desa_path);
            }
            $validated['foto_desa_path'] = $request->file('foto_desa')->store('desa', 'public');
        }

        unset($validated['logo'], $validated['foto_desa'], $validated['hapus_foto_desa']);

        $desa->update($validated);

        return redirect()->route('admin.desa.index')
            ->with('success', 'Profil dan identitas desa berhasil diperbarui.');
    }

    /**
     * Upload or update village landscape photo directly.
     */
    public function updateFoto(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'foto_desa' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ]);

        $desa = DesaProfile::current();

        if ($desa->foto_desa_path && Storage::disk('public')->exists($desa->foto_desa_path)) {
            Storage::disk('public')->delete($desa->foto_desa_path);
        }

        $path = $request->file('foto_desa')->store('desa', 'public');
        $desa->update(['foto_desa_path' => $path]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto desa berhasil diperbarui.',
                'image_url' => $desa->fresh()->foto_desa_url,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Foto lanskap desa berhasil diperbarui.');
    }

    /**
     * Delete custom village landscape photo and revert to default.
     */
    public function deleteFoto(Request $request): JsonResponse|RedirectResponse
    {
        $desa = DesaProfile::current();

        if ($desa->foto_desa_path && Storage::disk('public')->exists($desa->foto_desa_path)) {
            Storage::disk('public')->delete($desa->foto_desa_path);
        }

        $desa->update(['foto_desa_path' => null]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto desa berhasil dihapus dan dikembalikan ke foto bawaan.',
                'image_url' => $desa->fresh()->foto_desa_url,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Foto lanskap desa berhasil dihapus dan dikembalikan ke bawaan sistem.');
    }
}
