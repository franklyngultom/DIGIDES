<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $template->nama_surat ?? 'Surat Keterangan' }} - {{ $surat->nomor_surat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 2cm 1.5cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            line-height: 1.35;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .title-surat {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .nomor-surat {
            font-size: 11pt;
            font-weight: normal;
            margin-bottom: 16px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        table.data-table td {
            padding: 2.5px 0;
            vertical-align: top;
            font-size: 11.5pt;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }
        .signature-table td {
            vertical-align: top;
        }
        .watermark-box {
            border: 1px dashed #777;
            padding: 6px 10px;
            font-size: 8pt;
            color: #555;
            margin-top: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Official Kop Surat -->
    @include('pdf.layouts.kop_surat')

    <!-- Header Title & Number -->
    <div class="text-center" style="margin-bottom: 12px;">
        <div class="title-surat">{{ $template->nama_surat }}</div>
        <div class="nomor-surat">Nomor: {{ $surat->nomor_surat }}</div>
    </div>

    <!-- Opening Statement -->
    <div class="text-justify" style="margin-bottom: 8px;">
        Yang bertanda tangan di bawah ini Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}, Kecamatan {{ $desa->kecamatan ?? 'Cikole' }}, Kabupaten {{ $desa->kabupaten ?? 'Sukabumi' }}, dengan ini menerangkan bahwa:
    </div>

    <!-- Applicant Identity Table -->
    <table class="data-table" style="margin-left: 15px; width: 95%;">
        <tr>
            <td style="width: 28%;">Nama Lengkap</td>
            <td style="width: 3%;">:</td>
            <td style="width: 69%;" class="font-bold uppercase">{{ $penduduk->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td class="font-bold">{{ $penduduk->nik }}</td>
        </tr>
        <tr>
            <td>No. Kartu Keluarga</td>
            <td>:</td>
            <td>{{ $penduduk->no_kk }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir->translatedFormat('d F Y') }} ({{ $penduduk->umur }} Tahun)</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $penduduk->jenis_kelamin_label }}</td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $penduduk->agama }}</td>
        </tr>
        <tr>
            <td>Status Perkawinan</td>
            <td>:</td>
            <td>{{ $penduduk->status_perkawinan_label }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ $penduduk->pekerjaan ?? 'Belum / Tidak Bekerja' }}</td>
        </tr>
        <tr>
            <td>Kewarganegaraan</td>
            <td>:</td>
            <td>{{ $penduduk->kewarganegaraan ?? 'WNI' }}</td>
        </tr>
        <tr>
            <td>Alamat Domisili</td>
            <td>:</td>
            <td>{{ $penduduk->alamat_lengkap }} RT {{ $penduduk->rt }} / RW {{ $penduduk->rw }} Dusun {{ $penduduk->dusun ?? '-' }} Desa {{ $desa->nama_desa }}</td>
        </tr>
    </table>

    <!-- Specific Custom Content -->
    <div style="margin-top: 10px; margin-bottom: 10px;">
        @yield('surat_content')
    </div>

    <!-- Closing Statement -->
    <div class="text-justify" style="margin-top: 10px; margin-bottom: 16px;">
        Demikian Surat Keterangan ini dibuat dengan sebenarnya atas dasar permohonan yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <!-- Signatures -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                @if(isset($payload['tanda_tangan_pemohon']) && $payload['tanda_tangan_pemohon'])
                <br>
                Yang Bersangkutan / Pemohon,
                <br><br><br><br><br>
                <span class="font-bold"><u>{{ $penduduk->nama_lengkap }}</u></span>
                @endif
            </td>
            <td style="width: 50%; text-align: center;">
                {{ $desa->nama_desa ?? 'Sukamaju' }}, {{ $surat->tanggal_terbit->translatedFormat('d F Y') }}<br>
                Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}
                <br><br><br><br><br>
                <span class="font-bold"><u>{{ $desa->nama_kades ?? 'H. DEDI SUPRIADI' }}</u></span>
                @if(!empty($desa->nip_kades))
                <div style="font-size: 10pt;">NIP. {{ $desa->nip_kades }}</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Digital Archive Verification Box -->
    <div class="watermark-box">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: middle;">
                    <strong>VERIFIKASI SISTEM DIGITAL RESMI</strong><br>
                    Dokumen Nomor <code>{{ $surat->nomor_surat }}</code> diterbitkan secara sah dan terekam di Buku Arsip Pelayanan & Buku Ekspedisi Desa {{ $desa->nama_desa }}.
                </td>
                <td style="text-align: right; vertical-align: middle; width: 100px;">
                    <span style="font-family: monospace; font-size: 7.5pt; color: #333;">DIGIDES v2 ARCHIVE</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
