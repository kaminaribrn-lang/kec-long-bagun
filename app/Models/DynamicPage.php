<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicPage extends Model
{
    protected $fillable = [
        'menu_id',
        'slug',
        'title',
        'template',
        'content',
        'hero_badge',
        'meta_title',
        'meta_desc',
        'published',
    ];

    protected $casts = [
        'content'   => 'array',
        'published' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * All available templates with their labels and field definitions.
     */
    public static function templates(): array
    {
        return [
            'penjelasan_1'    => 'Penjelasan 1 (Judul & 1 Konten)',
            'penjelasan_2'    => 'Penjelasan 2 (Sub Judul & 2 Panel)',
            'aparatur'        => 'Daftar Aparatur',
            'profil_pimpinan' => 'Profil Pimpinan (Timeline)',
            'gambar_1'        => 'Halaman Gambar Tunggal',
            'galeri'          => 'Galeri Foto',
            'dokumen_grid'    => 'Dokumen Grid (Tabel)',
            'dokumen_list'    => 'Dokumen List (Kartu)',
            'tabel'           => 'Konten Tabel Custom',
            'blank_editor'    => 'Editor Bebas (Blank)',
            'berita'          => 'Daftar Berita',
        ] + collect(self::modul())->map(fn ($m) => 'Modul: ' . $m[0])->all();
    }

    /**
     * Templat modul: halaman yang isinya diambil dari koleksi lain di panel admin
     * (Kampung, Layanan, Statistik, dst.). Halaman dinamisnya mengatur judul,
     * pengantar, alamat, menu, SEO, dan status terbit.
     * Format: kunci => [label, keterangan, koleksi sumber].
     */
    public static function modul(): array
    {
        return [
            'modul_kampung'    => ['Daftar Kampung', 'Kartu kampung dan urutan', 'Kampung'],
            'modul_geografi'   => ['Geografi & Peta', 'Peta kampung dan fakta wilayah', 'Kampung, Statistik'],
            'modul_layanan'    => ['Daftar Layanan', 'Layanan dengan penyaring kategori', 'Layanan'],
            'modul_formulir'   => ['Unduh Formulir', 'Formulir dari tiap layanan', 'Layanan'],
            'modul_pengaduan'  => ['Formulir Pengaduan', 'Formulir aduan warga', 'Pengaduan'],
            'modul_data'       => ['Ringkasan Data', 'Angka kunci dan topik data', 'Statistik'],
            'modul_wisata'     => ['Destinasi & Acara Adat', 'Kartu destinasi berfoto', 'Destinasi'],
            'modul_akses'      => ['Cara Menuju', 'Jalur sungai, darat, udara', 'Identitas Website'],
            'modul_pengumuman' => ['Daftar Pengumuman', 'Pengumuman terbaru', 'Berita (kategori Pengumuman)'],
            'modul_agenda'     => ['Agenda', 'Agenda mendatang dan arsip', 'Agenda'],
            'modul_galeri'     => ['Galeri Kegiatan', 'Foto per kegiatan', 'Galeri'],
            'modul_ppid'       => ['Dokumen PPID', 'Dokumen dengan penyaring', 'Dokumen PPID'],
            'modul_kontak'     => ['Kontak & Lokasi', 'Alamat, kontak, dan peta', 'Identitas Website'],
        ];
    }

    public function isModul(): bool
    {
        return $this->template === 'berita' || isset(self::modul()[$this->template]);
    }

    /**
     * Badge color per template for Filament table.
     */
    public static function templateColor(string $template): string
    {
        return match($template) {
            'penjelasan_1'  => 'info',
            'penjelasan_2'  => 'info',
            'aparatur'      => 'success',
            'gambar_1'      => 'warning',
            'galeri'        => 'warning',
            'dokumen_grid'  => 'gray',
            'dokumen_list'  => 'gray',
            'tabel'         => 'danger',
            'blank_editor'  => 'primary',
            'berita'        => 'success',
            default         => 'gray',
        };
    }
}
