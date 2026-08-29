<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_lembaran_desa', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->index();
            $table->enum('jenis', ['lembaran_desa', 'berita_desa'])->default('lembaran_desa');
            $table->string('nomor_seri');
            $table->date('tanggal_diundangkan');
            $table->text('judul');
            $table->text('isi_singkat')->nullable();
            $table->string('file_pdf_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_lembaran_desa');
    }
};
