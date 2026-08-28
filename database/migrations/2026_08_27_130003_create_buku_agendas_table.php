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
        Schema::create('buku_agendas', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['masuk', 'keluar'])->default('keluar')->index();
            $table->unsignedInteger('nomor_urut')->index();
            $table->unsignedSmallInteger('tahun')->index();
            $table->string('nomor_surat')->index();
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima_dikirim')->index();
            $table->string('asal_tujuan'); // Asal untuk surat masuk, Tujuan untuk surat keluar
            $table->text('perihal');
            $table->foreignId('surat_arsip_id')->nullable()->constrained('surat_arsips')->nullOnDelete();
            $table->string('file_surat_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_agendas');
    }
};
