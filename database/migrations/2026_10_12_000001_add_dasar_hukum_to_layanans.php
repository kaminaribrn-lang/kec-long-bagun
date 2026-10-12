<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            // Peraturan yang menjadi dasar syarat dan alur, satu peraturan per baris.
            $table->text('dasar_hukum')->nullable()->after('alur');
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('dasar_hukum');
        });
    }
};
