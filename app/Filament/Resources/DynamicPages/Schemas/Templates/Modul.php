<?php

namespace App\Filament\Resources\DynamicPages\Schemas\Templates;

use App\Models\DynamicPage;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;

/**
 * Templat modul: isi halaman diambil dari koleksi lain (Kampung, Layanan, dst.),
 * sehingga di sini cukup mengatur kalimat pengantar di bawah judul.
 */
class Modul
{
    public static function schema(): array
    {
        return [
            Textarea::make('content.subtitle')
                ->label(__('Kalimat pengantar'))
                ->rows(3)
                ->maxLength(300)
                ->helperText(__('Tampil di bawah judul halaman. Boleh dikosongkan.'))
                ->columnSpanFull(),

            Placeholder::make('modul_sumber')
                ->label(__('Sumber isi halaman'))
                ->content(function (Get $get) {
                    $kunci = $get('template');
                    $sumber = $kunci === 'berita' ? 'Berita' : (DynamicPage::modul()[$kunci][2] ?? '-');

                    return __('Isi halaman ini diambil otomatis dari menu admin: :sumber. Ubah datanya di menu tersebut; halaman depan langsung mengikuti.', ['sumber' => $sumber]);
                })
                ->columnSpanFull(),
        ];
    }
}
