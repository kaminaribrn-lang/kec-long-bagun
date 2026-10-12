<?php

namespace App\Support;

use App\Models\WebsiteIdentity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Pembantu tampilan halaman depan: pengaturan situs, format angka Indonesia,
 * tautan berkas unggahan, dan teks pengganti untuk data yang belum diisi.
 */
class Situs
{
    /** Pengaturan situs; dibaca sekali per permintaan. */
    public static function pengaturan(): WebsiteIdentity
    {
        if (! app()->bound('situs.pengaturan')) {
            app()->scoped('situs.pengaturan', fn () => WebsiteIdentity::query()->latest('id')->first() ?? new WebsiteIdentity());
        }

        return app('situs.pengaturan');
    }

    /** 16744 menjadi "16.744"; 4801.22 menjadi "4.801,22". */
    public static function angka(int|float|string|null $nilai, ?int $desimal = null): string
    {
        if ($nilai === null || $nilai === '') {
            return '';
        }

        $nilai = (float) $nilai;
        $desimal ??= (floor($nilai) == $nilai) ? 0 : 2;

        return number_format($nilai, $desimal, ',', '.');
    }

    /** Nilai yang sudah diisi, atau teks pengganti "[LABEL]" sesuai aturan PRD bagian 10. */
    public static function isi(mixed $nilai, string $label): HtmlString
    {
        if (filled($nilai)) {
            return new HtmlString(e($nilai));
        }

        return new HtmlString('<span class="tbd">[' . e(Str::upper($label)) . ']</span>');
    }

    /**
     * Konten dari editor admin, siap ditampilkan: blok video diubah menjadi pemutar,
     * dan alamat gambar unggahan dibuat relatif agar tidak terikat nama domain.
     */
    public static function konten(?string $html): HtmlString
    {
        $html = preg_replace_callback('#<div\b(?=[^>]*data-type="customBlock")([^>]*)>\s*</div>#i', function (array $m): string {
            preg_match('#data-id="([^"]*)"#', $m[1], $id);
            preg_match('#data-config="([^"]*)"#', $m[1], $config);
            $config = json_decode(html_entity_decode($config[1] ?? '', ENT_QUOTES | ENT_HTML5), true);

            if (($id[1] ?? null) !== \App\Filament\RichEditor\VideoBlock::getId() || ! is_array($config)) {
                return '';
            }

            return (string) \App\Filament\RichEditor\VideoBlock::toHtml($config, []);
        }, (string) $html);

        $html = preg_replace('#(\ssrc=")https?://[^/"]+(/storage/)#i', '$1$2', (string) $html);

        return new HtmlString($html);
    }

    /** Tautan publik untuk berkas unggahan admin (disk public), atau null bila belum ada. */
    public static function berkas(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        $path = preg_replace('#^(?:storage/app/public/|app/public/|public/storage/|public/|storage/)#i', '', ltrim(str_replace('\\', '/', $path), '/'));

        if (Storage::disk('public')->exists($path)) {
            return route('media.public', ['path' => $path]);
        }

        return null;
    }

    /** Nomor WhatsApp dalam format internasional untuk wa.me, atau null bila belum diisi. */
    public static function nomorWa(): ?string
    {
        $nomor = preg_replace('/\D+/', '', (string) self::pengaturan()->whatsapp);

        if ($nomor === '') {
            return null;
        }

        return Str::startsWith($nomor, '0') ? '62' . substr($nomor, 1) : $nomor;
    }

    /**
     * Menu utama situs: [teks, url, aktif, anak[]].
     * Dibaca dari Menu Website di panel admin; susunan bawaan PRD hanya dipakai bila menu masih kosong.
     */
    public static function menu(): array
    {
        $menu = Halaman::navigasi();

        if ($menu !== []) {
            return $menu;
        }

        return [
            ['Beranda', url('/'), request()->is('/'), []],
            ['Kampung', url('/kampung'), request()->is('kampung*'), []],
            ['Layanan', url('/layanan'), request()->is('layanan*'), []],
            ['Berita', url('/berita'), request()->is('berita*'), []],
            ['Kontak', url('/kontak'), request()->is('kontak'), []],
        ];
    }
}
