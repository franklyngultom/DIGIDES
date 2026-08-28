@extends('pdf.layouts.surat_master')

@section('surat_content')
<div class="text-justify" style="margin-bottom: 8px;">
    Bahwa yang bersangkutan di atas adalah benar-benar warga penduduk kami yang berdomisili di Desa {{ $desa->nama_desa ?? 'Sukamaju' }} dan setelah diadakan penelitian/peninjauan lapangan ternyata yang bersangkutan tergolong dalam <strong>Keluarga Kurang Mampu / Ekonomi Lemah (Prasejahtera)</strong>.
</div>

<table class="data-table" style="margin-left: 15px; width: 95%;">
    <tr>
        <td style="width: 28%;">Nama Anak / Pemohon</td>
        <td style="width: 3%;">:</td>
        <td style="width: 69%;" class="font-bold">{{ $payload['nama_anak'] ?? $penduduk->nama_lengkap }}</td>
    </tr>
    @if(!empty($payload['nama_sekolah']))
    <tr>
        <td>Instansi / Sekolah</td>
        <td>:</td>
        <td>{{ $payload['nama_sekolah'] }}</td>
    </tr>
    @endif
    <tr>
        <td>Rata-rata Penghasilan</td>
        <td>:</td>
        <td>{{ !empty($payload['penghasilan']) ? 'Rp ' . number_format((float)$payload['penghasilan'], 0, ',', '.') . ' / bulan' : 'Kurang dari Rp 1.000.000,- / bulan' }}</td>
    </tr>
    <tr>
        <td>Keperluan Surat</td>
        <td>:</td>
        <td>{{ $surat->keperluan ?? $payload['keperluan'] ?? 'Persyaratan Pengajuan Bantuan Keringanan Biaya Pendidikan / Kesehatan' }}</td>
    </tr>
</table>
@endsection
