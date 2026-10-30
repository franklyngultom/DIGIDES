<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SuratTemplate extends Model
{
    use HasFactory;

    protected $table = 'surat_templates';

    protected $fillable = [
        'kode_surat',
        'slug',
        'nama_surat',
        'penomoran_format',
        'template_blade',
        'schema_fields_json',
        'persyaratan_json',
        'dokumen_wajib_json',
        'estimasi_proses',
        'biaya',
        'icon',
        'deskripsi',
        'is_active',
        'is_online_available',
        'custom_print_settings_json',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'schema_fields_json' => 'array',
            'persyaratan_json' => 'array',
            'dokumen_wajib_json' => 'array',
            'custom_print_settings_json' => 'array',
            'is_active' => 'boolean',
            'is_online_available' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->nama_surat) . '-' . Str::lower(Str::random(4));
            }
        });
    }

    /**
     * Relations.
     */
    public function arsips(): HasMany
    {
        return $this->hasMany(SuratArsip::class, 'surat_template_id');
    }

    /**
     * Active scope.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Online available scope.
     */
    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('is_online_available', true);
    }

    /**
     * Pastikan template surat yang paling sering diajukan masyarakat tersedia dan aktif untuk pengajuan online.
     */
    public static function ensureDefaultTemplates(): void
    {
        $defaults = [
            [
                'kode_surat' => 'SKCK',
                'slug' => 'surat-pengantar-skck',
                'nama_surat' => 'Surat Pengantar SKCK (Catatan Kepolisian)',
                'penomoran_format' => '300/{no}/SKCK/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat pengantar resmi dari Pemerintah Desa untuk pengurusan SKCK di Polsek/Polres setempat.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP Elektronik Pemohon',
                    'Fotokopi Kartu Keluarga (KK)',
                    'Fotokopi Akta Kelahiran / Ijazah Terakhir',
                    'Pas Foto 4x6 Latar Merah (untuk dibawa ke Polsek/Polres)',
                ],
                'schema_fields_json' => [
                    ['name' => 'tujuan_skck', 'label' => 'Keperluan / Tujuan Pembuatan SKCK', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Melamar Pekerjaan / Pendaftaran CPNS / Pendidikan'],
                    ['name' => 'polsek_polres_tujuan', 'label' => 'Polsek / Polres Tujuan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Polsek Bangko Pusako / Polres Rokan Hilir'],
                    ['name' => 'catatan_kriminal', 'label' => 'Keterangan Kelakuan Baik', 'type' => 'select', 'required' => true, 'options' => ['Berkelakuan Baik & Tidak Pernah Terlibat Tindak Pidana']],
                ],
            ],
            [
                'kode_surat' => 'SKU',
                'slug' => 'surat-keterangan-usaha',
                'nama_surat' => 'Surat Keterangan Usaha (SKU)',
                'penomoran_format' => '510/{no}/SKU/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat keterangan resmi kepemilikan usaha mikro/kecil warga untuk pengajuan KUR, perizinan NIB, atau kemitraan.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP Elektronik Pemohon',
                    'Fotokopi Kartu Keluarga (KK)',
                    'Foto Tempat / Kegiatan Usaha',
                ],
                'schema_fields_json' => [
                    ['name' => 'nama_usaha', 'label' => 'Nama / Merk Usaha', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Toko Sembako Berkah / Warung Makan Sederhana'],
                    ['name' => 'jenis_usaha', 'label' => 'Bidang / Jenis Usaha', 'type' => 'select', 'required' => true, 'options' => ['Perdagangan / Warung / Toko', 'Kuliner / Makanan & Minuman', 'Pertanian / Perkebunan', 'Peternakan / Perikanan', 'Jasa / Bengkel / Jahit', 'Industri Rumah Tangga / Kerajinan', 'Lainnya']],
                    ['name' => 'lama_usaha', 'label' => 'Lama Usaha Berjalan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: 3 Tahun (Sejak 2023)'],
                    ['name' => 'alamat_usaha', 'label' => 'Alamat Lengkap Lokasi Usaha', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Dusun I RT 002 RW 001 Desa Balam Sempurna'],
                    ['name' => 'perkiraan_omzet_bulanan', 'label' => 'Perkiraan Penghasilan / Omzet per Bulan', 'type' => 'text', 'required' => false, 'placeholder' => 'Contoh: Rp 5.000.000 / Bulan'],
                ],
            ],
            [
                'kode_surat' => 'SKD',
                'slug' => 'surat-keterangan-domisili',
                'nama_surat' => 'Surat Keterangan Domisili (SKD)',
                'penomoran_format' => '470/{no}/SKD/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat bukti tempat tinggal atau domisili warga yang menetap di wilayah desa untuk keperluan perbankan, pekerjaan, atau sekolah.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP Elektronik / Identitas Diri',
                    'Fotokopi Kartu Keluarga (KK)',
                    'Surat Pengantar RT / RW Setempat',
                ],
                'schema_fields_json' => [
                    ['name' => 'status_tempat_tinggal', 'label' => 'Status Tempat Tinggal Saat Ini', 'type' => 'select', 'required' => true, 'options' => ['Milik Sendiri', 'Rumah Orang Tua / Keluarga', 'Kontrak / Sewa', 'Mess / Asrama']],
                    ['name' => 'menetap_sejak', 'label' => 'Menetap / Berdomisili Sejak Tahun', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Tahun 2015 / Sejak Lahir'],
                    ['name' => 'instansi_tujuan', 'label' => 'Ditujukan Kepada Instansi / Perusahaan', 'type' => 'text', 'required' => false, 'placeholder' => 'Contoh: Bank BRI / PT. Pertamina / Sekolah'],
                ],
            ],
            [
                'kode_surat' => 'SKTM',
                'slug' => 'surat-keterangan-tidak-mampu',
                'nama_surat' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'penomoran_format' => '400/{no}/SKTM/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat keterangan bagi keluarga kurang mampu untuk pengajuan beasiswa KIP/sekolah, keringanan biaya rumah sakit, atau bantuan sosial.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP Elektronik Pemohon',
                    'Fotokopi Kartu Keluarga (KK)',
                    'Surat Pengantar RT / RW',
                ],
                'schema_fields_json' => [
                    ['name' => 'peruntukan_sktm', 'label' => 'Peruntukan Pengajuan SKTM', 'type' => 'select', 'required' => true, 'options' => ['Beasiswa Pendidikan / Sekolah / Kuliah (KIP)', 'Keringanan Biaya Berobat / Rumah Sakit / BPJS', 'Pengajuan Bantuan Sosial Pemerintah', 'Lainnya']],
                    ['name' => 'nama_instansi_tujuan', 'label' => 'Nama Sekolah / Kampus / Rumah Sakit Tujuan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: SMAN 1 Bangko Pusako / RSUD Pratomo'],
                    ['name' => 'nama_terkait', 'label' => 'Nama Anak / Pasien (Jika diajukan untuk anggota keluarga)', 'type' => 'text', 'required' => false, 'placeholder' => 'Kosongkan jika untuk diri sendiri'],
                    ['name' => 'penghasilan_rata_rata', 'label' => 'Rata-rata Penghasilan Keluarga per Bulan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Rp 1.200.000'],
                    ['name' => 'jumlah_tanggungan_keluarga', 'label' => 'Jumlah Tanggungan Keluarga', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: 4 Orang'],
                ],
            ],
            [
                'kode_surat' => 'SKBM',
                'slug' => 'surat-keterangan-belum-menikah',
                'nama_surat' => 'Surat Keterangan Belum Pernah Menikah',
                'penomoran_format' => '474.2/{no}/SKBM/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat keterangan status lajang (Jejaka/Perawan) untuk melamar pekerjaan, pendidikan, atau administrasi pernikahan.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP Elektronik Pemohon',
                    'Fotokopi Kartu Keluarga (KK)',
                    'Surat Pengantar RT / RW',
                ],
                'schema_fields_json' => [
                    ['name' => 'status_saat_ini', 'label' => 'Status Perkawinan Saat Ini', 'type' => 'select', 'required' => true, 'options' => ['Belum Kawin (Jejaka)', 'Belum Kawin (Perawan)']],
                    ['name' => 'instansi_tujuan', 'label' => 'Instansi / Keperluan Tujuan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Persyaratan Melamar Kerja / Administrasi KUA'],
                ],
            ],
            [
                'kode_surat' => 'SKPOT',
                'slug' => 'surat-keterangan-penghasilan-orang-tua',
                'nama_surat' => 'Surat Keterangan Penghasilan Orang Tua',
                'penomoran_format' => '422/{no}/SKPOT/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat keterangan rincian penghasilan orang tua/wali untuk pendaftaran kuliah (UKT), KIP Kuliah, atau beasiswa sekolah.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP Orang Tua / Wali',
                    'Fotokopi Kartu Keluarga (KK)',
                    'Kartu Pelajar / Kartu Mahasiswa Anak',
                ],
                'schema_fields_json' => [
                    ['name' => 'nama_anak', 'label' => 'Nama Lengkap Anak (Siswa / Mahasiswa)', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan nama lengkap anak sesuai KK'],
                    ['name' => 'nik_anak', 'label' => 'NIK Anak', 'type' => 'text', 'required' => false, 'placeholder' => '16 digit NIK anak'],
                    ['name' => 'sekolah_kampus_tujuan', 'label' => 'Nama Sekolah / Universitas Tujuan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Universitas Riau / UIN Suska'],
                    ['name' => 'pekerjaan_orang_tua', 'label' => 'Pekerjaan Pokok Orang Tua / Wali', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Petani / Buruh Harian Lepas / Pedagang'],
                    ['name' => 'penghasilan_per_bulan', 'label' => 'Rata-rata Penghasilan Orang Tua per Bulan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Rp 1.800.000 (Satu Juta Delapan Ratus Ribu Rupiah)'],
                    ['name' => 'jumlah_tanggungan', 'label' => 'Jumlah Tanggungan Anak / Keluarga', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: 3 Orang Anak'],
                ],
            ],
            [
                'kode_surat' => 'SKP-NIKAH',
                'slug' => 'surat-pengantar-nikah',
                'nama_surat' => 'Surat Pengantar Nikah (Model N1 - N4)',
                'penomoran_format' => '474.2/{no}/SKPN/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat pengantar numpang nikah atau pendaftaran pernikahan resmi menuju Kantor Urusan Agama (KUA) Kecamatan.',
                'estimasi_proses' => '1 - 2 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP & KK Calon Pengantin',
                    'Fotokopi KTP Kedua Orang Tua Kandung',
                    'Fotokopi Akta Kelahiran & Ijazah Terakhir',
                    'Pas Foto Latar Biru Ukuran 2x3 dan 4x6',
                ],
                'schema_fields_json' => [
                    ['name' => 'status_perkawinan_pemohon', 'label' => 'Status Perkawinan Pemohon', 'type' => 'select', 'required' => true, 'options' => ['Jejaka (Belum Pernah Menikah)', 'Perawan (Belum Pernah Menikah)', 'Duda (Cerai Hidup / Cerai Mati)', 'Janda (Cerai Hidup / Cerai Mati)']],
                    ['name' => 'nama_calon_pasangan', 'label' => 'Nama Lengkap Calon Suami / Istri', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama lengkap calon pasangan sesuai KTP'],
                    ['name' => 'nik_calon_pasangan', 'label' => 'NIK Calon Pasangan', 'type' => 'text', 'required' => true, 'placeholder' => '16 digit NIK calon pasangan'],
                    ['name' => 'alamat_calon_pasangan', 'label' => 'Alamat Lengkap Calon Pasangan', 'type' => 'text', 'required' => true, 'placeholder' => 'Alamat desa/kelurahan, kecamatan, dan kabupaten calon pasangan'],
                    ['name' => 'kua_tujuan', 'label' => 'KUA Kecamatan Tempat Akad Nikah', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: KUA Kecamatan Bangko Pusako'],
                    ['name' => 'rencana_tanggal_nikah', 'label' => 'Rencana Tanggal Pernikahan', 'type' => 'date', 'required' => false, 'placeholder' => ''],
                ],
            ],
            [
                'kode_surat' => 'SKL',
                'slug' => 'surat-keterangan-kelahiran',
                'nama_surat' => 'Surat Keterangan Kelahiran',
                'penomoran_format' => '474.1/{no}/SKL/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat pengantar pencatatan kelahiran anak baru lahir untuk penambahan anggota Kartu Keluarga (KK) dan pembuatan Akta Kelahiran.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Surat Keterangan Kelahiran dari Bidan / Puskesmas / Rumah Sakit',
                    'Fotokopi KTP Kedua Orang Tua',
                    'Fotokopi Kartu Keluarga (KK) Asli',
                    'Fotokopi Buku Nikah / Akta Perkawinan Orang Tua',
                ],
                'schema_fields_json' => [
                    ['name' => 'nama_bayi', 'label' => 'Nama Lengkap Bayi / Anak', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan nama lengkap bayi yang diberi'],
                    ['name' => 'jenis_kelamin_bayi', 'label' => 'Jenis Kelamin Bayi', 'type' => 'select', 'required' => true, 'options' => ['Laki-laki', 'Perempuan']],
                    ['name' => 'tanggal_lahir_bayi', 'label' => 'Tanggal Kelahiran Bayi', 'type' => 'date', 'required' => true, 'placeholder' => ''],
                    ['name' => 'tempat_kelahiran', 'label' => 'Tempat Kelahiran (RS / Puskesmas / Bidan)', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Puskesmas Bangko Jaya'],
                    ['name' => 'anak_ke', 'label' => 'Anak Ke-', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: 1 (Pertama) / 2 (Kedua)'],
                    ['name' => 'nama_ayah_kandung', 'label' => 'Nama Lengkap Ayah Kandung', 'type' => 'text', 'required' => true, 'placeholder' => 'Sesuai KTP / KK'],
                    ['name' => 'nama_ibu_kandung', 'label' => 'Nama Lengkap Ibu Kandung', 'type' => 'text', 'required' => true, 'placeholder' => 'Sesuai KTP / KK'],
                ],
            ],
            [
                'kode_surat' => 'SKM',
                'slug' => 'surat-keterangan-kematian',
                'nama_surat' => 'Surat Keterangan Kematian',
                'penomoran_format' => '474.3/{no}/SKM/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat keterangan pelaporan warga meninggal dunia untuk pengurusan Akta Kematian Disdukcapil, klaim santunan, atau perbankan.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP & KK Almarhum / Almarhumah',
                    'Fotokopi KTP Pelapor / Ahli Waris',
                    'Surat Keterangan Kematian dari Rumah Sakit (jika meninggal di RS)',
                ],
                'schema_fields_json' => [
                    ['name' => 'nama_almarhum', 'label' => 'Nama Lengkap Almarhum / Almarhumah', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama lengkap sesuai KTP/KK'],
                    ['name' => 'nik_almarhum', 'label' => 'NIK Almarhum / Almarhumah', 'type' => 'text', 'required' => true, 'placeholder' => '16 digit NIK almarhum'],
                    ['name' => 'tanggal_meninggal', 'label' => 'Tanggal Meninggal Dunia', 'type' => 'date', 'required' => true, 'placeholder' => ''],
                    ['name' => 'tempat_meninggal', 'label' => 'Tempat Meninggal Dunia', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Rumah Kediaman / RSUD'],
                    ['name' => 'penyebab_kematian', 'label' => 'Penyebab Meninggal Dunia', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Sakit / Usia Lanjut'],
                    ['name' => 'hubungan_pelapor', 'label' => 'Hubungan Pemohon dengan Almarhum', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Anak Kandung / Istri / Suami'],
                ],
            ],
            [
                'kode_surat' => 'SKH',
                'slug' => 'surat-pengantar-kehilangan',
                'nama_surat' => 'Surat Pengantar Kehilangan Dokumen / Barang',
                'penomoran_format' => '300/{no}/SKH/{romawi}/{tahun}',
                'template_blade' => 'default',
                'deskripsi' => 'Surat pengantar dari desa untuk pembuatan Laporan Kehilangan di Kepolisian (Polsek/Polres) atas KTP, KK, Buku Tabungan, atau dokumen penting.',
                'estimasi_proses' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'persyaratan_json' => [
                    'Fotokopi KTP / Kartu Keluarga Pemohon',
                    'Data Nomor / Rincian Dokumen yang Hilang (jika ada)',
                ],
                'schema_fields_json' => [
                    ['name' => 'dokumen_yang_hilang', 'label' => 'Jenis Dokumen / Barang yang Hilang', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: KTP-el / Kartu Keluarga / Buku Tabungan BRI / ATM / Ijazah'],
                    ['name' => 'nomor_identitas_dokumen', 'label' => 'Nomor Dokumen / Atas Nama', 'type' => 'text', 'required' => false, 'placeholder' => 'Contoh: No. Rekening / Nomor Seri Ijazah'],
                    ['name' => 'waktu_kejadian_hilang', 'label' => 'Perkiraan Tanggal / Waktu Kehilangan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Senin, 20 Oktober 2026 sekitar pukul 10.00 WIB'],
                    ['name' => 'lokasi_kejadian_hilang', 'label' => 'Perkiraan Lokasi Kehilangan', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: Dalam perjalanan sekitar Pasar Balam Sempurna'],
                ],
            ],
        ];

        foreach ($defaults as $tpl) {
            $existing = static::where('kode_surat', $tpl['kode_surat'])->first();
            if (! $existing) {
                static::create(array_merge($tpl, [
                    'is_active' => true,
                    'is_online_available' => true,
                ]));
            } else {
                $updates = [];
                if (! $existing->is_online_available) {
                    $updates['is_online_available'] = true;
                }
                if (empty($existing->schema_fields_json)) {
                    $updates['schema_fields_json'] = $tpl['schema_fields_json'];
                }
                if (empty($existing->persyaratan_json)) {
                    $updates['persyaratan_json'] = $tpl['persyaratan_json'];
                }
                if (empty($existing->deskripsi)) {
                    $updates['deskripsi'] = $tpl['deskripsi'];
                }
                if (! empty($updates)) {
                    $existing->update($updates);
                }
            }
        }
    }
}
