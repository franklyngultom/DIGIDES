<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuInventarisAset extends Model
{
    protected $table = 'buku_inventaris_aset';

    protected $fillable = [
        'tahun_pengadaan', 'jenis_barang', 'kode_barang', 'identitas_barang',
        'asal_usul', 'harga_perolehan', 'kondisi', 'lokasi_penempatan',
        'foto_barang_path', 'created_by',
    ];

    protected $casts = [
        'harga_perolehan' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
