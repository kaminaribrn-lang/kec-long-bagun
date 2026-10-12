<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('kode_wilayah')->nullable()->index();
            $table->decimal('luas_km2', 10, 2)->nullable();
            $table->unsignedInteger('penduduk')->nullable();
            $table->unsignedSmallInteger('tahun_data')->nullable();
            $table->string('sumber_data')->nullable();
            $table->unsignedSmallInteger('jumlah_rt')->nullable();
            $table->string('nama_petinggi')->nullable();
            $table->string('foto_petinggi')->nullable();
            $table->text('uraian')->nullable();
            $table->text('potensi')->nullable();
            $table->string('foto_utama')->nullable();
            $table->string('foto_alt')->nullable();
            $table->string('foto_kredit')->nullable();
            $table->json('galeri')->nullable();
            $table->string('alamat_kantor')->nullable();
            $table->string('kontak')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('kategori')->nullable()->index();
            $table->string('ringkasan', 500)->nullable();
            $table->json('syarat')->nullable();
            $table->json('alur')->nullable();
            $table->string('waktu')->nullable();
            $table->string('biaya')->nullable();
            $table->string('formulir')->nullable();
            $table->string('kontak_petugas')->nullable();
            $table->string('ikon')->default('dokumen');
            $table->boolean('tampil_beranda')->default(false);
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('destinasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('jenis')->index(); // alam, budaya, acara_adat
            $table->foreignId('kampung_id')->nullable()->constrained('kampungs')->nullOnDelete();
            $table->string('lokasi')->nullable();
            $table->text('uraian')->nullable();
            $table->text('cara_mencapai')->nullable();
            $table->string('waktu_terbaik')->nullable();
            $table->string('foto_utama')->nullable();
            $table->string('foto_alt')->nullable();
            $table->string('foto_kredit')->nullable();
            $table->json('galeri')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('statistiks', function (Blueprint $table) {
            $table->id();
            $table->string('topik')->index(); // kunci, kependudukan, pendidikan, kesehatan, ekonomi
            $table->string('kelompok')->nullable()->index(); // mis. penduduk_per_tahun, agama
            $table->string('label');
            $table->decimal('nilai', 16, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->string('sumber')->nullable();
            $table->string('tautan_sumber')->nullable();
            $table->string('keterangan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('dokumen_publiks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('jenis')->index(); // perencanaan, laporan_kinerja, anggaran, regulasi, sop
            $table->unsignedSmallInteger('tahun')->nullable()->index();
            $table->string('berkas')->nullable();
            $table->date('tanggal_unggah')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('website_identities', function (Blueprint $table) {
            $table->string('jam_pelayanan')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->boolean('alert_active')->default(false);
            $table->string('alert_text', 500)->nullable();
            $table->string('alert_level')->default('info'); // info, waspada, bahaya
            $table->string('hero_image')->nullable();
            $table->string('hero_image_kredit')->nullable();
            $table->longText('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->longText('geografi')->nullable();
            $table->string('batas_utara')->nullable();
            $table->string('batas_timur')->nullable();
            $table->string('batas_selatan')->nullable();
            $table->string('batas_barat')->nullable();
            $table->json('akses')->nullable();
            $table->text('akses_catatan')->nullable();
            $table->date('akses_diperbarui')->nullable();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('ringkasan', 500)->nullable();
            $table->string('kredit_foto')->nullable();
            $table->date('berakhir_at')->nullable();
        });

        Schema::table('agenda', function (Blueprint $table) {
            $table->timestamp('end_at')->nullable();
        });

        // Tabel galleries lama dihapus oleh migrasi file_sharings dan tidak pernah dibuat ulang,
        // padahal model Gallery dan resource admin-nya masih memakainya.
        if (! Schema::hasTable('galleries')) {
            Schema::create('galleries', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('image');
                $table->boolean('is_active')->default(true);
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('galleries', function (Blueprint $table) {
            $table->string('kegiatan')->nullable()->index();
            $table->string('kredit')->nullable();
        });

        Schema::table('aparaturs', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('aparaturs')->nullOnDelete();
        });

        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('kampung')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', fn (Blueprint $table) => $table->dropColumn('kampung'));
        Schema::table('aparaturs', fn (Blueprint $table) => $table->dropConstrainedForeignId('parent_id'));
        Schema::table('galleries', fn (Blueprint $table) => $table->dropColumn(['kegiatan', 'kredit']));
        Schema::table('agenda', fn (Blueprint $table) => $table->dropColumn('end_at'));
        Schema::table('posts', fn (Blueprint $table) => $table->dropColumn(['ringkasan', 'kredit_foto', 'berakhir_at']));
        Schema::table('website_identities', fn (Blueprint $table) => $table->dropColumn([
            'jam_pelayanan', 'latitude', 'longitude', 'alert_active', 'alert_text', 'alert_level',
            'hero_image', 'hero_image_kredit', 'sejarah', 'visi', 'misi', 'geografi',
            'batas_utara', 'batas_timur', 'batas_selatan', 'batas_barat', 'akses', 'akses_catatan', 'akses_diperbarui',
        ]));
        Schema::dropIfExists('dokumen_publiks');
        Schema::dropIfExists('statistiks');
        Schema::dropIfExists('destinasis');
        Schema::dropIfExists('layanans');
        Schema::dropIfExists('kampungs');
    }
};
