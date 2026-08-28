<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AparaturController extends Controller
{
    public function index()
    {
        $aparatur = Aparatur::with('penduduk')->paginate(20);
        return view('administrasi.aparatur_index', compact('aparatur'));
    }

    public function create()
    {
        $penduduk = Penduduk::orderBy('nama')->pluck('nama', 'id');
        return view('administrasi.aparatur_create', compact('penduduk'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penduduk_id' => 'required|exists:penduduk,id|unique:aparatur,penduduk_id',
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'required|string',
            'jam_masuk_standar' => 'required|date_format:H:i:s',
            'jam_pulang_standar' => 'required|date_format:H:i:s',
            'toleransi_terlambat_menit' => 'required|integer',
            'status_kepegawaian' => 'required|in:pns,pppk,perangkat_desa,honorer',
            'status_aktif' => 'required|boolean',
        ]);
        $validator->validate();
        Aparatur::create($validator->validated());
        return redirect()->route('administrasi.aparatur.index')->with('success', 'Data aparatur berhasil ditambahkan');
    }

    public function edit(Aparatur $aparatur)
    {
        $penduduk = Penduduk::orderBy('nama')->pluck('nama', 'id');
        return view('administrasi.aparatur_edit', compact('aparatur', 'penduduk'));
    }

    public function update(Request $request, Aparatur $aparatur)
    {
        $validator = Validator::make($request->all(), [
            'penduduk_id' => 'required|exists:penduduk,id|unique:aparatur,penduduk_id,' . $aparatur->id,
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'required|string',
            'jam_masuk_standar' => 'required|date_format:H:i:s',
            'jam_pulang_standar' => 'required|date_format:H:i:s',
            'toleransi_terlambat_menit' => 'required|integer',
            'status_kepegawaian' => 'required|in:pns,pppk,perangkat_desa,honorer',
            'status_aktif' => 'required|boolean',
        ]);
        $validator->validate();
        $aparatur->update($validator->validated());
        return redirect()->route('administrasi.aparatur.index')->with('success', 'Data aparatur berhasil diperbarui');
    }

    public function destroy(Aparatur $aparatur)
    {
        $aparatur->delete();
        return redirect()->route('administrasi.aparatur.index')->with('success', 'Data aparatur berhasil dihapus');
    }
}
