<?php

namespace App\Filament\Resources\Statistiks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatistiksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('topik')->label(__('TOPIK'))->badge()->formatStateUsing(fn ($state) => \App\Models\Statistik::TOPIK[$state] ?? $state),
                TextColumn::make('kelompok')->label(__('KELOMPOK'))->placeholder('-'),
                TextColumn::make('label')->label(__('LABEL'))->searchable(),
                TextColumn::make('nilai')->label(__('NILAI'))->numeric(locale: 'id')->alignEnd(),
                TextColumn::make('satuan')->label(__('SATUAN')),
                TextColumn::make('tahun')->label(__('TAHUN'))->alignCenter()->placeholder('-'),
                TextColumn::make('sumber')->label(__('SUMBER')),
                IconColumn::make('catatan_admin')->label(__('PERLU CEK'))->boolean()->getStateUsing(fn ($record) => filled($record->catatan_admin))
                    ->trueIcon('heroicon-o-exclamation-triangle')->falseIcon('heroicon-o-minus')->trueColor('warning')->falseColor('gray')
                    ->tooltip(fn ($record) => $record->catatan_admin)->alignCenter(),
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
