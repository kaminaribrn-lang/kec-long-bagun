<?php

namespace App\Filament\Resources\Destinasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DestinasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_utama')->label(__('FOTO'))->disk('public')->size(64)->square(),
                TextColumn::make('nama')->label(__('NAMA'))->searchable()->sortable(),
                TextColumn::make('jenis')->label(__('JENIS'))->badge()->formatStateUsing(fn ($state) => \App\Models\Destinasi::JENIS[$state] ?? $state),
                TextColumn::make('lokasi')->label(__('LOKASI'))->placeholder('-'),
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
