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
        Schema::table('provinsi', function (Blueprint $table) {
            if (!Schema::hasColumn('provinsi', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('name');
            }
            if (!Schema::hasColumn('provinsi', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            if (Schema::hasColumn('provinsi', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('provinsi', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};
