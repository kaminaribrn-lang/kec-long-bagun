<?php

namespace App\Filament\Resources\DokumenPubliks\Schemas;

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

class DokumenPublikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')->label(__('Judul dokumen'))->required()->maxLength(255)->columnSpanFull(),
                Select::make('kategori')->label(__('Kategori informasi'))->required()->options(\App\Models\DokumenPublik::pilihanKategori())
                    ->helperText(__('Menentukan di bagian PPID mana dokumen ini tampil.')),
                Select::make('jenis')->label(__('Jenis'))->required()->options(\App\Models\DokumenPublik::JENIS),
                TextInput::make('tahun')->label(__('Tahun'))->numeric()->minValue(1990)->maxValue(2100),
                FileUpload::make('berkas')->label(__('Berkas PDF'))
                    ->disk('public')->visibility('public')->directory('ppid')
                    ->acceptedFileTypes(['application/pdf'])->maxSize(20480)->columnSpanFull()
                    ->helperText(__('Boleh dikosongkan dulu; di situs dokumen tampil dengan keterangan "Berkas belum diunggah".')),
                DatePicker::make('tanggal_unggah')->label(__('Tanggal unggah'))->native(false)->displayFormat('d/m/Y'),
                Toggle::make('status')->label(__('Tampilkan di situs'))->default(true),
            ])
            ->columns(2);
    }
}
