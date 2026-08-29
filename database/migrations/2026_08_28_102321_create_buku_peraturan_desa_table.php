<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_peraturan_desa', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->index();
            $table->enum('jenis_peraturan', ['perdes', 'perkades', 'peraturan_bersama']);
            $table->string('nomor_ditetapkan');
            $table->date('tanggal_ditetapkan');
            $table->text('tentang');
            $table->text('uraian_singkat')->nullable();
            $table->string('nomor_kesepakatan_bpd')->nullable();
            $table->string('nomor_diundangkan')->nullable();
            $table->date('tanggal_diundangkan')->nullable();
            $table->string('file_pdf_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_peraturan_desa');
    }
};
