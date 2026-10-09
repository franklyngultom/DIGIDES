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
        Schema::create('village_news', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique()->index();
            $table->string('kategori', 50)->default('Berita')->index();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar_path')->nullable();
            $table->string('penulis')->default('Pemerintah Desa');
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_news');
    }
};
