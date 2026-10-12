<?php

namespace App\Filament\Resources\Layanans\Schemas;

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

class LayananForm
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

                TextInput::make('kategori')->label(__('Kategori'))->placeholder('Kependudukan')
                    ->datalist(fn () => \App\Models\Layanan::query()->whereNotNull('kategori')->distinct()->pluck('kategori')->all())
                    ->helperText(__('Dipakai sebagai penyaring di halaman Layanan.')),

                Select::make('ikon')->label(__('Ikon'))->default('dokumen')
                    ->options(['ktp' => 'Kartu identitas', 'pindah' => 'Pindah rumah', 'stempel' => 'Stempel', 'usaha' => 'Toko/usaha', 'dokumen' => 'Dokumen', 'unduh' => 'Unduh', 'pesan' => 'Pesan']),

                Textarea::make('ringkasan')->label(__('Ringkasan'))->rows(2)->maxLength(500)->columnSpanFull(),

                Repeater::make('syarat')->label(__('Syarat'))
                    ->simple(TextInput::make('teks')->required())
                    ->defaultItems(0)->addActionLabel(__('Tambah syarat'))
                    ->helperText(__('Tampil sebagai daftar centang. Bila kosong, halaman depan menampilkan [SYARAT].'))
                    ->columnSpanFull(),

                Repeater::make('alur')->label(__('Alur (berurutan)'))
                    ->simple(TextInput::make('teks')->required())
                    ->defaultItems(0)->addActionLabel(__('Tambah langkah'))->reorderable()
                    ->helperText(__('Tampil sebagai langkah bernomor. Bila kosong, halaman depan menampilkan [ALUR].'))
                    ->columnSpanFull(),

                Textarea::make('dasar_hukum')->label(__('Dasar hukum'))->rows(4)->columnSpanFull()
                    ->helperText(__('Peraturan yang menjadi dasar syarat dan alur. Satu peraturan per baris.')),

                TextInput::make('waktu')->label(__('Waktu penyelesaian'))->placeholder('1 hari kerja'),
                TextInput::make('biaya')->label(__('Biaya'))->placeholder('Gratis'),
                TextInput::make('kontak_petugas')->label(__('Kontak petugas')),

                FileUpload::make('formulir')->label(__('Berkas formulir (PDF)'))
                    ->disk('public')->visibility('public')->directory('layanan/formulir')
                    ->acceptedFileTypes(['application/pdf'])->maxSize(5120)->columnSpanFull(),

                TextInput::make('urutan')->label(__('Urutan'))->numeric()->default(0),
                Toggle::make('tampil_beranda')->label(__('Tampilkan di layanan cepat beranda'))
                    ->helperText(__('Beranda memuat empat layanan pertama menurut urutan.')),
                Toggle::make('status')->label(__('Tampilkan di situs'))->default(true),
            ])
            ->columns(2);
    }
}
