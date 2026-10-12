<?php

namespace App\Support;

use App\Models\DynamicPage;
use App\Models\Menu;
use App\Services\DynamicPageLinker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Penghubung halaman depan dengan fitur Menu Website dan Halaman Dinamis di panel admin.
 * Alamat sebuah halaman dinamis adalah tautan menunya (mis. /profil/sejarah);
 * halaman tanpa menu memakai /halaman/{slug}.
 */
class Halaman
{
    /** Menu situs yang aktif, bertingkat, urut sesuai pengaturan admin. */
    public static function menu(): Collection
    {
        if (! app()->bound('situs.menu')) {
            app()->scoped('situs.menu', fn () => Menu::query()
                ->where('position', 'website')
                ->where('status', true)
                ->orderBy('order')->orderBy('id')
                ->get());
        }

        return app('situs.menu');
    }

    public static function normal(?string $path): string
    {
        $path = '/' . trim((string) parse_url((string) $path, PHP_URL_PATH), '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    /** Halaman dinamis untuk sebuah alamat, atau null bila alamat itu tidak punya halaman dinamis. */
    public static function untukPath(string $path): ?DynamicPage
    {
        $path = self::normal($path);

        $menu = self::menu()->first(fn (Menu $m) => self::normal($m->link) === $path && ! in_array($m->link, ['#', '', null], true));
        if ($menu && ($page = DynamicPageLinker::resolveForMenu($menu))) {
            return $page;
        }

        if (Str::startsWith($path, '/halaman/')) {
            return DynamicPage::query()->where('slug', Str::after($path, '/halaman/'))->first();
        }

        return null;
    }

    /** Alamat publik sebuah halaman dinamis. */
    public static function url(DynamicPage $page): string
    {
        $link = $page->menu?->link;

        return url(filled($link) && $link !== '#' ? $link : '/halaman/' . $page->slug);
    }

    /**
     * Susunan menu untuk tampilan: [teks, url, aktif, anak[]].
     * Menu induk bertautan "#" memakai tautan anak pertamanya.
     */
    public static function navigasi(): array
    {
        $semua = self::menu();
        $sekarang = self::normal(request()->getPathInfo());
        $aktif = fn (?string $link) => filled($link) && $link !== '#'
            && (self::normal($link) === $sekarang || (self::normal($link) !== '/' && Str::startsWith($sekarang, self::normal($link) . '/')));
        $tautan = fn (?string $link) => Str::startsWith((string) $link, ['http://', 'https://']) ? $link : url(self::normal($link));

        return $semua->whereNull('parent_id')->map(function (Menu $induk) use ($semua, $aktif, $tautan) {
            $anak = $semua->where('parent_id', $induk->id)->values();
            $kosong = blank($induk->link) || $induk->link === '#';

            if ($kosong && $anak->isEmpty()) {
                return null;
            }

            return [
                $induk->name,
                $tautan($kosong ? $anak->first()->link : $induk->link),
                $aktif($induk->link) || $anak->contains(fn (Menu $a) => $aktif($a->link)),
                $anak->map(fn (Menu $a) => [$a->name, $tautan($a->link)])->all(),
            ];
        })->filter()->values()->all();
    }
}
