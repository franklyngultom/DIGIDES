<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembangunan_proyek', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_anggaran')->index();
            $table->string('nama_kegiatan');
            $table->string('lokasi');
            $table->string('volume');
            $table->decimal('anggaran_biaya', 15, 2)->default(0);
            $table->decimal('realisasi_biaya', 15, 2)->default(0);
            $table->string('sumber_dana', 50)->default('Dana Desa (DDS)');
            $table->string('pelaksana_tpk');
            $table->enum('status_progres', ['perencanaan', 'proses', 'selesai', 'tertunda'])->default('perencanaan')->index();
            $table->unsignedTinyInteger('persentase_selesai')->default(0);
            $table->string('foto_titik_nol')->nullable();
            $table->string('foto_50_persen')->nullable();
            $table->string('foto_100_persen')->nullable();
            $table->string('file_rab_path')->nullable();
            $table->text('manfaat_warga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembangunan_proyek');
    }
};
