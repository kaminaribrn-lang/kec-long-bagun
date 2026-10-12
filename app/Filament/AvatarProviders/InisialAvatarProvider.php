<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Avatar inisial yang digambar langsung sebagai SVG, tanpa meminta layanan luar (ui-avatars.com).
 */
class InisialAvatarProvider implements AvatarProvider
{
    public function get(Model $record): string
    {
        // Huruf pertama dari dua kata pertama; tanda baca dilewati.
        preg_match_all('/(?<![\p{L}\p{N}])\p{L}/u', Filament::getNameForDefaultAvatar($record), $huruf);
        $inisial = Str::upper(implode('', array_slice($huruf[0], 0, 2)));

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" fill="#065F46"/>'
            . '<text x="32" y="33" fill="#fff" font-family="Arial,sans-serif" font-size="26" font-weight="700" text-anchor="middle" dominant-baseline="middle">'
            . e($inisial ?: 'A') . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
