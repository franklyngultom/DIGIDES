<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RAB {{ $rab->nomor_rab }} - {{ $rab->nama_kegiatan }}</title>
    <style>
        @page {
            size: A4 portrait;
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
            width: 50px;
            height: auto;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 10.5pt;
            font-weight: bold;
            color: #0c3837;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h1 {
            margin: 2px 0;
            font-size: 12.5pt;
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
            margin: 10px 0 12px 0;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 11pt;
            font-weight: 800;
            color: #0c3837;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 8pt;
            font-weight: bold;
            color: #475569;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 8pt;
        }
        .meta-table td {
            padding: 2px 4px;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            color: #0c3837;
            width: 22%;
        }
        .meta-sep {
            width: 2%;
            text-align: center;
        }
        .meta-val {
            color: #1e293b;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 8pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
        }
        .data-table th {
            background-color: #f0f7f5;
            color: #0c3837;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 7.5pt;
            text-align: center;
        }
        .category-row {
            background-color: #e2f0ed;
            font-weight: bold;
            color: #0c3837;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #0c3837;
            color: #ffffff;
            font-weight: bold;
            font-size: 8.5pt;
        }
        .total-row td {
            border: 1px solid #0c3837;
            padding: 6px;
        }
        .signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signatures td {
            vertical-align: top;
            text-align: center;
            font-size: 8.5pt;
            width: 50%;
        }
        .sign-title {
            margin-bottom: 50px;
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
            <td style="width: 15%; text-align: center;">
                <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo">
            </td>
            <td style="width: 85%;">
                <div class="header-title">
                    <h2>PEMERINTAH KABUPATEN {{ strtoupper($desa->kabupaten ?? 'Bandung') }}</h2>
                    <h2>KECAMATAN {{ strtoupper($desa->kecamatan ?? 'Pangalengan') }}</h2>
                    <h1>DESA {{ strtoupper($desa->nama_desa ?? 'Sukamaju') }}</h1>
                    <p>{{ $desa->alamat_kantor ?? 'Jl. Raya Desa No. 123' }} &bull; Kode Pos: {{ $desa->kode_pos ?? '40378' }} &bull; Email: {{ $desa->email_desa ?? 'desa@sukamaju.id' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">
        <h3>RENCANA ANGGARAN BIAYA (RAB) KEGIATAN DESA</h3>
        <p>Nomor: {{ $rab->nomor_rab }} &bull; Tahun Anggaran: {{ $rab->tahun_anggaran }}</p>
    </div>

    <!-- INFORMASI UMUM -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Bidang Pemerintahan</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $rab->bidang }}</td>
            <td class="meta-label">Sumber Dana</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $rab->sumber_dana }}</td>
        </tr>
        <tr>
            <td class="meta-label">Nama Kegiatan</td>
            <td class="meta-sep">:</td>
            <td class="meta-val font-bold">{{ $rab->nama_kegiatan }}</td>
            <td class="meta-label">Waktu Pelaksanaan</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $rab->waktu_pelaksanaan }}</td>
        </tr>
        <tr>
            <td class="meta-label">Lokasi Target</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $rab->lokasi }}</td>
            <td class="meta-label">Pelaksana Teknis</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $rab->nama_ppkd ?? 'Kaur / Kasi Teknis' }} ({{ $rab->jabatan_ppkd ?? 'PPKD' }})</td>
        </tr>
    </table>

    <!-- TABEL RINCIAN ITEM RAB -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 14%;">KODE REK.</th>
                <th style="width: 41%;">URAIAN ITEM / KEBUTUHAN</th>
                <th style="width: 8%;">VOLUME</th>
                <th style="width: 8%;">SATUAN</th>
                <th style="width: 12%;">HARGA SATUAN</th>
                <th style="width: 12%;">JUMLAH TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $noUrut = 1; @endphp

            @foreach(['bahan_material' => '1. KELOMPOK BAHAN & MATERIAL', 'upah_tenaga_kerja' => '2. KELOMPOK UPAH TENAGA KERJA (HOK)', 'sewa_alat' => '3. KELOMPOK SEWA PERALATAN', 'operasional' => '4. KELOMPOK BIAYA OPERASIONAL & LAINNYA'] as $catKey => $catLabel)
                @if(isset($groupedItems[$catKey]) && count($groupedItems[$catKey]) > 0)
                    <tr class="category-row">
                        <td colspan="6" style="padding-left: 8px;">{{ $catLabel }}</td>
                        <td class="text-right">Rp {{ number_format($subtotals[$catKey], 0, ',', '.') }}</td>
                    </tr>

                    @foreach($groupedItems[$catKey] as $item)
                        <tr>
                            <td class="text-center">{{ $noUrut++ }}</td>
                            <td class="text-center">{{ $item->kode_rekening ?? '-' }}</td>
                            <td>
                                {{ $item->uraian }}
                                @if($item->keterangan)
                                    <br><small style="color: #64748b; font-style: italic;">Spesifikasi: {{ $item->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($item->volume, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->satuan }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach

            <!-- TOTAL ANGGARAN -->
            <tr class="total-row">
                <td colspan="6" class="text-right" style="padding-right: 12px;">TOTAL RENCANA ANGGARAN BIAYA (RAB) :</td>
                <td class="text-right">Rp {{ number_format($rab->total_anggaran, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- LEMBAR PENGESAHAN -->
    <table class="signatures">
        <tr>
            <td>
                <div class="sign-title">
                    Menyetujui,<br>
                    Kepala Desa {{ $desa->nama_desa ?? 'Sukamaju' }}
                </div>
                <div class="sign-name">{{ $desa->nama_kades ?? 'H. AHMAD SOBARI, S.Sos' }}</div>
                <div style="font-size: 7.5pt; color: #64748b;">NIP. {{ $desa->nip_kades ?? '19750812 200212 1 003' }}</div>
            </td>
            <td>
                <div class="sign-title">
                    {{ $desa->nama_desa ?? 'Sukamaju' }}, {{ date('d F Y') }}<br>
                    Pelaksana Pengelola Kegiatan (PPKD)
                </div>
                <div class="sign-name">{{ $rab->nama_ppkd ?? 'Kaur / Kasi Teknis' }}</div>
                <div style="font-size: 7.5pt; color: #64748b;">{{ $rab->jabatan_ppkd ?? 'Kepala Seksi Kesejahteraan' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
