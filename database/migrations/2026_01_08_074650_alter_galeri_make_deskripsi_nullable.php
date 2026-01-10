<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->text('excerpt')->nullable()->change();
            $table->string('alt_text')->nullable()->change();
            $table->string('kategori')->nullable()->change();
            $table->date('tanggal_kegiatan')->nullable()->change();
            $table->timestamp('published_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable(false)->change();
            $table->text('excerpt')->nullable(false)->change();
            $table->string('alt_text')->nullable(false)->change();
            $table->string('kategori')->nullable(false)->change();
            $table->date('tanggal_kegiatan')->nullable(false)->change();
            $table->timestamp('published_at')->nullable(false)->change();
        });
    }
};
