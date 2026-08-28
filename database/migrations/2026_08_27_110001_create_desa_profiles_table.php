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
        Schema::create('desa_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('kode_desa')->nullable();
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos')->nullable();
            $table->text('alamat_kantor');
            $table->string('email_desa')->nullable();
            $table->string('telepon_desa')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('nama_kades');
            $table->string('nip_kades')->nullable();
            $table->string('nik_kades')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa_profiles');
    }
};
