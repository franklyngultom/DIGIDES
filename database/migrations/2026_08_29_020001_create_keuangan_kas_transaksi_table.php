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
        Schema::create('keuangan_kas_transaksi', function (Blueprint $table) {
            $table->id();
            $table->enum('buku_kas_type', ['umum', 'bank', 'kegiatan', 'pajak'])->default('umum')->index();
            $table->unsignedSmallInteger('tahun_anggaran')->index();
            $table->date('tanggal')->index();
            $table->string('nomor_bukti', 100);
            $table->string('kode_rekening', 30)->nullable();
            $table->text('uraian');
            $table->decimal('penerimaan', 15, 2)->default(0);
            $table->decimal('pengeluaran', 15, 2)->default(0);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('sumber_dana', 30)->default('DDS');
            $table->string('file_bukti_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_kas_transaksi');
    }
};
