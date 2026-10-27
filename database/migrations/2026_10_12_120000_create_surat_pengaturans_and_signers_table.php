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
        // 1. Tabel Pengaturan Surat Utama
        Schema::create('surat_pengaturans', function (Blueprint $table) {
            $table->id();
            
            // Pengaturan Kontak & Alamat (Tab 1)
            $table->string('no_hp_pelayanan')->nullable();
            $table->string('email_pelayanan')->nullable();
            $table->json('format_alamat_json')->nullable();

            // Format Cetak Surat (Tab 2)
            $table->string('ukuran_kertas')->default('A4 (210mm x 297mm)');
            $table->integer('margin_atas')->default(20);
            $table->integer('margin_kanan')->default(10);
            $table->integer('margin_bawah')->default(20);
            $table->integer('margin_kiri')->default(10);
            $table->string('jenis_font')->default('Arial');
            $table->string('ukuran_font')->default('12pt');
            $table->string('tinggi_baris')->default('1.4pt');
            $table->string('munculkan_pangkat')->default('Tidak');
            $table->string('munculkan_nip')->default('-');
            $table->string('munculkan_qr')->default('Tidak');

            // Sistem Penomoran Surat (Tab 3)
            $table->string('aturan_penomoran')->default('Berlaku Secara Umum');
            $table->string('format_nomor_umum')->default('[nnn]/[kode]/DIGIDES/[br]/[t]');
            $table->integer('inkremen_terakhir')->default(0);

            // Pengaturan Kop Surat (Tab 4)
            $table->string('kop_baris_1')->nullable();
            $table->string('kop_baris_2')->nullable();
            $table->string('kop_baris_3')->nullable();
            $table->text('kop_alamat')->nullable();
            $table->string('kop_logo_path')->nullable();

            $table->timestamps();
        });

        // 2. Tabel Manajemen Tanda Tangan (Tab 5)
        Schema::create('surat_penandatangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aparatur_id')->nullable()->constrained('aparatur')->nullOnDelete();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('nip')->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('bisa_tanda_tangan')->default(true);
            $table->boolean('is_camat')->default(false);
            $table->timestamps();
        });

        // 3. Tambahan kolom pengaturan cetak per-jenis surat pada surat_templates
        if (! Schema::hasColumn('surat_templates', 'custom_print_settings_json')) {
            Schema::table('surat_templates', function (Blueprint $table) {
                $table->json('custom_print_settings_json')->nullable()->after('dokumen_wajib_json');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('surat_templates', 'custom_print_settings_json')) {
            Schema::table('surat_templates', function (Blueprint $table) {
                $table->dropColumn('custom_print_settings_json');
            });
        }

        Schema::dropIfExists('surat_penandatangans');
        Schema::dropIfExists('surat_pengaturans');
    }
};
