<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PegawaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')->label(__('FOTO'))->disk('public')->size(56)->circular(),
                TextColumn::make('name')->label(__('NAMA'))->searchable(),
                TextColumn::make('position')->label(__('JABATAN'))->searchable(),
                TextColumn::make('atasan.position')->label(__('ATASAN'))->placeholder('-'),
                TextColumn::make('order')->label(__('URUTAN'))->alignCenter()->sortable(),
                IconColumn::make('status')->label(__('TAMPIL'))->boolean()->alignCenter(),
            ])
            ->defaultSort('order', 'asc')
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
