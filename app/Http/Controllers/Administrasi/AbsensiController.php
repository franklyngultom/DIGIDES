<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class AbsensiController extends Controller
{
    /**
     * Show the QR scanner page.
     */
    public function scanner()
    {
        return View::make('absensi.scanner');
    }

    /**
     * Process a scanned QR token.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);
        $token = $request->input('token');
        $aparatur = Aparatur::where('qr_token', $token)
            ->where('status_aktif', true)
            ->first();
        if (! $aparatur) {
            return Response::json([
                'status' => 'error',
                'message' => 'Aparatur tidak ditemukan atau tidak aktif.',
            ], 404);
        }
        $today = Carbon::today();
        $now = Carbon::now();
        $absensi = Absensi::firstOrNew([
            'aparatur_id' => $aparatur->id,
            'tanggal' => $today->toDateString(),
        ]);
        if (! $absensi->jam_masuk) {
            $absensi->jam_masuk = $now->toTimeString();
            $batasMasuk = Carbon::parse($today->toDateString() . ' ' . $aparatur->jam_masuk_standar)
                ->addMinutes($aparatur->toleransi_terlambat_menit);
            $absensi->status_kehadiran = $now->gt($batasMasuk) ? 'terlambat' : 'hadir';
            $absensi->metode_absen = 'qr_scanner';
            $absensi->save();
            return Response::json([
                'status' => 'success',
                'type' => 'masuk',
                'aparatur' => $aparatur,
                'waktu' => $now->format('H:i'),
            ]);
        }
        if (! $absensi->jam_pulang) {
            $absensi->jam_pulang = $now->toTimeString();
            $absensi->metode_absen = $absensi->metode_absen ?? 'qr_scanner';
            $absensi->save();
            return Response::json([
                'status' => 'success',
                'type' => 'pulang',
                'aparatur' => $aparatur,
                'waktu' => $now->format('H:i'),
            ]);
        }
        return Response::json([
            'status' => 'error',
            'message' => 'Absensi hari ini sudah lengkap.',
        ], 400);
    }

    /**
     * Show manual attendance entry form.
     */
    public function manualForm()
    {
        $aparaturList = Aparatur::where('status_aktif', true)->get();
        return View::make('absensi.manual', compact('aparaturList'));
    }

    /**
     * Store manual attendance entry.
     */
    public function manualStore(Request $request)
    {
        $validated = $request->validate([
            'aparatur_id' => 'required|exists:aparatur,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status_kehadiran' => 'required|in:hadir,terlambat,izin,sakit,dinas_luar,alpa',
            'catatan' => 'nullable|string',
        ]);
        $absensi = Absensi::updateOrCreate([
            'aparatur_id' => $validated['aparatur_id'],
            'tanggal' => $validated['tanggal'],
        ], [
            'jam_masuk' => $validated['jam_masuk'] ?? null,
            'jam_pulang' => $validated['jam_pulang'] ?? null,
            'status_kehadiran' => $validated['status_kehadiran'],
            'catatan' => $validated['catatan'] ?? null,
            'metode_absen' => 'manual_override',
            'verified_by' => auth()->id(),
        ]);
        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan.');
    }

    /**
     * Export attendance recap to PDF (stub).
     */
    public function exportPdf(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $data = Absensi::when($start, fn($q) => $q->where('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->where('tanggal', '<=', $end))
            ->with('aparatur')
            ->get();
        return Response::json([
            'status' => 'stub',
            'records' => $data->count(),
        ]);
    }
}

