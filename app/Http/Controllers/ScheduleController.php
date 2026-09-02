<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'tag' => 'required|string|max:50',
            'time' => 'required|string|max:50',
            'pic' => 'nullable|string|max:100',
            'date' => 'nullable|date',
        ], [
            'title.required' => 'Judul agenda / jadwal kegiatan wajib diisi.',
            'tag.required' => 'Kategori agenda wajib dipilih.',
            'time.required' => 'Waktu kegiatan wajib diisi.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['order'] = (Schedule::max('order') ?? 0) + 1;

        Schedule::create($validated);

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil ditambahkan ke Upcoming Schedule.');
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'tag' => 'required|string|max:50',
            'time' => 'required|string|max:50',
            'pic' => 'nullable|string|max:100',
            'date' => 'nullable|date',
        ], [
            'title.required' => 'Judul agenda / jadwal kegiatan wajib diisi.',
            'tag.required' => 'Kategori agenda wajib dipilih.',
            'time.required' => 'Waktu kegiatan wajib diisi.',
        ]);

        $schedule->update($validated);

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $title = $schedule->title;
        $schedule->delete();

        return redirect()->back()->with('success', "Jadwal '{$title}' berhasil dihapus.");
    }
}
