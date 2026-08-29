<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Realisasi Pembangunan Desa - Tahun {{ $tahun }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
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
            width: 55px;
            height: auto;
        }
        .header-title-kiri {
            text-align: center;
        }
        .header-title-kiri h2 {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
            color: #0c3837;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title-kiri h1 {
            margin: 2px 0;
            font-size: 13pt;
            font-weight: 800;
            color: #0c3837;
            text-transform: uppercase;
        }
        .header-title-kiri p {
            margin: 0;
            font-size: 7.5pt;
            color: #475569;
        }
        .report-title {
            text-align: center;
            margin: 10px 0 14px 0;
        }
        .report-title h3 {
            margin: 0;
            font-size: 11pt;
            font-weight: bold;
            color: #0c3837;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-title p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 600;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.data-table th {
            background-color: #f1f8f6;
            color: #0c3837;
            font-weight: bold;
            border: 1px solid #94a3b8;
            padding: 5px 4px;
            text-align: center;
            font-size: 7.5pt;
            text-transform: uppercase;
        }
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 4px;
            font-size: 7.5pt;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #fcfdfd;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .footer-sign {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .footer-sign td {
            vertical-align: top;
            font-size: 8pt;
            text-align: center;
            width: 50%;
        }
        .signature-space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT PEMERINTAH DESA -->
    <table class="header-table">
        <tr>
            <td style="width: 70px; text-align: center;">
                <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo">
            </td>
            <td class="header-title-kiri">
                <h2>Pemerintah Kabupaten {{ $desa->kabupaten ?? 'Sukabumi' }}</h2>
                <h2>Kecamatan {{ $desa->kecamatan ?? 'Cikole' }}</h2>
                <h1>Pemerintah Desa {{ $desa->nama_desa ?? 'Sukamaju' }}</h1>
                <p>{{ $desa->alamat_kantor ?? 'Jl. Raya Desa No. 01' }} &bull; Telp: {{ $desa->telepon ?? '-' }} &bull; Email: {{ $desa->email_desa ?? 'desa@sukamaju.desa.id' }}</p>
            </td>
            <td style="width: 70px;"></td>
        </tr>
    </table>

    <div class="report-title">
        <h3>LAPORAN KEGIATAN & HASIL PEMBANGUNAN DESA (RKP DESA)</h3>
        <p>TAHUN ANGGARAN {{ $tahun ?? date('Y') }}</p>
    </div>

    <!-- TABEL DATA PROYEK PEMBANGUNAN -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Kegiatan Pembangunan</th>
                <th style="width: 110px;">Lokasi Proyek</th>
                <th style="width: 80px;">Volume</th>
                <th style="width: 75px;">Sumber Dana</th>
                <th style="width: 90px;">Pelaksana (TPK)</th>
                <th style="width: 85px;">Anggaran (Rp)</th>
                <th style="width: 85px;">Realisasi (Rp)</th>
                <th style="width: 50px;">Progres</th>
                <th style="width: 65px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totPagu = 0;
                $totReal = 0;
            @endphp
            @forelse($data as $index => $item)
                @php
                    $totPagu += $item->anggaran_biaya;
                    $totReal += $item->realisasi_biaya;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $item->nama_kegiatan }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td class="text-center">{{ $item->volume }}</td>
                    <td class="text-center">{{ $item->sumber_dana }}</td>
                    <td>{{ $item->pelaksana_tpk }}</td>
                    <td class="text-right">{{ number_format($item->anggaran_biaya, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->realisasi_biaya, 0, ',', '.') }}</td>
                    <td class="text-center text-bold">{{ $item->persentase_selesai }}%</td>
                    <td class="text-center" style="text-transform: capitalize;">{{ $item->status_progres }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 15px; color: #64748b;">
                        Belum ada kegiatan pembangunan fisik yang tercatat pada tahun anggaran {{ $tahun }}.
                    </td>
                </tr>
            @endforelse
            @if(count($data) > 0)
                <tr style="background-color: #f1f8f6; font-weight: bold;">
                    <td colspan="6" class="text-right text-bold">TOTAL ANGGARAN & REALISASI :</td>
                    <td class="text-right text-bold">Rp {{ number_format($totPagu, 0, ',', '.') }}</td>
                    <td class="text-right text-bold">Rp {{ number_format($totReal, 0, ',', '.') }}</td>
                    <td colspan="2" class="text-center text-bold">
                        {{ $totPagu > 0 ? round(($totReal / $totPagu) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- LEMBAR TANDA TANGAN (KADES & TIM PELAKSANA KEGIATAN) -->
    <table class="footer-sign">
        <tr>
            <td>
                Mengesahkan,<br>
                <strong>Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}</strong>
                <div class="signature-space"></div>
                <strong><u>{{ $desa->nama_kades ?? 'H. AHMAD SANUSI, S.AP' }}</u></strong><br>
                <span>NIPD: {{ $desa->nip_kades ?? '197508122005011004' }}</span>
            </td>
            <td>
                {{ $desa->nama_desa ?? 'Sukamaju' }}, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                <strong>Kasi Kesejahteraan / Pelaksana Kegiatan</strong>
                <div class="signature-space"></div>
                <strong><u>KURNIAWAN, S.T.</u></strong><br>
                <span>Ketua Tim Pelaksana Kegiatan (TPK)</span>
            </td>
        </tr>
    </table>

</body>
</html>
