<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Inventaris Hasil Pembangunan - Tahun {{ $tahun }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.3;
            color: #1e293b;
        }
        .header-table {
            width: 100%;
            border-bottom: 2.5px double #0c3837;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-img {
            width: 50px;
            height: auto;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
            color: #0c3837;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h1 {
            margin: 2px 0;
            font-size: 13pt;
            font-weight: 800;
            color: #0c3837;
            text-transform: uppercase;
        }
        .header-title p {
            margin: 0;
            font-size: 7.5pt;
            color: #475569;
        }
        .doc-title {
            text-align: center;
            margin: 10px 0 14px 0;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 11pt;
            font-weight: 800;
            color: #0c3837;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 8pt;
            color: #475569;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-top: 8px;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 4px 6px;
        }
        .data-table th {
            background-color: #e2f0ed;
            color: #0c3837;
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            font-size: 7pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #f1f8f6;
            font-weight: bold;
        }
        .signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signatures td {
            vertical-align: top;
            text-align: center;
            font-size: 8pt;
            width: 50%;
        }
        .sign-title {
            margin-bottom: 45px;
            font-weight: bold;
            color: #0c3837;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0c3837;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI -->
    <table class="header-table">
        <tr>
            <td style="width: 12%; text-align: center;">
                <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo">
            </td>
            <td style="width: 88%;">
                <div class="header-title">
                    <h2>PEMERINTAH KABUPATEN {{ strtoupper($desa->kabupaten ?? 'Bandung') }}</h2>
                    <h2>KECAMATAN {{ strtoupper($desa->kecamatan ?? 'Pangalengan') }}</h2>
                    <h1>DESA {{ strtoupper($desa->nama_desa ?? 'Sukamaju') }}</h1>
                    <p>{{ $desa->alamat_kantor ?? 'Jl. Raya Desa No. 123' }} &bull; Kode Pos: {{ $desa->kode_pos ?? '40378' }} &bull; Email: {{ $desa->email_desa ?? 'desa@sukamaju.id' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- JUDUL LAPORAN -->
    <div class="doc-title">
        <h3>BUKU INVENTARIS HASIL PEMBANGUNAN DESA</h3>
        <p>TAHUN ANGGARAN {{ $tahun }}</p>
    </div>

    <!-- TABEL DATA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 90px;">No. Inventaris</th>
                <th style="width: 160px;">Jenis / Nama Hasil Pembangunan</th>
                <th style="width: 85px;">Kategori</th>
                <th style="width: 90px;">Volume / Ukuran</th>
                <th style="width: 110px;">Lokasi Pembangunan</th>
                <th style="width: 55px;">Sumber</th>
                <th style="width: 95px;">Nilai Perolehan (Rp)</th>
                <th style="width: 60px;">Kondisi</th>
                <th style="width: 110px;">Status Pengelolaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold text-center">{{ $item->nomor_inventaris }}</td>
                    <td class="font-bold">{{ $item->nama_hasil_pembangunan }}</td>
                    <td class="text-center">{{ $item->kategori_label }}</td>
                    <td class="text-center">{{ $item->volume }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td class="text-center">{{ $item->sumber_dana }}</td>
                    <td class="text-right font-bold">{{ number_format($item->nilai_aset, 0, ',', '.') }}</td>
                    <td class="text-center font-bold">{{ $item->kondisi_label }}</td>
                    <td>{{ $item->status_pengelolaan_label }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 15px; color: #64748b;">
                        Belum ada data inventaris hasil pembangunan pada tahun anggaran {{ $tahun }}.
                    </td>
                </tr>
            @endforelse

            @if(count($data) > 0)
                <tr class="total-row">
                    <td colspan="7" class="text-right font-bold">TOTAL NILAI PEROLEHAN ASET HASIL PEMBANGUNAN :</td>
                    <td class="text-right font-bold">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- LEMBAR PENGESAHAN -->
    <table class="signatures">
        <tr>
            <td>
                <div class="sign-title">
                    Mengetahui,<br>
                    Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}
                </div>
                <div class="sign-name">{{ $desa->nama_kades ?? 'H. AHMAD SOBARI, S.Sos' }}</div>
                <div style="font-size: 7pt; color: #64748b;">NIP. {{ $desa->nip_kades ?? '19750812 200212 1 003' }}</div>
            </td>
            <td>
                <div class="sign-title">
                    {{ $desa->nama_desa ?? 'Sukamaju' }}, {{ date('d F Y') }}<br>
                    Pelaksana Pembangunan / Kaur Perencanaan
                </div>
                <div class="sign-name">IRVAN HERMAWAN, S.T.</div>
                <div style="font-size: 7pt; color: #64748b;">Kepala Seksi Kesejahteraan</div>
            </td>
        </tr>
    </table>
</body>
</html>
