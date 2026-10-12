<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPublik extends Model
{
    protected $fillable = ['judul','jenis','kategori','tahun','berkas','tanggal_unggah','status'];

    protected $casts = [
        'tanggal_unggah' => 'date',
        'status' => 'boolean',
    ];

    public const JENIS = ['profil' => 'Profil', 'perencanaan' => 'Perencanaan', 'laporan_kinerja' => 'Laporan kinerja', 'anggaran' => 'Anggaran', 'regulasi' => 'Regulasi', 'sop' => 'SOP', 'layanan_informasi' => 'Layanan informasi', 'kerja_sama' => 'Kerja sama', 'darurat' => 'Peringatan dan darurat'];

    /** Klasifikasi informasi publik: kunci => [alamat, nama, uraian]. */
    public const KATEGORI = [
        'berkala' => ['berkala', 'Informasi berkala', 'Informasi yang wajib disediakan dan diumumkan secara rutin, seperti profil, program, kinerja, dan laporan keuangan.'],
        'serta_merta' => ['serta-merta', 'Informasi serta merta', 'Informasi yang harus segera diumumkan karena menyangkut hajat hidup orang banyak dan ketertiban umum, misalnya peringatan banjir.'],
        'setiap_saat' => ['setiap-saat', 'Informasi setiap saat', 'Informasi yang wajib tersedia kapan pun diminta, seperti daftar informasi publik, keputusan, kebijakan, dan SOP.'],
        'dikecualikan' => ['dikecualikan', 'Informasi dikecualikan', 'Informasi yang tidak dapat dibuka untuk umum berdasarkan uji konsekuensi sesuai peraturan perundang-undangan.'],
    ];

    /** Pilihan kategori untuk form admin: kunci => nama. */
    public static function pilihanKategori(): array
    {
        return array_map(fn (array $k) => $k[1], self::KATEGORI);
    }

    /** Kunci kategori dari potongan alamat (mis. "serta-merta" menjadi "serta_merta"). */
    public static function kategoriDariAlamat(?string $alamat): ?string
    {
        foreach (self::KATEGORI as $kunci => [$slug]) {
            if ($slug === $alamat) {
                return $kunci;
            }
        }

        return null;
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? ucfirst((string) $this->jenis);
    }

    public function getKategoriLabelAttribute(): ?string
    {
        return self::KATEGORI[$this->kategori][1] ?? null;
    }
}
