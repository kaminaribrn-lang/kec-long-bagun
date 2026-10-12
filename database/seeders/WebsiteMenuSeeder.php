<?php

namespace Database\Seeders;

use App\Models\DynamicPage;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Menu Website dan Halaman Dinamis awal untuk situs Kecamatan Long Bagun (prd.md bagian 3).
 * Setiap menu yang punya alamat mendapat satu Halaman Dinamis, sehingga judul, pengantar,
 * alamat, dan status terbitnya bisa diatur dari panel admin.
 */
class WebsiteMenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->hapusMenuLama();

        // [nama, tautan, templat, isi awal, anak[]]
        $menus = [
            ['Beranda', '/', null, [], []],
            ['Profil', '#', null, [], [
                ['Sejarah', '/profil/sejarah', 'penjelasan_1', [
                    'judul' => 'Sejarah Kecamatan Long Bagun',
                    'isi_konten' => '<p>[SEJARAH]</p>',
                ]],
                ['Visi dan Misi', '/profil/visi-misi', 'penjelasan_2', [
                    'box_1_title' => 'Visi', 'box_1_content' => '<p>[VISI]</p>',
                    'box_2_title' => 'Misi', 'box_2_content' => '<p>[MISI]</p>',
                ]],
                ['Geografi dan Peta', '/profil/geografi', 'modul_geografi', []],
                ['Struktur Organisasi', '/profil/struktur-organisasi', 'aparatur', [
                    'subtitle' => 'Pimpinan dan aparatur Kantor Camat Long Bagun.',
                ]],
            ]],
            ['Kampung', '/kampung', 'modul_kampung', ['subtitle' => 'Profil sebelas kampung: petinggi, luas wilayah, penduduk, dan potensinya.'], []],
            ['Layanan', '#', null, [], [
                ['Daftar Layanan', '/layanan', 'modul_layanan', ['subtitle' => 'Cek syarat dan alur tiap layanan sebelum datang ke kantor.']],
                ['Unduh Formulir', '/layanan/formulir', 'modul_formulir', ['subtitle' => 'Formulir layanan yang bisa diisi sebelum datang ke kantor camat.']],
                ['Pengaduan', '/layanan/pengaduan', 'modul_pengaduan', ['subtitle' => 'Sampaikan keluhan atau masukan. Pesan Anda masuk ke kantor camat dan tidak ditampilkan ke publik.']],
            ]],
            ['Data', '/data', 'modul_data', ['subtitle' => 'Angka dasar wilayah, masing-masing dengan tahun dan sumbernya.'], []],
            ['Wisata', '#', null, [], [
                ['Destinasi dan Acara Adat', '/wisata', 'modul_wisata', ['subtitle' => 'Destinasi alam, budaya, dan acara adat di hulu Sungai Mahakam.']],
                ['Cara Menuju Long Bagun', '/wisata/akses', 'modul_akses', ['subtitle' => 'Jalur sungai, darat, dan udara menuju Ujoh Bilang dan kampung-kampung di Long Bagun.']],
            ]],
            ['Informasi', '#', null, [], [
                ['Berita', '/berita', 'berita', ['subtitle' => 'Kabar kegiatan dan informasi dari Kecamatan Long Bagun.']],
                ['Pengumuman', '/pengumuman', 'modul_pengumuman', ['subtitle' => 'Pengumuman resmi Kantor Camat Long Bagun.']],
                ['Agenda', '/agenda', 'modul_agenda', ['subtitle' => 'Jadwal kegiatan Kantor Camat Long Bagun. Acara yang sudah lewat pindah ke arsip.']],
                ['Galeri', '/galeri', 'modul_galeri', ['subtitle' => 'Dokumentasi kegiatan di Kecamatan Long Bagun.']],
            ]],
            ['PPID', '/ppid', 'modul_ppid', ['subtitle' => 'Dokumen perencanaan, laporan kinerja, anggaran, regulasi, dan SOP yang bisa diunduh.'], [
                ['Informasi Berkala', '/ppid/berkala', null, []],
                ['Informasi Serta Merta', '/ppid/serta-merta', null, []],
                ['Informasi Setiap Saat', '/ppid/setiap-saat', null, []],
                ['Informasi Dikecualikan', '/ppid/dikecualikan', null, []],
            ]],
            ['Kontak', '/kontak', 'modul_kontak', ['subtitle' => 'Alamat, jam pelayanan, dan cara menghubungi Kantor Camat Long Bagun.'], []],
        ];

        foreach ($menus as $i => [$nama, $tautan, $templat, $isi, $anak]) {
            $induk = $this->menu($nama, $tautan, null, $i);
            $this->halaman($induk, $templat, $isi);

            foreach ($anak as $j => [$namaAnak, $tautanAnak, $templatAnak, $isiAnak]) {
                $this->halaman($this->menu($namaAnak, $tautanAnak, $induk->id, $j), $templatAnak, $isiAnak);
            }
        }
    }

    private function menu(string $nama, string $tautan, ?int $indukId, int $urutan): Menu
    {
        // Menu yang sudah ada tidak ditimpa, supaya perubahan admin tidak hilang saat seeder dijalankan ulang.
        return Menu::firstOrCreate(
            ['position' => 'website', 'parent_id' => $indukId, 'slug' => Str::slug($nama)],
            ['name' => $nama, 'link' => $tautan, 'order' => $urutan, 'status' => true]
        );
    }

    private function halaman(Menu $menu, ?string $templat, array $isi): void
    {
        if ($templat === null || DynamicPage::where('menu_id', $menu->id)->exists()) {
            return;
        }

        $slug = Str::slug(Str::afterLast($menu->link, '/')) ?: $menu->slug;

        DynamicPage::firstOrCreate(['slug' => $slug], [
            'menu_id' => $menu->id,
            'title' => $menu->name,
            'template' => $templat,
            'content' => $isi,
            'published' => true,
        ]);
    }

    /**
     * Menu bawaan situs Prokopim (regulasi, dokumen, WBS, dst.) tidak dipakai situs kecamatan.
     * Dihapus hanya pada pemasangan pertama: selama belum ada satu pun Halaman Dinamis
     * dan struktur menu kecamatan belum pernah dibuat. Setelah itu menu sepenuhnya milik admin.
     */
    private function hapusMenuLama(): void
    {
        $sudahDipasang = Menu::where('position', 'website')->where('slug', 'informasi')->exists();

        if ($sudahDipasang || DynamicPage::query()->exists()) {
            return;
        }

        Menu::where('position', 'website')->whereNotNull('parent_id')->delete();
        Menu::where('position', 'website')->delete();
    }
}
