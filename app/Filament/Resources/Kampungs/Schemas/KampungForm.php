<?php

namespace App\Filament\Resources\Kampungs\Schemas;

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

class KampungForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label(__('Nama'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),

                TextInput::make('slug')
                    ->label(__('Slug (alamat halaman)'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText(__('Huruf kecil dan tanda hubung. Mengubah slug mengubah alamat halaman.')),

                Section::make(__('Data wilayah'))
                    ->description(__('Setiap angka wajib punya tahun dan sumber. Angka tampil di halaman kampung, beranda, dan halaman Data.'))
                    ->schema([
                        TextInput::make('kode_wilayah')->label(__('Kode wilayah'))->placeholder('64.11.01.2006')
                            ->helperText(__('Dipakai untuk mencocokkan kampung dengan wilayahnya di peta.')),
                        TextInput::make('luas_km2')->label(__('Luas (km²)'))->numeric()->step(0.01),
                        TextInput::make('penduduk')->label(__('Penduduk (jiwa)'))->numeric(),
                        TextInput::make('jumlah_rt')->label(__('Jumlah RT'))->numeric(),
                        TextInput::make('tahun_data')->label(__('Tahun data'))->numeric()->minValue(1990)->maxValue(2100),
                        TextInput::make('sumber_data')->label(__('Sumber data'))->placeholder('BPS Mahakam Ulu'),
                    ])->columns(3)->columnSpanFull(),

                Section::make(__('Petinggi dan kontak'))
                    ->schema([
                        TextInput::make('nama_petinggi')->label(__('Nama petinggi'))
                            ->helperText(__('Bila kosong, halaman depan menampilkan [NAMA PETINGGI].')),
                        TextInput::make('kontak')->label(__('Kontak kantor kampung')),
                        TextInput::make('alamat_kantor')->label(__('Alamat kantor kampung'))->columnSpanFull(),
                        TextInput::make('latitude')->label(__('Lintang (desimal)'))->numeric()->step(0.000001),
                        TextInput::make('longitude')->label(__('Bujur (desimal)'))->numeric()->step(0.000001),
                        FileUpload::make('foto_petinggi')->label(__('Foto petinggi'))->directory('kampung/petinggi')
                            ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('1000')
                    ->imageResizeUpscale(false)
                    ->maxSize(4096)
                    ->helperText(__('JPG, PNG, atau WebP. Diperkecil otomatis saat diunggah.'))->columnSpanFull(),
                    ])->columns(2)->columnSpanFull(),

                Section::make(__('Uraian dan foto'))
                    ->schema([
                        Textarea::make('uraian')->label(__('Uraian'))->rows(4)->columnSpanFull(),
                        Textarea::make('potensi')->label(__('Potensi'))->rows(4)->columnSpanFull(),
                        FileUpload::make('foto_utama')->label(__('Foto utama'))->directory('kampung')
                            ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('900')
                    ->imageResizeUpscale(false)
                    ->maxSize(4096)
                    ->helperText(__('JPG, PNG, atau WebP. Diperkecil otomatis saat diunggah.'))->columnSpanFull(),
                        TextInput::make('foto_alt')->label(__('Teks alternatif foto'))->helperText(__('Kalimat singkat yang menjelaskan isi foto.')),
                        TextInput::make('foto_kredit')->label(__('Kredit foto'))->helperText(__('Nama pemotret atau pemilik foto.')),
                        Repeater::make('galeri')->label(__('Galeri'))
                            ->schema([
                                FileUpload::make('foto')->label(__('Foto'))->directory('kampung/galeri')
                                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('1200')
                    ->imageResizeUpscale(false)
                    ->maxSize(4096)
                    ->helperText(__('JPG, PNG, atau WebP. Diperkecil otomatis saat diunggah.'))->required(),
                                TextInput::make('alt')->label(__('Teks alternatif')),
                                TextInput::make('kredit')->label(__('Kredit foto')),
                            ])->columns(3)->defaultItems(0)->addActionLabel(__('Tambah foto'))->columnSpanFull(),
                    ])->columns(2)->columnSpanFull(),

                Textarea::make('catatan_admin')->label(__('Catatan admin (tidak tampil ke publik)'))->rows(2)->columnSpanFull(),
                Toggle::make('status')->label(__('Tampilkan di situs'))->default(true),
            ])
            ->columns(2);
    }
}
