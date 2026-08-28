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
        Schema::create('buku_ekspedisis', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('nomor_urut')->index();
            $table->unsignedSmallInteger('tahun')->index();
            $table->date('tanggal_pengiriman')->index();
            $table->string('nomor_surat')->index();
            $table->date('tanggal_surat');
            $table->text('perihal');
            $table->string('tujuan_penerima');
            $table->string('petugas_pengirim')->nullable();
            $table->foreignId('surat_arsip_id')->nullable()->constrained('surat_arsips')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_ekspedisis');
    }
};
