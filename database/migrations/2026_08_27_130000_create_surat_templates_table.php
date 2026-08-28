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
        Schema::create('surat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat', 20)->unique()->index();
            $table->string('nama_surat');
            $table->string('penomoran_format'); // e.g. '470/{no}/DS-SKM/{romawi_bulan}/{tahun}'
            $table->string('template_blade');
            $table->json('schema_fields_json')->nullable();
            $table->string('icon')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_templates');
    }
};
