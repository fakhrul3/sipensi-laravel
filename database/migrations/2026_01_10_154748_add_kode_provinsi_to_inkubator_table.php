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
        Schema::table('inkubator', function (Blueprint $table) {
            // Tambahkan kolom kode_provinsi jika belum ada
            if (!Schema::hasColumn('inkubator', 'kode_provinsi')) {
                $table->string('kode_provinsi', 10)->nullable()->after('email');
            }
            
            // Update provinsi_id ke unsigned bigint jika masih int
            // (tidak perlu, karena sudah ada dan bisa berbeda tipenya)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inkubator', function (Blueprint $table) {
            // Hapus kolom jika rollback
            if (Schema::hasColumn('inkubator', 'kode_provinsi')) {
                $table->dropColumn('kode_provinsi');
            }
        });
    }
};
