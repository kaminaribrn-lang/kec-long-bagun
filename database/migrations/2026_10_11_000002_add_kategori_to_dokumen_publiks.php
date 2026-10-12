<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_publiks', function (Blueprint $table) {
            // Klasifikasi informasi publik: berkala, serta_merta, setiap_saat, dikecualikan
            $table->string('kategori')->nullable()->index()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_publiks', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
