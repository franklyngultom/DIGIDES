<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_tanah_desa', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_tanah', ['tanah_kas_desa', 'tanah_bengkok', 'tanah_warga']);
            $table->string('nomor_sertifikat_letter_c');
            $table->string('nama_pemilik_asal');
            $table->decimal('luas_m2', 10, 2);
            $table->string('kelas_tanah')->nullable();
            $table->string('lokasi_blok');
            $table->string('peruntukan_saat_ini');
            $table->text('patok_tanda_batas')->nullable();
            $table->string('file_warkah_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_tanah_desa');
    }
};
