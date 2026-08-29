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
        Schema::table('keuangan_kas_transaksi', function (Blueprint $table) {
            $table->string('kategori_kas', 20)->default('tunai')->after('buku_kas_type')->index();
            $table->string('jenis_pembantu', 20)->nullable()->after('kategori_kas')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_kas_transaksi', function (Blueprint $table) {
            $table->dropColumn(['kategori_kas', 'jenis_pembantu']);
        });
    }
};
