<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_inventaris_aset', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_pengadaan')->index();
            $table->string('jenis_barang');
            $table->string('kode_barang')->nullable();
            $table->text('identitas_barang');
            $table->enum('asal_usul', [
                'apbdes', 'bantuan_pemerintah', 'bantuan_provinsi',
                'bantuan_kabupaten', 'hibah', 'lainnya',
            ])->default('apbdes');
            $table->decimal('harga_perolehan', 15, 2)->default(0);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('lokasi_penempatan');
            $table->string('foto_barang_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_inventaris_aset');
    }
};
