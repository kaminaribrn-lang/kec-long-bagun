<?php

namespace App\Filament\Resources\Layanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LayanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label(__('LAYANAN'))->searchable()->sortable(),
                TextColumn::make('kategori')->label(__('KATEGORI'))->badge()->placeholder('-'),
                TextColumn::make('waktu')->label(__('WAKTU'))->placeholder(__('Belum diisi')),
                TextColumn::make('biaya')->label(__('BIAYA'))->placeholder(__('Belum diisi')),
                IconColumn::make('tampil_beranda')->label(__('BERANDA'))->boolean()->alignCenter(),
                TextColumn::make('urutan')->label(__('URUTAN'))->alignCenter()->sortable(),
                IconColumn::make('status')->label(__('TAMPIL'))->boolean()->alignCenter(),
            ])
            ->defaultSort('urutan', 'asc')
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
