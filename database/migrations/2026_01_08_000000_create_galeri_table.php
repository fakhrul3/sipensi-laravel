<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('path_gambar');              // path file (public/img/... atau storage/...)
            $table->string('judul');
            $table->string('slug')->unique();           // SEO slug
            $table->string('kategori')->nullable();     // fleksibel, bisa nambah kategori lain

            $table->boolean('is_show')->default(true);  // 1 tampil, 0 tidak tampil
            $table->date('tanggal_kegiatan')->nullable();

            $table->longText('deskripsi');              // konten panjang (boleh HTML)
            $table->text('excerpt')->nullable();        // ringkasan
            $table->string('alt_text')->nullable();     // alt gambar

            $table->integer('sort_order')->default(0);  // urutan manual
            $table->timestamp('published_at')->nullable(); // jadwal publish (optional)

            $table->softDeletes();                      // deleted_at
            $table->timestamps();

            $table->index(['kategori']);
            $table->index(['is_show']);
            $table->index(['tanggal_kegiatan']);
            $table->index(['published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
