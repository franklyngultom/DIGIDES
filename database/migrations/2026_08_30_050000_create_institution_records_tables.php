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
        // 1. Anggota Lembaga
        Schema::create('institution_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduks')->nullOnDelete();
            $table->string('nama_lengkap');
            $table->string('nik', 20)->nullable();
            $table->string('jabatan');
            $table->string('nomor_sk_pengangkatan')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->integer('periode_mulai')->nullable();
            $table->integer('periode_selesai')->nullable();
            $table->string('kontak', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['institution_id', 'status_aktif']);
        });

        // 2. Keputusan Lembaga
        Schema::create('institution_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->string('nomor_keputusan');
            $table->date('tanggal_keputusan');
            $table->string('tentang');
            $table->text('uraian_singkat')->nullable();
            $table->string('dokumen_path')->nullable();
            $table->integer('tahun')->index();
            $table->timestamps();

            $table->index(['institution_id', 'tahun']);
        });

        // 3. Kegiatan Lembaga
        Schema::create('institution_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->string('nama_kegiatan');
            $table->date('tanggal_kegiatan');
            $table->string('lokasi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->string('sumber_dana')->nullable();
            $table->text('output_hasil')->nullable();
            $table->string('foto_dokumentasi')->nullable();
            $table->integer('tahun')->index();
            $table->timestamps();

            $table->index(['institution_id', 'tahun']);
        });

        // 4. Agenda Lembaga
        Schema::create('institution_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->date('tanggal_agenda');
            $table->string('waktu', 20)->nullable();
            $table->string('nama_agenda');
            $table->string('tempat')->nullable();
            $table->string('peserta')->nullable();
            $table->text('pembahasan')->nullable();
            $table->enum('status', ['rencana', 'berlangsung', 'selesai', 'dibatalkan'])->default('rencana');
            $table->integer('tahun')->index();
            $table->timestamps();

            $table->index(['institution_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_agendas');
        Schema::dropIfExists('institution_activities');
        Schema::dropIfExists('institution_decisions');
        Schema::dropIfExists('institution_members');
    }
};
