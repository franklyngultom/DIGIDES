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
        Schema::table('surat_templates', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('kode_surat');
            $table->json('persyaratan_json')->nullable()->after('schema_fields_json');
            $table->json('dokumen_wajib_json')->nullable()->after('persyaratan_json');
            $table->string('estimasi_proses', 50)->default('15 - 30 Menit')->after('dokumen_wajib_json');
            $table->string('biaya', 50)->default('Gratis')->after('estimasi_proses');
            $table->boolean('is_online_available')->default(true)->after('is_active')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_templates', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'persyaratan_json',
                'dokumen_wajib_json',
                'estimasi_proses',
                'biaya',
                'is_online_available',
            ]);
        });
    }
};
