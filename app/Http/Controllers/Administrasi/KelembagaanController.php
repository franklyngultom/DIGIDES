<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionActivity;
use App\Models\InstitutionAgenda;
use App\Models\InstitutionDecision;
use App\Models\InstitutionMember;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelembagaanController extends Controller
{
    /**
     * Display the workspace for a specific institution.
     */
    public function show(Request $request, Institution $institution)
    {
        $activeTab = $request->query('tab', 'anggota');
        $currentYear = (int) ($request->query('tahun', date('Y')));

        $institution->loadCount([
            'members',
            'activeMembers',
            'decisions',
            'activities',
            'agendas'
        ]);

        $members = $institution->members()
            ->with('penduduk')
            ->orderBy('status_aktif', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $decisions = $institution->decisions()
            ->when($request->query('tahun'), fn($q, $y) => $q->where('tahun', $y))
            ->orderBy('tanggal_keputusan', 'desc')
            ->get();

        $activities = $institution->activities()
            ->when($request->query('tahun'), fn($q, $y) => $q->where('tahun', $y))
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        $agendas = $institution->agendas()
            ->when($request->query('tahun'), fn($q, $y) => $q->where('tahun', $y))
            ->orderBy('tanggal_agenda', 'desc')
            ->get();

        $allInstitutions = Institution::orderBy('urutan', 'asc')->get();

        return view('administrasi.kelembagaan.show', compact(
            'institution',
            'allInstitutions',
            'activeTab',
            'currentYear',
            'members',
            'decisions',
            'activities',
            'agendas'
        ));
    }

    // ==========================================
    // 1. ANGGOTA CRUD
    // ==========================================
    public function createMember(Institution $institution)
    {
        $penduduks = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('administrasi.kelembagaan.members.form', compact('institution', 'penduduks'));
    }

    public function storeMember(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'penduduk_id' => 'nullable|exists:penduduks,id',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'jabatan' => 'required|string|max:100',
            'nomor_sk_pengangkatan' => 'nullable|string|max:100',
            'tanggal_sk' => 'nullable|date',
            'periode_mulai' => 'nullable|integer',
            'periode_selesai' => 'nullable|integer',
            'kontak' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['institution_id'] = $institution->id;
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        InstitutionMember::create($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota'])
            ->with('success', "Anggota {$institution->singkatan} berhasil ditambahkan.");
    }

    public function editMember(Institution $institution, InstitutionMember $member)
    {
        $penduduks = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('administrasi.kelembagaan.members.form', compact('institution', 'member', 'penduduks'));
    }

    public function updateMember(Request $request, Institution $institution, InstitutionMember $member)
    {
        $validated = $request->validate([
            'penduduk_id' => 'nullable|exists:penduduks,id',
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'jabatan' => 'required|string|max:100',
            'nomor_sk_pengangkatan' => 'nullable|string|max:100',
            'tanggal_sk' => 'nullable|date',
            'periode_mulai' => 'nullable|integer',
            'periode_selesai' => 'nullable|integer',
            'kontak' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif', true);
        $member->update($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota'])
            ->with('success', "Data anggota {$institution->singkatan} berhasil diperbarui.");
    }

    public function destroyMember(Institution $institution, InstitutionMember $member)
    {
        $member->delete();
        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'anggota'])
            ->with('success', "Anggota {$institution->singkatan} berhasil dihapus.");
    }

    // ==========================================
    // 2. KEPUTUSAN CRUD
    // ==========================================
    public function createDecision(Institution $institution)
    {
        return view('administrasi.kelembagaan.decisions.form', compact('institution'));
    }

    public function storeDecision(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'nomor_keputusan' => 'required|string|max:100',
            'tanggal_keputusan' => 'required|date',
            'tentang' => 'required|string|max:255',
            'uraian_singkat' => 'nullable|string',
            'tahun' => 'required|integer',
        ]);

        $validated['institution_id'] = $institution->id;

        InstitutionDecision::create($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan'])
            ->with('success', "Keputusan {$institution->singkatan} berhasil dicatat.");
    }

    public function editDecision(Institution $institution, InstitutionDecision $decision)
    {
        return view('administrasi.kelembagaan.decisions.form', compact('institution', 'decision'));
    }

    public function updateDecision(Request $request, Institution $institution, InstitutionDecision $decision)
    {
        $validated = $request->validate([
            'nomor_keputusan' => 'required|string|max:100',
            'tanggal_keputusan' => 'required|date',
            'tentang' => 'required|string|max:255',
            'uraian_singkat' => 'nullable|string',
            'tahun' => 'required|integer',
        ]);

        $decision->update($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan'])
            ->with('success', "Keputusan {$institution->singkatan} berhasil diperbarui.");
    }

    public function destroyDecision(Institution $institution, InstitutionDecision $decision)
    {
        $decision->delete();
        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'keputusan'])
            ->with('success', "Keputusan {$institution->singkatan} berhasil dihapus.");
    }

    // ==========================================
    // 3. KEGIATAN CRUD
    // ==========================================
    public function createActivity(Institution $institution)
    {
        return view('administrasi.kelembagaan.activities.form', compact('institution'));
    }

    public function storeActivity(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:100',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:100',
            'output_hasil' => 'nullable|string',
            'tahun' => 'required|integer',
        ]);

        $validated['institution_id'] = $institution->id;
        $validated['anggaran'] = $validated['anggaran'] ?? 0;

        InstitutionActivity::create($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan'])
            ->with('success', "Kegiatan {$institution->singkatan} berhasil dicatat.");
    }

    public function editActivity(Institution $institution, InstitutionActivity $activity)
    {
        return view('administrasi.kelembagaan.activities.form', compact('institution', 'activity'));
    }

    public function updateActivity(Request $request, Institution $institution, InstitutionActivity $activity)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:100',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:100',
            'output_hasil' => 'nullable|string',
            'tahun' => 'required|integer',
        ]);

        $validated['anggaran'] = $validated['anggaran'] ?? 0;
        $activity->update($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan'])
            ->with('success', "Kegiatan {$institution->singkatan} berhasil diperbarui.");
    }

    public function destroyActivity(Institution $institution, InstitutionActivity $activity)
    {
        $activity->delete();
        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'kegiatan'])
            ->with('success', "Kegiatan {$institution->singkatan} berhasil dihapus.");
    }

    // ==========================================
    // 4. AGENDA CRUD
    // ==========================================
    public function createAgenda(Institution $institution)
    {
        return view('administrasi.kelembagaan.agendas.form', compact('institution'));
    }

    public function storeAgenda(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'tanggal_agenda' => 'required|date',
            'waktu' => 'nullable|string|max:20',
            'nama_agenda' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'peserta' => 'nullable|string|max:255',
            'pembahasan' => 'nullable|string',
            'status' => 'required|in:rencana,berlangsung,selesai,dibatalkan',
            'tahun' => 'required|integer',
        ]);

        $validated['institution_id'] = $institution->id;

        InstitutionAgenda::create($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda'])
            ->with('success', "Agenda {$institution->singkatan} berhasil dijadwalkan.");
    }

    public function editAgenda(Institution $institution, InstitutionAgenda $agenda)
    {
        return view('administrasi.kelembagaan.agendas.form', compact('institution', 'agenda'));
    }

    public function updateAgenda(Request $request, Institution $institution, InstitutionAgenda $agenda)
    {
        $validated = $request->validate([
            'tanggal_agenda' => 'required|date',
            'waktu' => 'nullable|string|max:20',
            'nama_agenda' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'peserta' => 'nullable|string|max:255',
            'pembahasan' => 'nullable|string',
            'status' => 'required|in:rencana,berlangsung,selesai,dibatalkan',
            'tahun' => 'required|integer',
        ]);

        $agenda->update($validated);

        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda'])
            ->with('success', "Agenda {$institution->singkatan} berhasil diperbarui.");
    }

    public function destroyAgenda(Institution $institution, InstitutionAgenda $agenda)
    {
        $agenda->delete();
        return redirect()->route('administrasi.kelembagaan.show', ['institution' => $institution->slug, 'tab' => 'agenda'])
            ->with('success', "Agenda {$institution->singkatan} berhasil dihapus.");
    }
}
