<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\CitizenProfile;
use App\Models\DesaProfile;
use App\Models\PengajuanSurat;
use App\Models\SuratTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CitizenPengajuanController extends Controller
{
    /**
     * List all submissions belonging to the authenticated citizen.
     */
    public function index(): View
    {
        $user   = Auth::user();
        $desa   = DesaProfile::current();

        $pengajuan = PengajuanSurat::with(['suratTemplate'])
            ->byUser($user->id)
            ->latest()
            ->paginate(10);

        return view('masyarakat.pengajuan.index', compact('user', 'desa', 'pengajuan'));
    }

    /**
     * Show the submission form for a given letter template.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user    = Auth::user();
        $desa    = DesaProfile::current();
        $profile = $user->citizenProfile;

        // Citizen must complete their profile first
        if (! $profile || ! $profile->nik) {
            return redirect()->route('masyarakat.profil')
                ->with('warning', 'Lengkapi data profil dan NIK Anda terlebih dahulu sebelum mengajukan surat.');
        }

        // Load chosen template
        $templateId = $request->query('template');
        $template   = $templateId
            ? SuratTemplate::where('is_active', true)->where('is_online_available', true)->find($templateId)
            : null;

        // All available templates for the selection dropdown
        $templates = SuratTemplate::where('is_active', true)->where('is_online_available', true)->orderBy('nama_surat')->get();

        return view('masyarakat.pengajuan.create', compact('user', 'desa', 'profile', 'template', 'templates'));
    }

    /**
     * Store a new submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $user    = Auth::user();
        $profile = $user->citizenProfile;

        // Guard: Profile must exist
        if (! $profile || ! $profile->nik) {
            return redirect()->route('masyarakat.profil')
                ->with('warning', 'Lengkapi profil dan NIK Anda terlebih dahulu.');
        }

        $request->validate([
            'surat_template_id'  => ['required', 'exists:surat_templates,id'],
            'keperluan'          => ['required', 'string', 'max:500'],
            'dokumen.*'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'surat_template_id.required' => 'Pilih jenis surat yang ingin diajukan.',
            'keperluan.required'         => 'Tuliskan keperluan pengajuan surat ini.',
            'dokumen.*.max'              => 'Ukuran setiap dokumen maksimal 5 MB.',
            'dokumen.*.mimes'            => 'Format dokumen yang diterima: PDF, JPG, PNG.',
        ]);

        $template = SuratTemplate::where('is_active', true)
            ->where('is_online_available', true)
            ->findOrFail($request->surat_template_id);

        // Build dynamic form data
        $formData = ['keperluan' => $request->keperluan];
        if ($template->schema_fields_json) {
            foreach ($template->schema_fields_json as $field) {
                $key = $field['name'] ?? null;
                if ($key && $request->has("form_{$key}")) {
                    $formData[$key] = $request->input("form_{$key}");
                }
            }
        }

        // Store uploaded supporting documents
        $dokumenPaths = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $path           = $file->store("pengajuan/{$user->id}", 'private');
                $dokumenPaths[] = [
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime'          => $file->getMimeType(),
                ];
            }
        }

        $pengajuan = PengajuanSurat::create([
            'user_id'           => $user->id,
            'citizen_profile_id'=> $profile->id,
            'surat_template_id' => $template->id,
            'pemohon_nama'      => $profile->nama_lengkap,
            'pemohon_nik'       => $profile->nik,
            'pemohon_no_kk'     => $profile->no_kk,
            'pemohon_alamat'    => $profile->full_address ?? $profile->alamat,
            'pemohon_phone'     => $user->phone,
            'form_data_json'    => $formData,
            'dokumen_path_json' => $dokumenPaths,
            'status'            => 'menunggu',
        ]);

        // Initial log entry
        $pengajuan->logs()->create([
            'user_id'        => $user->id,
            'status_sebelum' => null,
            'status_sesudah' => 'menunggu',
            'catatan'        => 'Pengajuan baru dikirimkan oleh warga.',
        ]);

        activity('pengajuan_surat')
            ->causedBy($user)
            ->performedOn($pengajuan)
            ->log("Warga {$user->name} mengajukan permohonan {$template->nama_surat} ({$pengajuan->nomor_pengajuan}).");

        return redirect()->route('masyarakat.pengajuan.show', $pengajuan)
            ->with('success', "Pengajuan Anda ({$pengajuan->nomor_pengajuan}) telah berhasil dikirim dan sedang menunggu review petugas desa.");
    }

    /**
     * Show the detail of a single submission (only to the owner).
     */
    public function show(PengajuanSurat $pengajuan): View|RedirectResponse
    {
        $user = Auth::user();

        // Authorization: citizen may only see their own
        if (! $pengajuan->isOwnedBy($user)) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $desa = DesaProfile::current();
        $pengajuan->load(['suratTemplate', 'logs.user', 'diprosesByUser', 'suratArsip']);

        return view('masyarakat.pengajuan.show', compact('user', 'desa', 'pengajuan'));
    }

    /**
     * Download a private supporting document (only to owner or officer).
     */
    public function downloadDokumen(PengajuanSurat $pengajuan, int $index)
    {
        $user = Auth::user();

        if (! $pengajuan->isOwnedBy($user) && ! $user->hasAnyPermission(['persuratan.view'])) {
            abort(403);
        }

        $dokumen = $pengajuan->dokumen_path_json[$index] ?? null;
        if (! $dokumen || ! Storage::disk('private')->exists($dokumen['path'])) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('private')->download($dokumen['path'], $dokumen['original_name']);
    }
}
