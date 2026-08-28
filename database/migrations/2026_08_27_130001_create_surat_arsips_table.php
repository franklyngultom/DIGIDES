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
        Schema::create('surat_arsips', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique()->index();
            $table->foreignId('surat_template_id')->constrained('surat_templates')->onDelete('cascade');
            $table->foreignId('penduduk_id')->constrained('penduduks')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('keperluan')->nullable();
            $table->json('payload_data')->nullable(); // Snapshot of resident info and form variables
            $table->string('file_pdf_path')->nullable();
            $table->date('tanggal_terbit')->index();
            $table->enum('status', ['draft', 'terbit', 'dibatalkan'])->default('terbit')->index();
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_arsips');
    }
};
