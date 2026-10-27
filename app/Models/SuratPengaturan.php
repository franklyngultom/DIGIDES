<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SuratPengaturan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'surat_pengaturans';

    protected $fillable = [
        'no_hp_pelayanan',
        'email_pelayanan',
        'format_alamat_json',
        'ukuran_kertas',
        'margin_atas',
        'margin_kanan',
        'margin_bawah',
        'margin_kiri',
        'jenis_font',
        'ukuran_font',
        'tinggi_baris',
        'munculkan_pangkat',
        'munculkan_nip',
        'munculkan_qr',
        'aturan_penomoran',
        'format_nomor_umum',
        'inkremen_terakhir',
        'kop_baris_1',
        'kop_baris_2',
        'kop_baris_3',
        'kop_alamat',
        'kop_logo_path',
    ];

    protected function casts(): array
    {
        return [
            'format_alamat_json' => 'array',
            'margin_atas' => 'integer',
            'margin_kanan' => 'integer',
            'margin_bawah' => 'integer',
            'margin_kiri' => 'integer',
            'inkremen_terakhir' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('surat_pengaturan')
            ->setDescriptionForEvent(fn (string $eventName) => "Pengaturan persuratan {$eventName}");
    }

    /**
     * Get or create active singleton configuration.
     */
    public static function current(): self
    {
        $desa = DesaProfile::current();

        return static::firstOrCreate(
            ['id' => 1],
            [
                'no_hp_pelayanan' => $desa->telepon_desa ?? '081234567890',
                'email_pelayanan' => $desa->email_desa ?? 'pelayanan@desa.id',
                'format_alamat_json' => ['dusun', 'desa', 'kecamatan', 'kabupaten'],
                'ukuran_kertas' => 'A4 (210mm x 297mm)',
                'margin_atas' => 20,
                'margin_kanan' => 10,
                'margin_bawah' => 20,
                'margin_kiri' => 10,
                'jenis_font' => 'Arial',
                'ukuran_font' => '12pt',
                'tinggi_baris' => '1.4pt',
                'munculkan_pangkat' => 'Tidak',
                'munculkan_nip' => '-',
                'munculkan_qr' => 'Tidak',
                'aturan_penomoran' => 'Berlaku Secara Umum',
                'format_nomor_umum' => '[nnn]/[kode]/DIGIDES/[br]/[t]',
                'inkremen_terakhir' => 0,
                'kop_baris_1' => 'PEMERINTAH KABUPATEN ' . strtoupper($desa->kabupaten ?? 'ROKAN HILIR'),
                'kop_baris_2' => 'KECAMATAN ' . strtoupper($desa->kecamatan ?? 'BAGAN SINEMBAH'),
                'kop_baris_3' => 'DESA ' . strtoupper($desa->nama_desa ?? 'BALAM SEMPURNA'),
                'kop_alamat' => 'Alamat: ' . ($desa->alamat_kantor ?? 'Jl. Lintas Riau-Sumut Km. 21') . ', Kode Pos: ' . ($desa->kode_pos ?? '28992'),
                'kop_logo_path' => $desa->logo_path,
            ]
        );
    }

    /**
     * Helper to render full address string according to selected components.
     */
    public function formatAlamat(Penduduk|array|null $penduduk): string
    {
        if (! $penduduk) {
            return '';
        }

        $desa = DesaProfile::current();
        $components = $this->format_alamat_json ?? ['dusun', 'desa', 'kecamatan', 'kabupaten'];

        $parts = [];
        foreach ($components as $key) {
            $key = strtolower(trim($key));
            switch ($key) {
                case 'jalan':
                    $val = is_array($penduduk) ? ($penduduk['jalan'] ?? $penduduk['alamat_lengkap'] ?? null) : ($penduduk->jalan ?? $penduduk->alamat_lengkap ?? null);
                    if ($val) $parts[] = 'Jl. ' . $val;
                    break;
                case 'nomor':
                    $val = is_array($penduduk) ? ($penduduk['nomor'] ?? null) : ($penduduk->nomor ?? null);
                    if ($val) $parts[] = 'No. ' . $val;
                    break;
                case 'kampung':
                    $val = is_array($penduduk) ? ($penduduk['kampung'] ?? null) : ($penduduk->kampung ?? null);
                    if ($val) $parts[] = 'Kp. ' . $val;
                    break;
                case 'rt':
                    $val = is_array($penduduk) ? ($penduduk['rt'] ?? null) : ($penduduk->rt ?? null);
                    if ($val) $parts[] = 'RT ' . $val;
                    break;
                case 'rw':
                    $val = is_array($penduduk) ? ($penduduk['rw'] ?? null) : ($penduduk->rw ?? null);
                    if ($val) $parts[] = 'RW ' . $val;
                    break;
                case 'dusun':
                    $val = is_array($penduduk) ? ($penduduk['dusun'] ?? null) : ($penduduk->dusun ?? null);
                    $parts[] = 'Dusun ' . ($val ?: 'Inovasi');
                    break;
                case 'desa':
                    $parts[] = 'Desa ' . ($desa->nama_desa ?? 'Balam Sempurna');
                    break;
                case 'kecamatan':
                    $parts[] = 'Kecamatan ' . ($desa->kecamatan ?? 'Bagan Sinembah');
                    break;
                case 'kabupaten':
                    $parts[] = 'Kabupaten ' . ($desa->kabupaten ?? 'Rokan Hilir');
                    break;
                case 'provinsi':
                    $parts[] = 'Provinsi ' . ($desa->provinsi ?? 'Riau');
                    break;
            }
        }

        return implode(' ', $parts);
    }
}
