<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_anggaran_desa', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->index();
            $table->enum('jenis_dokumen', ['apbdes', 'apbdes_perubahan'])->default('apbdes');
            $table->string('nomor_perdes');
            $table->date('tanggal_penetapan');
            $table->decimal('total_pendapatan', 18, 2)->default(0);
            $table->decimal('total_belanja', 18, 2)->default(0);
            $table->decimal('total_pembiayaan', 18, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('file_pdf_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_anggaran_desa');
    }
};
