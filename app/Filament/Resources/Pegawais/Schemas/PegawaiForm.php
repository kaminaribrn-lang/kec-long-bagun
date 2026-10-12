<?php

namespace App\Filament\Resources\Pegawais\Schemas;

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

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label(__('Nama'))->required()->maxLength(255),
                TextInput::make('position')->label(__('Jabatan'))->required()->maxLength(255),
                Select::make('parent_id')->label(__('Atasan'))->relationship('atasan', 'position', ignoreRecord: true)->searchable()->preload()
                    ->helperText(__('Kosongkan untuk camat.')),
                TextInput::make('order')->label(__('Urutan tampil'))->numeric()->default(0)
                    ->helperText(__('Angka terkecil tampil pertama. Pegawai urutan pertama tampil sebagai pimpinan di beranda.')),
                FileUpload::make('photo')->label(__('Foto resmi'))->directory('pegawai')
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
                Toggle::make('status')->label(__('Tampilkan di situs'))->default(true),
            ])
            ->columns(2);
    }
}
