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
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan', 30)->unique()->index(); // e.g. PGJ-2026-000001
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('citizen_profile_id')->nullable()->constrained('citizen_profiles')->nullOnDelete();
            $table->foreignId('surat_template_id')->constrained('surat_templates')->onDelete('restrict');

            // Applicant snapshot (at time of submission, denormalized for historical record)
            $table->string('pemohon_nama');
            $table->string('pemohon_nik', 16);
            $table->string('pemohon_no_kk', 16)->nullable();
            $table->text('pemohon_alamat')->nullable();
            $table->string('pemohon_phone', 20)->nullable();

            // Form data filled by the applicant
            $table->json('form_data_json')->nullable(); // Dynamic fields per template

            // Supporting documents uploaded
            $table->json('dokumen_path_json')->nullable(); // Array of stored file paths

            // Status lifecycle
            $table->enum('status', [
                'menunggu',      // Submitted, waiting for officer review
                'diproses',      // Officer is processing
                'perlu_perbaikan', // Officer requests corrections
                'disetujui',     // Approved, letter being generated
                'selesai',       // Completed, letter issued
                'ditolak',       // Rejected
            ])->default('menunggu')->index();

            $table->text('catatan_petugas')->nullable(); // Internal note from officer
            $table->text('pesan_ke_pemohon')->nullable(); // Message visible to applicant

            // Officer who processes
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();

            // Link to generated letter (if issued)
            $table->foreignId('surat_arsip_id')->nullable()->constrained('surat_arsips')->nullOnDelete();

            $table->timestamps();
        });

        // Status change history log
        Schema::create('pengajuan_surat_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surat')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_sebelum', 30)->nullable();
            $table->string('status_sesudah', 30);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_logs');
        Schema::dropIfExists('pengajuan_surat');
    }
};
