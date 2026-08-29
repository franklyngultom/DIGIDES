<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_keputusan_kades', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->index();
            $table->string('nomor_keputusan');
            $table->date('tanggal_keputusan');
            $table->text('tentang');
            $table->text('uraian_singkat')->nullable();
            $table->string('nomor_dilaporkan')->nullable();
            $table->string('file_pdf_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_keputusan_kades');
    }
};
