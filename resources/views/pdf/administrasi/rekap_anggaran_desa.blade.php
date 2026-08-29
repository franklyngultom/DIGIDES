<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Anggaran Pendapatan dan Belanja Desa</title>
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

    <div class="header-title">BUKU ARSIP ANGGARAN PEMERINTAH DESA (APBDes)</div>
    <div class="header-sub">Tahun Anggaran: {{ $tahun ?: 'Semua Tahun' }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 50px;">Tahun</th>
                <th style="width: 100px;">Jenis Dokumen</th>
                <th style="width: 110px;">Nomor Perdes & Tgl Penetapan</th>
                <th style="width: 90px;">Pendapatan</th>
                <th style="width: 90px;">Belanja</th>
                <th style="width: 90px;">Pembiayaan Netto</th>
                <th>Keterangan</th>
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
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->tahun }}</td>
                    <td class="text-center"><strong>{{ strtoupper(str_replace('_', ' ', $item->jenis_dokumen)) }}</strong></td>
                    <td>
                        {{ $item->nomor_perdes }}<br>
                        <small>{{ \Carbon\Carbon::parse($item->tanggal_penetapan)->isoFormat('D MMMM Y') }}</small>
                    </td>
                    <td class="text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_pembiayaan, 0, ',', '.') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px;">Belum ada data dokumen APBDes yang tercatat.</td>
                </tr>
            @endforelse
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
