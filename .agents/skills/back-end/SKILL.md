---
name: back-end
description: Panduan arsitektur backend Laravel 11/13 DIGIDES v2, mencakup Spatie RBAC, Dynamic Institution Engine, NIK Duplicate Scanner, PDF Generator & Ekspedisi Sync, dan Activity Logging.
---

# Back-End Development Skill (DIGIDES Laravel Architecture)

Skill ini memberikan panduan teknis mendalam dan pola arsitektur backend untuk mengimplementasikan seluruh kebutuhan fungsional DIGIDES v2 secara bersih, aman, dan modular.

---

## 1. Pola Desain & Standar Struktur Proyek

```text
app/
├── Actions/
│   ├── Kependudukan/
│   │   └── ScanDuplicateNikAction.php
│   └── Persuratan/
│       ├── GenerateNomorSuratAction.php
│       └── RenderSuratPdfAction.php
├── Events/
│   └── SuratDiterbitkanEvent.php
├── Listeners/
│   ├── SyncToBukuEkspedisiListener.php
│   └── SyncToBukuAgendaListener.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/ (UserController, DesaProfileController, BackupController)
│   │   ├── Kependudukan/ (PendudukController, MutasiController, BerkasController)
│   │   ├── Persuratan/ (PelayananSuratController, ArsipSuratController)
│   │   ├── Kelembagaan/ (InstitutionMasterController, InstitutionWorkspaceController)
│   │   ├── Administrasi/ (BukuPeraturanController, BukuInventarisController, BukuTanahController)
│   │   └── Keuangan/ (ApbdesController, BukuKasController, BankDesaController)
│   └── Requests/ (Form Requests terisolasi per operasi)
├── Models/
│   ├── User.php
│   ├── DesaProfile.php
│   ├── Penduduk.php
│   ├── PendudukDocument.php
│   ├── SuratTemplate.php
│   ├── SuratArsip.php
│   ├── BukuEkspedisi.php
│   ├── BukuAgenda.php
│   ├── Institution.php
│   ├── InstitutionMember.php
│   ├── InstitutionDecision.php
│   ├── InstitutionActivity.php
│   ├── InstitutionAgenda.php
│   └── Aparatur.php
└── Services/
```

---

## 2. Dynamic Institution Engine Implementation

### Schema Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'nama_lembaga', 'singkatan', 'slug', 'kategori',
        'nomor_sk_pendirian', 'tanggal_sk', 'deskripsi',
        'alamat_sekretariat', 'logo_path', 'urutan', 'is_active'
    ];

    public function members(): HasMany
    {
        return $this->hasMany(InstitutionMember::class);
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(InstitutionDecision::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(InstitutionActivity::class);
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(InstitutionAgenda::class);
    }
}
```

---

## 3. Algoritma Pemindai NIK Duplikat (Duplicate NIK Scanner)

```php
namespace App\Actions\Kependudukan;

use App\Models\Penduduk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ScanDuplicateNikAction
{
    public function execute(): Collection
    {
        // 1. Ambil NIK yang memiliki frekuensi > 1
        $duplicateNiks = Penduduk::select('nik', DB::raw('COUNT(*) as total'))
            ->groupBy('nik')
            ->having('total', '>', 1)
            ->pluck('nik');

        // 2. Ambil detail lengkap warga yang terduplikasi
        return Penduduk::whereIn('nik', $duplicateNiks)
            ->orderBy('nik')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
```

---

## 4. Persuratan Walk-In & Auto-dispatching ke Buku Ekspedisi

### Event & Listener Pattern
```php
// app/Events/SuratDiterbitkanEvent.php
namespace App\Events;

use App\Models\SuratArsip;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuratDiterbitkanEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public SuratArsip $suratArsip) {}
}

// app/Listeners/SyncToBukuEkspedisiListener.php
namespace App\Listeners;

use App\Events\SuratDiterbitkanEvent;
use App\Models\BukuEkspedisi;
use Carbon\Carbon;

class SyncToBukuEkspedisiListener
{
    public function handle(SuratDiterbitkanEvent $event): void
    {
        $surat = $event->suratArsip;
        $tahun = Carbon::parse($surat->tanggal_terbit)->year;

        $lastNoUrut = BukuEkspedisi::where('tahun', $tahun)->max('nomor_urut') ?? 0;

        BukuEkspedisi::create([
            'nomor_urut' => $lastNoUrut + 1,
            'tahun' => $tahun,
            'tanggal_pengiriman' => $surat->tanggal_terbit,
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => $surat->tanggal_terbit,
            'perihal' => $surat->template->nama_surat . ' - ' . $surat->penduduk->nama_lengkap,
            'tujuan_penerima' => $surat->payload_data['tujuan'] ?? 'Pemohon Langsung',
            'petugas_pengirim' => $surat->user->name,
            'surat_arsip_id' => $surat->id,
            'catatan' => 'Diterbitkan otomatis melalui Pelayanan Walk-In Desk'
        ]);
    }
}
```
