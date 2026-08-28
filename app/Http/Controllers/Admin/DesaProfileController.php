<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DesaProfileUpdateRequest;
use App\Models\DesaProfile;
use Illuminate\Http\RedirectResponse;
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

        unset($validated['logo']);

        $desa->update($validated);

        return redirect()->route('admin.desa.index')
            ->with('success', 'Profil dan identitas desa berhasil diperbarui.');
    }
}
