<?php

namespace App\Filament\Resources\Statistiks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StatistikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('topik')->label(__('Topik'))->required()->options(\App\Models\Statistik::TOPIK)
                    ->helperText(__('"Angka kunci beranda" tampil sebagai empat angka di beranda.')),
                TextInput::make('kelompok')->label(__('Kelompok tabel'))->placeholder('penduduk_per_tahun')
                    ->datalist(fn () => \App\Models\Statistik::query()->whereNotNull('kelompok')->distinct()->pluck('kelompok')->all())
                    ->helperText(__('Baris dengan kelompok yang sama tampil sebagai satu tabel dan satu grafik.')),
                TextInput::make('label')->label(__('Label'))->required()->maxLength(255),
                TextInput::make('nilai')->label(__('Nilai'))->numeric()->step(0.01)->required(),
                TextInput::make('satuan')->label(__('Satuan'))->placeholder('jiwa'),
                TextInput::make('tahun')->label(__('Tahun'))->numeric()->minValue(1990)->maxValue(2100),
                TextInput::make('sumber')->label(__('Sumber'))->required()->default('BPS Mahakam Ulu'),
                TextInput::make('tautan_sumber')->label(__('Tautan sumber'))->url(),
                TextInput::make('keterangan')->label(__('Keterangan'))->columnSpanFull(),
                Textarea::make('catatan_admin')->label(__('Catatan admin (tidak tampil ke publik)'))->rows(2)
                    ->helperText(__('Misalnya angka pembanding yang masih perlu dicek ke publikasi BPS.'))->columnSpanFull(),
                TextInput::make('urutan')->label(__('Urutan'))->numeric()->default(0),
            ])
            ->columns(2);
    }
}
