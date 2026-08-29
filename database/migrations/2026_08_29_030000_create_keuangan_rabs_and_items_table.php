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
        Schema::create('keuangan_rabs', function (Blueprint $table) {
            $table->id();
            $table->year('tahun_anggaran')->index();
            $table->string('nomor_rab', 100)->unique();
            $table->string('bidang', 255);
            $table->string('sub_bidang', 255)->nullable();
            $table->string('nama_kegiatan', 255);
            $table->string('lokasi', 255);
            $table->string('waktu_pelaksanaan', 100);
            $table->string('sumber_dana', 50)->default('DDS');
            $table->string('nama_ppkd', 150)->nullable();
            $table->string('jabatan_ppkd', 150)->nullable();
            $table->decimal('total_anggaran', 15, 2)->default(0);
            $table->enum('status', ['draft', 'disetujui', 'direalisasikan'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->string('file_lampiran_path', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('keuangan_rab_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keuangan_rab_id')->constrained('keuangan_rabs')->cascadeOnDelete();
            $table->string('kode_rekening', 50)->nullable();
            $table->enum('kategori', ['bahan_material', 'upah_tenaga_kerja', 'sewa_alat', 'operasional'])->default('bahan_material');
            $table->string('uraian', 255);
            $table->decimal('volume', 12, 2)->default(1);
            $table->string('satuan', 50)->default('Unit');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('keterangan', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_rab_items');
        Schema::dropIfExists('keuangan_rabs');
    }
};
