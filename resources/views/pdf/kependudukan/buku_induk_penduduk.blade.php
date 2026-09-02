<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Induk Kependudukan - {{ $desa->nama_desa ?? 'Desa' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm 1.2cm 1.2cm 1.2cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #111;
            margin: 0;
            line-height: 1.25;
        }
        .header-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0c3837;
            margin-bottom: 3px;
        }
        .header-sub {
            text-align: center;
            font-size: 9pt;
            margin-bottom: 12px;
            color: #475569;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 4.5px 5px;
            text-align: left;
            vertical-align: middle;
            font-size: 8pt;
        }
        table.data-table th {
            background-color: #e2f0ed;
            color: #0c3837;
            font-weight: bold;
            text-align: center;
            font-size: 7.5pt;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .footer-sign {
            margin-top: 25px;
            width: 100%;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    @include('pdf.layouts.kop_surat')

    <div class="header-title">BUKU INDUK KEPENDUDUKAN DESA</div>
    <div class="header-sub">
        @if(!empty($filterText))
            Kriteria Filter: {{ $filterText }} · 
        @endif
        Total Data: {{ number_format($data->count()) }} Jiwa · Dicetak Pada: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 105px;">NIK</th>
                <th style="width: 105px;">Nomor KK</th>
                <th>Nama Lengkap</th>
                <th style="width: 25px;">JK</th>
                <th style="width: 85px;">Tempat, Tgl Lahir</th>
                <th style="width: 30px;">Umur</th>
                <th style="width: 55px;">Agama</th>
                <th style="width: 70px;">Pendidikan</th>
                <th style="width: 75px;">Pekerjaan</th>
                <th style="width: 65px;">Status Kawin</th>
                <th style="width: 75px;">Hub Keluarga</th>
                <th>Alamat & RT/RW</th>
                <th style="width: 45px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono text-center">{{ $item->nik }}</td>
                    <td class="font-mono text-center">{{ $item->no_kk }}</td>
                    <td><strong>{{ $item->nama_lengkap }}</strong></td>
                    <td class="text-center font-bold">{{ $item->jenis_kelamin }}</td>
                    <td>
                        {{ $item->tempat_lahir }}<br>
                        <small>{{ $item->tanggal_lahir?->format('d-m-Y') }}</small>
                    </td>
                    <td class="text-center">{{ $item->umur }} th</td>
                    <td>{{ $item->agama }}</td>
                    <td>{{ $item->pendidikan_terakhir ?? '-' }}</td>
                    <td>{{ $item->pekerjaan ?? '-' }}</td>
                    <td>{{ $item->status_perkawinan_label }}</td>
                    <td>{{ $item->status_keluarga_label }}</td>
                    <td>
                        {{ $item->dusun ? 'Dusun ' . $item->dusun . ', ' : '' }}
                        RT {{ $item->rt }} / RW {{ $item->rw }}
                    </td>
                    <td class="text-center">{{ ucfirst($item->status_penduduk) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center" style="padding: 20px;">Tidak ada data penduduk yang sesuai dengan kriteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 70%;"></td>
            <td style="text-align: center; width: 30%;">
                <div>{{ $desa->nama_desa ?? 'Desa' }}, {{ now()->isoFormat('D MMMM Y') }}</div>
                <div style="font-weight: bold; margin-top: 3px;">Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}</div>
                <div style="margin-top: 45px; font-weight: bold; text-decoration: underline;">{{ $desa->nama_kades ?? 'Dr. H. Rahmat Hidayat, M.Si' }}</div>
                <div style="font-size: 7.5pt; color: #555;">NIP. {{ $desa->nip_kades ?? '-' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
