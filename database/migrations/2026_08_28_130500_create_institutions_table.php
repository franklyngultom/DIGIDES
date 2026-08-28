<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lembaga');
            $table->string('singkatan', 20)->nullable();
            $table->string('slug')->unique();
            $table->enum('kategori', ['pemerintahan', 'kemasyarakatan', 'ekonomi', 'kesehatan', 'lainnya'])->nullable();
            $table->string('nomor_sk_pendirian')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('alamat_sekretariat')->nullable();
            $table->string('logo_path')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutions');
    }
};
