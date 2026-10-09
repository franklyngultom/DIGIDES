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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->index();
            $table->string('no_kk', 16)->index();
            $table->string('nama_lengkap')->index();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir')->index();
            $table->enum('jenis_kelamin', ['L', 'P'])->index();
            $table->string('agama');
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('status_perkawinan', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'])->default('belum_kawin');
            $table->enum('status_dalam_keluarga', ['kepala_keluarga', 'istri', 'anak', 'famili_lain', 'lainnya'])->default('kepala_keluarga');
            $table->string('kewarganegaraan')->default('WNI');
            $table->string('golongan_darah', 3)->nullable();
            $table->text('alamat_lengkap');
            $table->string('rt', 3)->nullable()->index();
            $table->string('rw', 3)->nullable()->index();
            $table->string('dusun')->nullable()->index();
            $table->string('telepon')->nullable();
            $table->enum('sumber_data', ['prodeskel', 'manual', 'migrasi_legacy', 'online'])->default('manual');
            $table->enum('status_penduduk', ['tetap', 'sementara', 'pindah', 'meninggal'])->default('tetap')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
