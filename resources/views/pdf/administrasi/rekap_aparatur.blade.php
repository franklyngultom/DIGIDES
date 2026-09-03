<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Register Aparat Pemerintah Desa</title>
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
            vertical-align: middle;
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
        .footer-sign {
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>
<body>
    @include('pdf.layouts.kop_surat')

    <div class="header-title">BUKU APARAT PEMERINTAH DESA</div>
    <div class="header-sub">Tahun: {{ $tahun ?: date('Y') }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Lengkap</th>
                <th style="width: 80px;">NIAP / NIP</th>
                <th style="width: 90px;">Jabatan</th>
                <th style="width: 70px;">Pendidikan</th>
                <th style="width: 90px;">No & Tgl Keputusan Pengangkatan</th>
                <th style="width: 90px;">No & Tgl Keputusan Pemberhentian</th>
                <th style="width: 50px;">Status</th>
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
                    <td>
                        <strong>{{ $item->penduduk->nama_lengkap ?? '-' }}</strong><br>
                        <small>NIK: {{ $item->penduduk->nik ?? '-' }}</small>
                    </td>
                    <td>{{ $item->nip ?? '-' }}</td>
                    <td><strong>{{ $item->jabatan }}</strong></td>
                    <td>{{ $item->penduduk->pendidikan_terakhir ?? '-' }}</td>
                    <td>
                        {{ $item->no_sk_pengangkatan ?? '-' }}<br>
                        @if($item->tanggal_pengangkatan)
                            <small>{{ \Carbon\Carbon::parse($item->tanggal_pengangkatan)->isoFormat('D MMM Y') }}</small>
                        @endif
                    </td>
                    <td>
                        {{ $item->no_sk_pemberhentian ?? '-' }}<br>
                        @if($item->tanggal_pemberhentian)
                            <small>{{ \Carbon\Carbon::parse($item->tanggal_pemberhentian)->isoFormat('D MMM Y') }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->status_aktif)
                            <span style="color: #059669; font-weight: bold;">Aktif</span>
                        @else
                            <span style="color: #dc2626;">Purna</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px;">Belum ada data aparatur pemerintah desa yang tercatat.</td>
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
