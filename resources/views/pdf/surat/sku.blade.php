@extends('pdf.layouts.surat_master')

@section('surat_content')
<div class="text-justify" style="margin-bottom: 8px;">
    Bahwa nama tersebut di atas benar-benar adalah warga penduduk Desa {{ $desa->nama_desa ?? 'Sukamaju' }} dan berdasarkan data serta penelitian kami yang bersangkutan benar memiliki dan mengelola usaha sebagai berikut:
</div>

<table class="data-table" style="margin-left: 15px; width: 95%;">
    <tr>
        <td style="width: 28%;">Nama Usaha</td>
        <td style="width: 3%;">:</td>
        <td style="width: 69%;" class="font-bold uppercase">{{ $payload['nama_usaha'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>Bidang / Jenis Usaha</td>
        <td>:</td>
        <td>{{ $payload['jenis_usaha'] ?? $payload['bidang_usaha'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>Alamat / Lokasi Usaha</td>
        <td>:</td>
        <td>{{ $payload['alamat_usaha'] ?? $penduduk->alamat_lengkap }}</td>
    </tr>
    <tr>
        <td>Mulai Beroperasi Sejak</td>
        <td>:</td>
        <td>Tahun {{ $payload['tahun_mulai'] ?? $payload['sejak_tahun'] ?? now()->year }}</td>
    </tr>
    <tr>
        <td>Keperluan Surat</td>
        <td>:</td>
        <td>{{ $surat->keperluan ?? $payload['keperluan'] ?? 'Persyaratan Administrasi / Pengajuan Bantuan Modal' }}</td>
    </tr>
</table>
@endsection
