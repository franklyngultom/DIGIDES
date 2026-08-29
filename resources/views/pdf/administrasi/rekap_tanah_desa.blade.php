<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Tanah Kas Desa & Tanah di Desa</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 10px 15px;
        }
        .header-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .header-sub {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
            color: #444;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #e2f0ed;
            color: #0c3837;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer-sign {
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>
<body>
    @include('pdf.layouts.kop_surat')

    <div class="header-title">BUKU TANAH KAS DESA & TANAH DI DESA</div>
    <div class="header-sub">Kategori: {{ $jenis ? ucwords(str_replace('_', ' ', $jenis)) : 'Semua Kategori Tanah' }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 90px;">Jenis Tanah</th>
                <th style="width: 100px;">No. Sertifikat / Letter C</th>
                <th>Nama Pemilik Asal</th>
                <th style="width: 70px;">Luas (m²)</th>
                <th style="width: 60px;">Kelas Tanah</th>
                <th style="width: 90px;">Lokasi / Blok</th>
                <th>Peruntukan Saat Ini</th>
                <th>Tanda Batas / Patok</th>
            </tr>
            <tr style="background-color: #f7faf9; font-size: 8px; text-align: center;">
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLuas = 0; @endphp
            @forelse($data as $index => $item)
                @php $totalLuas += $item->luas_m2; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"><strong>{{ ucwords(str_replace('_', ' ', $item->jenis_tanah)) }}</strong></td>
                    <td class="text-center">{{ $item->nomor_sertifikat_letter_c }}</td>
                    <td>{{ $item->nama_pemilik_asal }}</td>
                    <td class="text-right">{{ number_format($item->luas_m2, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $item->kelas_tanah ?? '-' }}</td>
                    <td>{{ $item->lokasi_blok }}</td>
                    <td>{{ $item->peruntukan_saat_ini }}</td>
                    <td>{{ $item->patok_tanda_batas ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px;">Belum ada data register tanah yang tercatat.</td>
                </tr>
            @endforelse
            @if(count($data) > 0)
                <tr style="font-weight: bold; background-color: #f7faf9;">
                    <td colspan="4" class="text-center">TOTAL LUAS TANAH</td>
                    <td class="text-right">{{ number_format($totalLuas, 2, ',', '.') }} m²</td>
                    <td colspan="4"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 70%;"></td>
            <td style="text-align: center;">
                <div>{{ $desa->nama_desa ?? 'Desa' }}, {{ now()->isoFormat('D MMMM Y') }}</div>
                <div style="font-weight: bold; margin-top: 3px;">Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}</div>
                <div style="margin-top: 50px; font-weight: bold; text-decoration: underline;">{{ $desa->nama_kades ?? 'Dr. H. Rahmat Hidayat, M.Si' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
