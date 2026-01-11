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
        if (!Schema::hasTable('inkubator')) {
            Schema::create('inkubator', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('logo')->nullable();
                $table->string('no_tanda_daftar', 100)->nullable();
                $table->string('nama_inkubator', 250)->nullable();
                $table->integer('jenis_inkubator')->nullable();
                $table->string('induk_inkubator', 150)->nullable();
                $table->string('nama_pimpinan', 100)->nullable();
                $table->string('no_kontak', 15)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('kode_provinsi', 10)->nullable();
                $table->text('alamat_kantor')->nullable();
                $table->unsignedBigInteger('provinsi_id')->nullable();
                $table->integer('kabupaten_id')->nullable();
                $table->integer('kecamatan_id')->nullable();
                $table->string('website')->nullable();
                $table->string('facebook', 100)->nullable();
                $table->string('instagram', 100)->nullable();
                $table->string('tiktok', 100)->nullable();
                $table->text('path_kantor')->nullable();
                $table->text('path_ruang_usaha')->nullable();
                $table->text('path_ruang_rapat')->nullable();
                $table->text('path_ruang_pelatihan')->nullable();
                $table->text('path_ruang_komunikasi')->nullable();
                $table->text('path_legalitas')->nullable();
                $table->text('path_spesialisasi_inkubasi')->nullable();
                $table->text('path_model_inkubasi')->nullable();
                $table->text('path_rencana_strategis')->nullable();
                $table->string('ganti_nama', 250)->nullable();
                $table->string('ganti_email', 250)->nullable();
                $table->boolean('is_ganti')->default(0);
                $table->text('pemeringkatan_file')->nullable();
                $table->string('pemeringkatan_rank', 3)->nullable();
                $table->timestamps();

                // Indexes
                $table->index('provinsi_id', 'idx_provinsi_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inkubator');
    }
};
