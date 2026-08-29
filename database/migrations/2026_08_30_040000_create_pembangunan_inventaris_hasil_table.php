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
        Schema::create('pembangunan_inventaris_hasil', function (Blueprint $table) {
            $table->id();
            $table->year('tahun_anggaran')->index();
            $table->string('nomor_inventaris', 100)->unique();
            $table->string('nama_hasil_pembangunan', 255);
            $table->foreignId('pembangunan_proyek_id')->nullable()->constrained('pembangunan_proyek')->nullOnDelete();
            $table->enum('kategori_aset', [
                'jalan_jembatan',
                'bangunan_gedung',
                'irigasi_sanitasi',
                'sarana_air_bersih',
                'sarana_olahraga',
                'fasilitas_umum',
                'lainnya'
            ])->default('jalan_jembatan');
            $table->string('volume', 150);
            $table->string('lokasi', 255);
            $table->date('tanggal_serah_terima')->nullable();
            $table->string('sumber_dana', 50)->default('DDS');
            $table->decimal('nilai_aset', 15, 2)->default(0);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('status_pengelolaan', [
                'dikelola_desa',
                'diserahkan_ke_masyarakat',
                'dikelola_bumdes',
                'dihibahkan'
            ])->default('dikelola_desa');
            $table->string('penanggung_jawab', 150)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_hasil_path', 255)->nullable();
            $table->string('file_bast_path', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembangunan_inventaris_hasil');
    }
};
