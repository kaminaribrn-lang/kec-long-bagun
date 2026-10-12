<?php

namespace App\Filament\Resources\DokumenPubliks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DokumenPubliksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->label(__('JUDUL'))->searchable()->wrap(),
                TextColumn::make('kategori')->label(__('KATEGORI'))->badge()->color('gray')->placeholder('–')->formatStateUsing(fn ($state) => \App\Models\DokumenPublik::pilihanKategori()[$state] ?? $state),
                TextColumn::make('jenis')->label(__('JENIS'))->badge()->formatStateUsing(fn ($state) => \App\Models\DokumenPublik::JENIS[$state] ?? $state),
                TextColumn::make('tahun')->label(__('TAHUN'))->alignCenter()->sortable(),
                TextColumn::make('tanggal_unggah')->label(__('DIUNGGAH'))->date('d M Y'),
                IconColumn::make('status')->label(__('TAMPIL'))->boolean()->alignCenter(),
            ])
            ->defaultSort('tahun', 'desc')
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
