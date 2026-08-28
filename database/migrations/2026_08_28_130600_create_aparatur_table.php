<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aparatur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->unique()->constrained('penduduk')->onDelete('cascade');
            $table->string('nip', 30)->nullable();
            $table->string('jabatan');
            $table->string('qr_token', 64)->unique()->index();
            $table->time('jam_masuk_standar')->default('08:00:00');
            $table->time('jam_pulang_standar')->default('16:00:00');
            $table->integer('toleransi_terlambat_menit')->default(15);
            $table->enum('status_kepegawaian', ['pns', 'pppk', 'perangkat_desa', 'honorer']);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aparatur');
    }
};
