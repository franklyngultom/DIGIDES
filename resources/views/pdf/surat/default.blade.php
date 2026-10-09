@extends('pdf.layouts.surat_master')

@section('surat_content')
<div class="text-justify" style="margin-bottom: 8px;">
    Bahwa yang bersangkutan di atas adalah benar-benar warga penduduk kami yang berdomisili di Desa {{ $desa->nama_desa ?? 'Sukamaju' }}, Kecamatan {{ $desa->kecamatan ?? 'Cikole' }}, Kabupaten {{ $desa->kabupaten ?? 'Sukabumi' }}.
</div>

<div class="text-justify" style="margin-bottom: 12px;">
    Surat keterangan ini diterbitkan berdasarkan permohonan yang bersangkutan untuk keperluan:
    <div style="margin: 6px 0 10px 15px; font-weight: bold; font-style: italic;">
        "{{ $surat->keperluan ?? $payload['keperluan'] ?? 'Keperluan Administrasi Umum' }}"
    </div>
</div>

@if(!empty($payload) && count($payload) > 0)
    @php
        $ignoredKeys = ['nik', 'nama_lengkap', 'no_kk', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat_lengkap', 'rt', 'rw', 'dusun', 'telepon', 'status_penduduk', 'keperluan', 'nomor_pengajuan', 'pemohon_nik', 'pemohon_nama', 'pemohon_alamat'];
        $customFields = array_filter($payload, fn($val, $key) => !in_array($key, $ignoredKeys) && !empty($val) && !is_array($val), ARRAY_FILTER_USE_BOTH);
    @endphp

    @if(count($customFields) > 0)
        <table class="data-table" style="margin-left: 15px; width: 95%; margin-bottom: 12px;">
            @foreach($customFields as $k => $v)
                <tr>
                    <td style="width: 28%;">{{ ucwords(str_replace('_', ' ', $k)) }}</td>
                    <td style="width: 3%;">:</td>
                    <td style="width: 69%;" class="font-bold">{{ $v }}</td>
                </tr>
            @endforeach
        </table>
    @endif
@endif

<div class="text-justify" style="margin-top: 8px;">
    Demikian surat keterangan ini kami buat dengan sebenarnya dan agar dapat dipergunakan sebagaimana mestinya.
</div>
@endsection
