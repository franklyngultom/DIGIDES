<?php

namespace Database\Seeders;

use App\Models\KeuanganApbdes;
use App\Models\KeuanganKasTransaksi;
use App\Models\KeuanganRab;
use App\Models\KeuanganRabItem;
use App\Models\PembangunanInventarisHasil;
use App\Models\PembangunanKader;
use App\Models\PembangunanProyek;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\Seeder;

class KeuanganPembangunanSeeder extends Seeder
{
    /**
     * Run the database seeds for Keuangan & Pembangunan Desa (Phase 7).
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@desa.id')->first() ?? User::first();
        $adminId = $admin ? $admin->id : 1;

        // 1. Seed Master & Realisasi APBDes 2026
        $apbdesRecords = [
            // Pendapatan
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '4.1.1.01',
                'jenis'          => 'pendapatan',
                'bidang'         => 'Pendapatan Asli Desa (PADes)',
                'uraian'         => 'Bagi Hasil Usaha BUMDes Sukamaju Makmur',
                'anggaran'       => 45000000,
                'realisasi'      => 22500000,
                'sumber_dana'    => 'PAD',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '4.2.1.01',
                'jenis'          => 'pendapatan',
                'bidang'         => 'Pendapatan Transfer - Dana Desa',
                'uraian'         => 'Penyaluran Dana Desa (DDS) Tahap I & II',
                'anggaran'       => 850000000,
                'realisasi'      => 510000000,
                'sumber_dana'    => 'DDS',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '4.2.2.01',
                'jenis'          => 'pendapatan',
                'bidang'         => 'Pendapatan Transfer - Alokasi Dana Desa',
                'uraian'         => 'Alokasi Dana Desa (ADD) dari APBD Kabupaten',
                'anggaran'       => 420000000,
                'realisasi'      => 280000000,
                'sumber_dana'    => 'ADD',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '4.2.3.01',
                'jenis'          => 'pendapatan',
                'bidang'         => 'Pendapatan Transfer - Bagi Hasil Pajak',
                'uraian'         => 'Bagi Hasil Pajak & Retribusi Daerah (PBH)',
                'anggaran'       => 65000000,
                'realisasi'      => 35000000,
                'sumber_dana'    => 'PBH',
            ],

            // Belanja
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '5.1.1.01',
                'jenis'          => 'belanja',
                'bidang'         => 'Bidang 1: Penyelenggaraan Pemerintahan Desa',
                'uraian'         => 'Penghasilan Tetap & Tunjangan Kepala Desa serta Perangkat Desa',
                'anggaran'       => 280000000,
                'realisasi'      => 165000000,
                'sumber_dana'    => 'ADD',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '5.2.1.01',
                'jenis'          => 'belanja',
                'bidang'         => 'Bidang 2: Pelaksanaan Pembangunan Desa',
                'uraian'         => 'Pengaspalan Hotmix Jalan Usaha Tani Dusun Babakan',
                'anggaran'       => 185000000,
                'realisasi'      => 185000000,
                'sumber_dana'    => 'DDS',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '5.2.2.01',
                'jenis'          => 'belanja',
                'bidang'         => 'Bidang 2: Pelaksanaan Pembangunan Desa',
                'uraian'         => 'Pembangunan Gedung Posyandu Melati Dusun 2',
                'anggaran'       => 95000000,
                'realisasi'      => 50000000,
                'sumber_dana'    => 'DDS',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '5.3.1.01',
                'jenis'          => 'belanja',
                'bidang'         => 'Bidang 3: Pembinaan Kemasyarakatan Desa',
                'uraian'         => 'Penyelenggaraan Festival Seni Budaya & Turnamen Olahraga Desa',
                'anggaran'       => 35000000,
                'realisasi'      => 20000000,
                'sumber_dana'    => 'PAD',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '5.4.1.01',
                'jenis'          => 'belanja',
                'bidang'         => 'Bidang 4: Pemberdayaan Masyarakat Desa',
                'uraian'         => 'Pelatihan Keterampilan UMKM & Honor Insentif Kader KPM',
                'anggaran'       => 45000000,
                'realisasi'      => 25000000,
                'sumber_dana'    => 'DDS',
            ],
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '5.5.1.01',
                'jenis'          => 'belanja',
                'bidang'         => 'Bidang 5: Penanggulangan Bencana & Darurat',
                'uraian'         => 'Dana Siaga Mitigasi Longsor & Tanggap Darurat Kesehatan',
                'anggaran'       => 50000000,
                'realisasi'      => 12500000,
                'sumber_dana'    => 'DDS',
            ],

            // Pembiayaan
            [
                'tahun_anggaran' => 2026,
                'kode_rekening'  => '6.1.1.01',
                'jenis'          => 'pembiayaan',
                'bidang'         => 'Penerimaan Pembiayaan Desa',
                'uraian'         => 'Sisa Lebih Perhitungan Anggaran (SiLPA) Tahun Anggaran 2025',
                'anggaran'       => 85000000,
                'realisasi'      => 85000000,
                'sumber_dana'    => 'DLL',
            ],
        ];

        foreach ($apbdesRecords as $record) {
            KeuanganApbdes::updateOrCreate(
                [
                    'tahun_anggaran' => $record['tahun_anggaran'],
                    'kode_rekening'  => $record['kode_rekening'],
                ],
                $record
            );
        }

        // 2. Seed Transaksi Buku Kas Umum (BKU - Tunai)
        $kasUmumRecords = [
            [
                'buku_kas_type'  => 'umum',
                'kategori_kas'   => 'tunai',
                'jenis_pembantu' => 'umum',
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-01-10',
                'nomor_bukti'    => 'BKM-01/DDS/2026',
                'kode_rekening'  => '4.2.1.01',
                'uraian'         => 'Penerimaan Penyaluran Dana Desa (DDS) Tahap I Tahun 2026',
                'penerimaan'     => 340000000,
                'pengeluaran'    => 0,
                'sumber_dana'    => 'DDS',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'umum',
                'kategori_kas'   => 'tunai',
                'jenis_pembantu' => 'panjar',
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-01-15',
                'nomor_bukti'    => 'BKK-01/DDS/2026',
                'kode_rekening'  => '5.2.1.01',
                'uraian'         => 'Pembayaran Termin 1 Pengaspalan Jalan Usaha Tani Dusun Babakan',
                'penerimaan'     => 0,
                'pengeluaran'    => 90000000,
                'sumber_dana'    => 'DDS',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'umum',
                'kategori_kas'   => 'tunai',
                'jenis_pembantu' => 'umum',
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-02-01',
                'nomor_bukti'    => 'BKM-02/ADD/2026',
                'kode_rekening'  => '4.2.2.01',
                'uraian'         => 'Penerimaan Alokasi Dana Desa (ADD) Triwulan I',
                'penerimaan'     => 105000000,
                'pengeluaran'    => 0,
                'sumber_dana'    => 'ADD',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'umum',
                'kategori_kas'   => 'tunai',
                'jenis_pembantu' => 'umum',
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-02-05',
                'nomor_bukti'    => 'BKK-02/ADD/2026',
                'kode_rekening'  => '5.1.1.01',
                'uraian'         => 'Pembayaran Siltap dan Tunjangan Aparatur Desa Bulan Januari',
                'penerimaan'     => 0,
                'pengeluaran'    => 23500000,
                'sumber_dana'    => 'ADD',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'umum',
                'kategori_kas'   => 'tunai',
                'jenis_pembantu' => 'panjar',
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-02-20',
                'nomor_bukti'    => 'BKK-03/DDS/2026',
                'kode_rekening'  => '5.2.2.01',
                'uraian'         => 'Pembelian Material Batu, Semen & Besi Gedung Posyandu Dusun 2',
                'penerimaan'     => 0,
                'pengeluaran'    => 35000000,
                'sumber_dana'    => 'DDS',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'umum',
                'kategori_kas'   => 'tunai',
                'jenis_pembantu' => 'pajak',
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-03-01',
                'nomor_bukti'    => 'BKM-03/PAD/2026',
                'kode_rekening'  => '4.1.1.01',
                'uraian'         => 'Setoran Bagi Hasil Laba BUMDes Unit Pengelolaan Air Bersih',
                'penerimaan'     => 12500000,
                'pengeluaran'    => 0,
                'sumber_dana'    => 'PAD',
                'created_by'     => $adminId,
            ],
        ];

        foreach ($kasUmumRecords as $row) {
            KeuanganKasTransaksi::updateOrCreate(
                [
                    'tahun_anggaran' => $row['tahun_anggaran'],
                    'buku_kas_type'  => $row['buku_kas_type'],
                    'nomor_bukti'    => $row['nomor_bukti'],
                ],
                $row
            );
        }
        KeuanganKasTransaksi::recalculateBalances(2026, 'tunai');

        // 3. Seed Transaksi Kas Bank Desa
        $kasBankRecords = [
            [
                'buku_kas_type'  => 'bank',
                'kategori_kas'   => 'bank',
                'jenis_pembantu' => null,
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-01-10',
                'nomor_bukti'    => 'TRF-BJB/01/2026',
                'kode_rekening'  => '4.2.1.01',
                'uraian'         => 'Transfer Masuk dari Kas Daerah - Dana Desa Tahap I',
                'penerimaan'     => 340000000,
                'pengeluaran'    => 0,
                'sumber_dana'    => 'DDS',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'bank',
                'kategori_kas'   => 'bank',
                'jenis_pembantu' => null,
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-01-14',
                'nomor_bukti'    => 'CEK-BJB/01/2026',
                'kode_rekening'  => '5.2.1.01',
                'uraian'         => 'Penarikan Cek Operasional Belanja Aspal Jalan Babakan',
                'penerimaan'     => 0,
                'pengeluaran'    => 90000000,
                'sumber_dana'    => 'DDS',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'bank',
                'kategori_kas'   => 'bank',
                'jenis_pembantu' => null,
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-01-31',
                'nomor_bukti'    => 'BUNGA-BJB/01/2026',
                'kode_rekening'  => '4.1.1.01',
                'uraian'         => 'Penerimaan Pendapatan Bunga Rekening Giro Kas Desa',
                'penerimaan'     => 350000,
                'pengeluaran'    => 0,
                'sumber_dana'    => 'PAD',
                'created_by'     => $adminId,
            ],
            [
                'buku_kas_type'  => 'bank',
                'kategori_kas'   => 'bank',
                'jenis_pembantu' => null,
                'tahun_anggaran' => 2026,
                'tanggal'        => '2026-01-31',
                'nomor_bukti'    => 'ADM-BJB/01/2026',
                'kode_rekening'  => '5.1.1.01',
                'uraian'         => 'Biaya Administrasi Bulanan Bank & Pajak Giro',
                'penerimaan'     => 0,
                'pengeluaran'    => 75000,
                'sumber_dana'    => 'PAD',
                'created_by'     => $adminId,
            ],
        ];

        foreach ($kasBankRecords as $row) {
            KeuanganKasTransaksi::updateOrCreate(
                [
                    'tahun_anggaran' => $row['tahun_anggaran'],
                    'buku_kas_type'  => $row['buku_kas_type'],
                    'nomor_bukti'    => $row['nomor_bukti'],
                ],
                $row
            );
        }
        KeuanganKasTransaksi::recalculateBalances(2026, 'bank');

        // 4. Seed Proyek Pembangunan Desa (RKP Desa & Hasil Fisik)
        $proyekRecords = [
            [
                'tahun_anggaran'     => 2026,
                'nama_kegiatan'      => 'Pengaspalan Hotmix Jalan Usaha Tani Dusun Babakan',
                'lokasi'             => 'Dusun Babakan RT 03 / RW 01',
                'volume'             => 'Panjang 450m x Lebar 3m x Tebal 4cm',
                'anggaran_biaya'     => 185000000,
                'realisasi_biaya'    => 185000000,
                'sumber_dana'        => 'Dana Desa (DDS)',
                'pelaksana_tpk'      => 'TPK Dusun Babakan (Ketua: Bpk. Suryadi)',
                'status_progres'     => 'selesai',
                'persentase_selesai' => 100,
                'manfaat_warga'      => 'Mempermudah akses transportasi panen 120 KK petani padi dan palawija',
            ],
            [
                'tahun_anggaran'     => 2026,
                'nama_kegiatan'      => 'Pembangunan Gedung Posyandu Melati Dusun 2',
                'lokasi'             => 'Kp. Sukamaju RW 02 (Samping Balai Dusun)',
                'volume'             => 'Bangunan Permanen 6m x 8m',
                'anggaran_biaya'     => 95000000,
                'realisasi_biaya'    => 50000000,
                'sumber_dana'        => 'Dana Desa (DDS)',
                'pelaksana_tpk'      => 'TPK Pembangunan Desa Sukamaju',
                'status_progres'     => 'proses',
                'persentase_selesai' => 50,
                'manfaat_warga'      => 'Pelayanan pemeriksaan kesehatan ibu hamil, imunisasi balita & posbindu lansia',
            ],
            [
                'tahun_anggaran'     => 2026,
                'nama_kegiatan'      => 'Pembangunan Drainase Lingkungan & U-Ditch RW 03',
                'lokasi'             => 'Jl. Flamboyan Gang 2 RT 01 s/d RT 03 RW 03',
                'volume'             => 'Panjang 200m (Beton Precast U-Ditch 40x40)',
                'anggaran_biaya'     => 65000000,
                'realisasi_biaya'    => 0,
                'sumber_dana'        => 'Dana Desa (DDS)',
                'pelaksana_tpk'      => 'TPK Dusun 3 Sukamaju',
                'status_progres'     => 'perencanaan',
                'persentase_selesai' => 0,
                'manfaat_warga'      => 'Mencegah genangan air hujan dan banjir luapan bagi 65 KK warga RW 03',
            ],
        ];

        foreach ($proyekRecords as $proyek) {
            PembangunanProyek::updateOrCreate(
                [
                    'tahun_anggaran' => $proyek['tahun_anggaran'],
                    'nama_kegiatan'  => $proyek['nama_kegiatan'],
                ],
                $proyek
            );
        }

        // 5. Seed Kader Pemberdayaan Masyarakat (KPM)
        $pendudukList = Penduduk::limit(5)->get();
        if ($pendudukList->isNotEmpty()) {
            $kaderData = [
                [
                    'penduduk_id'   => $pendudukList[0]->id,
                    'jenis_kader'   => 'kpm_stunting',
                    'jabatan'       => 'Koordinator Kader Pembangunan Manusia (KPM Stunting)',
                    'nomor_sk'      => '141.1/SK-08/DS/2026',
                    'tanggal_sk'    => '2026-01-05',
                    'honor_bulanan' => 450000,
                    'keterangan'    => 'Monitoring pemenuhan gizi 1000 HPK & pendataan balita di seluruh dusun',
                    'status_aktif'  => true,
                ],
            ];

            if ($pendudukList->count() > 1) {
                $kaderData[] = [
                    'penduduk_id'   => $pendudukList[1]->id,
                    'jenis_kader'   => 'posyandu',
                    'jabatan'       => 'Ketua Kader Posyandu Melati Dusun 1',
                    'nomor_sk'      => '141.1/SK-09/DS/2026',
                    'tanggal_sk'    => '2026-01-05',
                    'honor_bulanan' => 300000,
                    'keterangan'    => 'Pelaksanaan posyandu balita bulanan dan PMT',
                    'status_aktif'  => true,
                ];
            }

            if ($pendudukList->count() > 2) {
                $kaderData[] = [
                    'penduduk_id'   => $pendudukList[2]->id,
                    'jenis_kader'   => 'guru_paud',
                    'jabatan'       => 'Tenaga Pendidik PAUD Tunas Bangsa',
                    'nomor_sk'      => '141.1/SK-10/DS/2026',
                    'tanggal_sk'    => '2026-01-05',
                    'honor_bulanan' => 500000,
                    'keterangan'    => 'Insentif guru PAUD binaan desa',
                    'status_aktif'  => true,
                ];
            }

            foreach ($kaderData as $kader) {
                PembangunanKader::updateOrCreate(
                    [
                        'penduduk_id' => $kader['penduduk_id'],
                        'jenis_kader' => $kader['jenis_kader'],
                    ],
                    $kader
                );
            }
        }

        // 5. Seed Rencana Anggaran Biaya (RAB) Desa 2026
        $rab1 = KeuanganRab::updateOrCreate(
            ['nomor_rab' => 'RAB/01/DDS/2026'],
            [
                'tahun_anggaran'    => 2026,
                'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
                'sub_bidang'        => 'Pekerjaan Umum dan Penataan Ruang',
                'nama_kegiatan'     => 'Pembangunan Rabat Beton Jalan Usaha Tani Dusun Babakan',
                'lokasi'            => 'Dusun Babakan RT 02 / RW 03',
                'waktu_pelaksanaan' => '90 Hari Kalender (Maret - Mei 2026)',
                'sumber_dana'       => 'DDS',
                'nama_ppkd'         => 'Irvan Hermawan, S.T.',
                'jabatan_ppkd'      => 'Kepala Seksi Kesejahteraan',
                'total_anggaran'    => 145000000,
                'status'            => 'disetujui',
                'keterangan'        => 'Peningkatan akses jalan pertanian sepanjang 450 meter dengan spesifikasi tebal 15cm dan lebar 2.5 meter',
                'created_by'        => $adminId,
            ]
        );

        $rab1->items()->delete();
        $rab1Items = [
            // Bahan Material
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Semen Portland (50 kg) Standar SNI',
                'volume'        => 450,
                'satuan'        => 'Zak',
                'harga_satuan'  => 68000,
                'total_harga'   => 30600000,
                'keterangan'    => 'Semen Gresik / Tiga Roda',
                'urutan'        => 1,
            ],
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Pasir Pasang / Pasir Cor Berkualitas',
                'volume'        => 85,
                'satuan'        => 'M3',
                'harga_satuan'  => 280000,
                'total_harga'   => 23800000,
                'keterangan'    => 'Pasir kali Galunggung',
                'urutan'        => 2,
            ],
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Batu Split / Kerikil Cor 2/3',
                'volume'        => 95,
                'satuan'        => 'M3',
                'harga_satuan'  => 310000,
                'total_harga'   => 29450000,
                'keterangan'    => 'Split pecah mesin',
                'urutan'        => 3,
            ],
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Besi Wiremesh M6 Standar',
                'volume'        => 60,
                'satuan'        => 'Lembar',
                'harga_satuan'  => 360000,
                'total_harga'   => 21600000,
                'keterangan'    => 'Tulangan bawah jalan',
                'urutan'        => 4,
            ],
            // Upah Tenaga Kerja
            [
                'kode_rekening' => '5.2.1.02',
                'kategori'      => 'upah_tenaga_kerja',
                'uraian'        => 'Upah Tukang Batu Berpengalaman',
                'volume'        => 90,
                'satuan'        => 'HOK',
                'harga_satuan'  => 130000,
                'total_harga'   => 11700000,
                'keterangan'    => 'Tukang lokal warga desa',
                'urutan'        => 5,
            ],
            [
                'kode_rekening' => '5.2.1.02',
                'kategori'      => 'upah_tenaga_kerja',
                'uraian'        => 'Upah Pekerja / Pembantu Tukang (Padat Karya Tunai Desa)',
                'volume'        => 200,
                'satuan'        => 'HOK',
                'harga_satuan'  => 95000,
                'total_harga'   => 19000000,
                'keterangan'    => 'PKTD masyarakat miskin/pra-sejahtera',
                'urutan'        => 6,
            ],
            // Sewa Alat
            [
                'kode_rekening' => '5.2.1.03',
                'kategori'      => 'sewa_alat',
                'uraian'        => 'Sewa Mesin Molen Pengaduk Semen',
                'volume'        => 20,
                'satuan'        => 'Hari',
                'harga_satuan'  => 250000,
                'total_harga'   => 5000000,
                'keterangan'    => 'Termasuk bahan bakar & operator mesin',
                'urutan'        => 7,
            ],
            // Operasional
            [
                'kode_rekening' => '5.2.1.04',
                'kategori'      => 'operasional',
                'uraian'        => 'Papan Nama Proyek & Prasasti Marmer Peresmian',
                'volume'        => 1,
                'satuan'        => 'Paket',
                'harga_satuan'  => 1850000,
                'total_harga'   => 1850000,
                'keterangan'    => 'Transparansi publik dan prasasti',
                'urutan'        => 8,
            ],
            [
                'kode_rekening' => '5.2.1.04',
                'kategori'      => 'operasional',
                'uraian'        => 'Honorarium Tim Pengelola Kegiatan (TPK) & Pelaporan',
                'volume'        => 1,
                'satuan'        => 'Paket',
                'harga_satuan'  => 2000000,
                'total_harga'   => 2000000,
                'keterangan'    => 'Dokumentasi 0%, 50%, 100% dan LPJ',
                'urutan'        => 9,
            ],
        ];

        foreach ($rab1Items as $i) {
            $rab1->items()->create($i);
        }
        $rab1->recalculateTotal();

        $rab2 = KeuanganRab::updateOrCreate(
            ['nomor_rab' => 'RAB/02/DDS/2026'],
            [
                'tahun_anggaran'    => 2026,
                'bidang'            => 'Bidang 2: Pelaksanaan Pembangunan Desa',
                'sub_bidang'        => 'Kawasan Permukiman dan Sanitasi',
                'nama_kegiatan'     => 'Penyediaan Sarana Air Bersih & MCK Komunal Dusun 2',
                'lokasi'            => 'Dusun 2 RT 04 / RW 02',
                'waktu_pelaksanaan' => '60 Hari Kalender (Juni - Juli 2026)',
                'sumber_dana'       => 'DDS',
                'nama_ppkd'         => 'Irvan Hermawan, S.T.',
                'jabatan_ppkd'      => 'Kepala Seksi Kesejahteraan',
                'total_anggaran'    => 65000000,
                'status'            => 'draft',
                'keterangan'        => 'Pengeboran sumur artesis dalam, tangki toren 2000L, dan pipanisasi ke 40 KK',
                'created_by'        => $adminId,
            ]
        );

        $rab2->items()->delete();
        $rab2Items = [
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Tangki Air Pinguin 2000 Liter & Menara Besi Siku',
                'volume'        => 1,
                'satuan'        => 'Unit',
                'harga_satuan'  => 14500000,
                'total_harga'   => 14500000,
                'keterangan'    => 'Kapasitas 2.000 Liter SNI',
                'urutan'        => 1,
            ],
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Pipa PVC Rucika AW 1 Inch & Fitting Sambungan',
                'volume'        => 120,
                'satuan'        => 'Batang',
                'harga_satuan'  => 85000,
                'total_harga'   => 10200000,
                'keterangan'    => 'Pipa distribusi dusun',
                'urutan'        => 2,
            ],
            [
                'kode_rekening' => '5.2.1.01',
                'kategori'      => 'bahan_material',
                'uraian'        => 'Mesin Pompa Submersible Deep Well 1.5 HP',
                'volume'        => 1,
                'satuan'        => 'Unit',
                'harga_satuan'  => 8800000,
                'total_harga'   => 8800000,
                'keterangan'    => 'Pompa submersible kedalaman 60m',
                'urutan'        => 3,
            ],
            [
                'kode_rekening' => '5.2.1.03',
                'kategori'      => 'sewa_alat',
                'uraian'        => 'Jasa Pengeboran Sumur Artesis Kedalaman 60 Meter',
                'volume'        => 1,
                'satuan'        => 'Paket',
                'harga_satuan'  => 22000000,
                'total_harga'   => 22000000,
                'keterangan'    => 'Rig bor sumur lengkap',
                'urutan'        => 4,
            ],
            [
                'kode_rekening' => '5.2.1.02',
                'kategori'      => 'upah_tenaga_kerja',
                'uraian'        => 'Upah Pemasangan Jaringan Pipa & Kran Distribusi',
                'volume'        => 70,
                'satuan'        => 'HOK',
                'harga_satuan'  => 110000,
                'total_harga'   => 7700000,
                'keterangan'    => 'Instalatur lokal',
                'urutan'        => 5,
            ],
            [
                'kode_rekening' => '5.2.1.04',
                'kategori'      => 'operasional',
                'uraian'        => 'Uji Laboratorium Kualitas Air & Sosialisasi Warga',
                'volume'        => 1,
                'satuan'        => 'Paket',
                'harga_satuan'  => 1800000,
                'total_harga'   => 1800000,
                'keterangan'    => 'Uji lab kelayakan konsumsi',
                'urutan'        => 6,
            ],
        ];

        foreach ($rab2Items as $i) {
            $rab2->items()->create($i);
        }
        $rab2->recalculateTotal();

        // 6. Seed Inventaris Hasil Pembangunan 2026
        $completedProyek = PembangunanProyek::where('status_progres', 'selesai')->first();

        PembangunanInventarisHasil::updateOrCreate(
            ['nomor_inventaris' => 'INV-BANG/2026/001'],
            [
                'tahun_anggaran'          => 2026,
                'nama_hasil_pembangunan' => 'Jalan Usaha Tani Rabat Beton Babakan',
                'pembangunan_proyek_id'   => $completedProyek ? $completedProyek->id : null,
                'kategori_aset'           => 'jalan_jembatan',
                'volume'                  => 'Panjang 450m x Lebar 2.5m x Tebal 15cm',
                'lokasi'                  => 'Dusun Babakan RT 02 / RW 03',
                'tanggal_serah_terima'    => '2026-03-25',
                'sumber_dana'             => 'DDS',
                'nilai_aset'              => 145000000,
                'kondisi'                 => 'baik',
                'status_pengelolaan'      => 'dikelola_desa',
                'penanggung_jawab'        => 'Kaur Pembangunan & Kepala Dusun Babakan',
                'keterangan'              => 'Konstruksi beton K-225 bertulang wiremesh, menghubungkan sentra pertanian ke jalan utama',
                'created_by'              => $adminId,
            ]
        );

        PembangunanInventarisHasil::updateOrCreate(
            ['nomor_inventaris' => 'INV-BANG/2026/002'],
            [
                'tahun_anggaran'          => 2026,
                'nama_hasil_pembangunan' => 'Sarana MCK & Sumur Bor Komunal Dusun 1',
                'pembangunan_proyek_id'   => null,
                'kategori_aset'           => 'sarana_air_bersih',
                'volume'                  => '1 Unit Bangunan 4 Pintu & Toren 1.500L',
                'lokasi'                  => 'Kp. Sukamaju RW 01 RT 04',
                'tanggal_serah_terima'    => '2026-02-15',
                'sumber_dana'             => 'Bantuan Provinsi',
                'nilai_aset'              => 48000000,
                'kondisi'                 => 'baik',
                'status_pengelolaan'      => 'diserahkan_ke_masyarakat',
                'penanggung_jawab'        => 'Ketua RW 01 Kp. Sukamaju',
                'keterangan'              => 'Fasilitas sanitasi publik untuk 35 KK warga padat permukiman',
                'created_by'              => $adminId,
            ]
        );
    }
}
