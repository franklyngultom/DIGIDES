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
        Schema::create('keuangan_apbdes', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun_anggaran')->index();
            $table->string('kode_rekening', 30)->index();
            $table->enum('jenis', ['pendapatan', 'belanja', 'pembiayaan'])->index();
            $table->string('bidang')->nullable(); // Bidang 1 - 5 untuk belanja, atau kelompok pendapatan
            $table->text('uraian');
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->enum('sumber_dana', ['DDS', 'ADD', 'PBH', 'PAD', 'DLL'])->default('DDS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_apbdes');
    }
};
