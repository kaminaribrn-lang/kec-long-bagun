<?php

use App\Http\Controllers\DocumentStatsController;
use App\Http\Controllers\PublicMediaController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Situs Kecamatan Long Bagun
|--------------------------------------------------------------------------
| Struktur URL mengikuti prd.md bagian 3. Panel admin ada di /site-admin
| (lihat AdminPanelProvider).
|
| Rute di bawah adalah modul berbasis koleksi (kampung, layanan, berita, dst.).
| Menu atas dan halaman isi (profil, dll.) diatur dari panel admin lewat
| Menu Website dan Halaman Dinamis; lihat App\Support\Halaman.
*/

Route::get('/', [SiteController::class, 'home'])->name('beranda');

Route::get('/media/public/{path}', [PublicMediaController::class, 'show'])
    ->where('path', '.*')
    ->name('media.public');

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    cookie()->queue(cookie()->forever('filament_language_switch_locale', $locale));

    return redirect()->back();
})->name('language.switch');

// Kampung
Route::get('/kampung', [SiteController::class, 'kampungIndex'])->name('kampung.index');
Route::get('/kampung/{slug}', [SiteController::class, 'kampungShow'])->name('kampung.show');

// Layanan
Route::get('/layanan', [SiteController::class, 'layananIndex'])->name('layanan.index');
Route::get('/layanan/formulir', [SiteController::class, 'formulir'])->name('layanan.formulir');
Route::get('/layanan/pengaduan', [SiteController::class, 'pengaduan'])->name('layanan.pengaduan');
Route::post('/layanan/pengaduan', [SiteController::class, 'pengaduanStore'])
    ->middleware('throttle:5,10')
    ->name('layanan.pengaduan.store');
Route::get('/layanan/{slug}', [SiteController::class, 'layananShow'])->name('layanan.show');

// Data
Route::get('/data', [SiteController::class, 'dataIndex'])->name('data.index');
Route::get('/data/unduh/{kelompok}.csv', [SiteController::class, 'dataCsv'])->name('data.csv');
Route::get('/data/{topik}', [SiteController::class, 'dataTopik'])->name('data.topik');

// Potensi dan wisata
Route::get('/wisata', [SiteController::class, 'wisataIndex'])->name('wisata.index');
Route::get('/wisata/akses', [SiteController::class, 'akses'])->name('wisata.akses');
Route::get('/wisata/{slug}', [SiteController::class, 'wisataShow'])->name('wisata.show');

// Informasi
Route::get('/berita', [SiteController::class, 'beritaIndex'])->name('berita.index');
Route::get('/berita/{slug}', [SiteController::class, 'beritaShow'])->name('berita.show');
Route::get('/pengumuman', [SiteController::class, 'pengumumanIndex'])->name('pengumuman.index');
Route::get('/pengumuman/{slug}', [SiteController::class, 'pengumumanShow'])->name('pengumuman.show');
Route::get('/agenda', [SiteController::class, 'agenda'])->name('agenda');
Route::get('/galeri', [SiteController::class, 'galeri'])->name('galeri');

// PPID, kontak, pencarian, peta situs
Route::get('/ppid', [SiteController::class, 'ppid'])->name('ppid.index');
Route::get('/ppid/{kategori}', [SiteController::class, 'ppid'])->name('ppid.kategori')
    ->whereIn('kategori', array_column(\App\Models\DokumenPublik::KATEGORI, 0));
Route::get('/kontak', [SiteController::class, 'kontak'])->name('kontak');
Route::get('/cari', [SiteController::class, 'cari'])->name('cari');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');

// Nama rute lama yang masih dirujuk tampilan panel admin; diarahkan ke padanan barunya.
Route::redirect('/e-gov', '/layanan')->name('egov');
Route::redirect('/ppid/permohonan', '/ppid')->name('ppid.permohonan');
Route::redirect('/ppid/informasi', '/ppid')->name('ppid.informasi');
Route::redirect('/halaman/pengumuman', '/pengumuman', 301);

// Halaman Dinamis: /halaman/{slug} dan semua alamat lain yang diatur lewat Menu Website
// (mis. /profil/sejarah). Ditangani paling akhir, setelah semua rute tetap di atas.
Route::fallback([SiteController::class, 'halaman']);

// Statistik unduhan dokumen (dipakai widget admin)
Route::prefix('dokumen-stats')->name('stats.')->group(function () {
    Route::get('/view', [DocumentStatsController::class, 'view'])->name('view');
    Route::get('/download', [DocumentStatsController::class, 'download'])->name('download');
    Route::get('/get', [DocumentStatsController::class, 'stats'])->name('get');
    Route::post('/batch', [DocumentStatsController::class, 'batch'])->name('batch');
});
