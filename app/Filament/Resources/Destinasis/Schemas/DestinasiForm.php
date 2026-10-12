<?php

namespace App\Filament\Resources\Destinasis\Schemas;

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

class DestinasiForm
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

                Select::make('jenis')->label(__('Jenis'))->required()->options(\App\Models\Destinasi::JENIS),
                Select::make('kampung_id')->label(__('Kampung'))->relationship('kampung', 'nama')->searchable()->preload()
                    ->helperText(__('Kosongkan bila tidak berada di satu kampung tertentu.')),
                TextInput::make('lokasi')->label(__('Lokasi (teks)'))->placeholder('Kampung Long Melaham'),
                TextInput::make('waktu_terbaik')->label(__('Waktu terbaik berkunjung')),

                Textarea::make('uraian')->label(__('Uraian'))->rows(4)->columnSpanFull(),
                Textarea::make('cara_mencapai')->label(__('Cara mencapai'))->rows(3)->columnSpanFull(),

                FileUpload::make('foto_utama')->label(__('Foto utama'))->directory('wisata')
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
                TextInput::make('foto_alt')->label(__('Teks alternatif foto')),
                TextInput::make('foto_kredit')->label(__('Kredit foto')),
                Repeater::make('galeri')->label(__('Galeri'))
                    ->schema([
                        FileUpload::make('foto')->label(__('Foto'))->directory('wisata/galeri')
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
                        TextInput::make('kredit')->label(__('Kredit foto')),
                    ])->columns(2)->defaultItems(0)->addActionLabel(__('Tambah foto'))->columnSpanFull(),

                Textarea::make('catatan_admin')->label(__('Catatan admin (tidak tampil ke publik)'))->rows(2)->columnSpanFull(),
                TextInput::make('urutan')->label(__('Urutan'))->numeric()->default(0),
                Toggle::make('status')->label(__('Tampilkan di situs'))->default(true),
            ])
            ->columns(2);
    }
}
