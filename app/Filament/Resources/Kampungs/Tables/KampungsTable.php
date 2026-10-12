<?php

namespace App\Filament\Resources\Kampungs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KampungsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label(__('KAMPUNG'))->searchable()->sortable(),
                TextColumn::make('kode_wilayah')->label(__('KODE WILAYAH')),
                TextColumn::make('penduduk')->label(__('PENDUDUK'))->numeric(locale: 'id')->sortable()->alignEnd(),
                TextColumn::make('luas_km2')->label(__('LUAS (KM²)'))->numeric(decimalPlaces: 2, locale: 'id')->alignEnd(),
                TextColumn::make('jumlah_rt')->label(__('RT'))->alignCenter(),
                TextColumn::make('nama_petinggi')->label(__('PETINGGI'))->placeholder(__('Belum diisi')),
                IconColumn::make('status')->label(__('TAMPIL'))->boolean()->alignCenter(),
            ])
            ->defaultSort('penduduk', 'desc')
            ->actions([
                EditAction::make()->iconButton()->tooltip(__('Ubah')),
                DeleteAction::make()->iconButton()->tooltip(__('Hapus')),
            ])
            ->actionsColumnLabel(__('AKSI'))
            ->emptyStateHeading(__('Belum ada data'))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('Hapus Terpilih')),
                ])->label(__('Aksi Masal')),
            ]);
    }
}
