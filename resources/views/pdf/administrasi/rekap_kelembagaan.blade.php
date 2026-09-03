<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Register Administrasi Kelembagaan Desa</title>
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

    <div class="header-title">BUKU ADMINISTRASI LEMBAGA DESA: {{ strtoupper($institution->nama_lembaga ?? 'LEMBAGA') }}</div>
    <div class="header-sub">Tahun: {{ $tahun ?: date('Y') }}</div>

    <h4 style="margin-bottom: 5px; color: #0c3837;">I. DAFTAR PENGURUS & ANGGOTA</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Lengkap</th>
                <th style="width: 100px;">Jabatan</th>
                <th style="width: 100px;">Nomor Telepon</th>
                <th style="width: 120px;">No SK Penetapan</th>
                <th style="width: 60px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->penduduk->nama_lengkap ?? $item->nama_manual ?? '-' }}</strong><br>
                        <small>NIK: {{ $item->penduduk->nik ?? '-' }}</small>
                    </td>
                    <td><strong>{{ $item->jabatan }}</strong></td>
                    <td>{{ $item->telepon ?? $item->penduduk->telepon ?? '-' }}</td>
                    <td>{{ $item->no_sk_pengangkatan ?? '-' }}</td>
                    <td class="text-center">
                        @if($item->is_active ?? true)
                            <span style="color: #059669; font-weight: bold;">Aktif</span>
                        @else
                            <span style="color: #dc2626;">Non-Aktif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 10px;">Belum ada data anggota yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($activities) && count($activities) > 0)
    <h4 style="margin-top: 20px; margin-bottom: 5px; color: #0c3837;">II. BUKU KEGIATAN LEMBAGA</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama Kegiatan</th>
                <th style="width: 100px;">Lokasi</th>
                <th>Hasil & Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $index => $act)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($act->tanggal_kegiatan)->isoFormat('D MMM Y') }}</td>
                    <td><strong>{{ $act->nama_kegiatan }}</strong></td>
                    <td>{{ $act->lokasi ?? '-' }}</td>
                    <td>{{ $act->hasil_kegiatan ?? $act->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="footer-sign">
        <tr>
            <td style="width: 60%;">
                <div style="text-align: center; width: 200px;">
                    <div>Mengetahui,</div>
                    <div style="font-weight: bold; margin-top: 3px;">Ketua {{ $institution->nama_lembaga ?? 'Lembaga' }}</div>
                    <div style="margin-top: 50px; font-weight: bold; text-decoration: underline;">{{ $institution->nama_ketua ?? '................................' }}</div>
                </div>
            </td>
            <td style="text-align: center;">
                <div>{{ $desa->nama_desa ?? 'Desa' }}, {{ now()->isoFormat('D MMMM Y') }}</div>
                <div style="font-weight: bold; margin-top: 3px;">Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}</div>
                <div style="margin-top: 50px; font-weight: bold; text-decoration: underline;">{{ $desa->nama_kades ?? 'Dr. H. Rahmat Hidayat, M.Si' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
