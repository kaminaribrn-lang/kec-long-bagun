<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label(__('NO'))
                    ->rowIndex()
                    ->width('60px')
                    ->alignCenter(),
                TextColumn::make('title')
                    ->label(__('JUDUL'))
                    ->searchable()
                    ->html()
                    ->formatStateUsing(function ($state, \App\Models\Post $record) {
                        $colors = [
                            ['#fee2e2', '#b91c1c'],
                            ['#dbeafe', '#1d4ed8'],
                            ['#dcfce7', '#3255AA'],
                            ['#fef9c3', '#a16207'],
                            ['#f3e8ff', '#7e22ce'],
                            ['#ffedd5', '#c2410c'],
                            ['#ecfeff', '#0e7490'],
                            ['#fce7f3', '#be185d'],
                        ];
                        
                        $categoryName = $record->category?->name ?? '-';
                        $hash = crc32(trim($categoryName));
                        $colorIndex = abs($hash) % count($colors);
                        [$bg, $text] = $colors[$colorIndex];

                        $hiddenBadge = '';
                        if ($record->category?->slug === 'uncategorized') {
                            $hiddenBadge = "<span class='inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-200 text-gray-600 border border-gray-300' title='" . e(__('Tidak tampil di daftar & pencarian Berita publik, hanya bisa diakses lewat tautan langsung')) . "'>"
                                . '🔒 ' . __('TERSEMBUNYI')
                                . '</span>';
                        }

                        return new \Illuminate\Support\HtmlString("
                            <div class='flex w-full min-w-0 flex-col items-start gap-1.5 text-left'>
                                <div class='flex flex-wrap items-center gap-1.5'>
                                    <span class='inline-flex items-center rounded px-2 py-0.5 text-xs font-bold' style='background-color: {$bg}; color: {$text}; border: 1px solid {$bg};'>
                                        " . e(strtoupper(__(strtolower($categoryName)))) . "
                                    </span>
                                    {$hiddenBadge}
                                </div>
                                <span class='w-full font-medium text-gray-900 dark:text-white'>" . e(\Illuminate\Support\Str::limit($state ?? '', 100)) . "</span>
                            </div>
                        ");
                    })
                    ->extraCellAttributes(['class' => 'long-text-column text-left'])
                    ->extraHeaderAttributes(['class' => 'text-left'])
                    ->alignLeft()
                    ->wrap(),
                TextColumn::make('penulis')
                    ->label(__('PENULIS'))
                    ->searchable()
                    ->placeholder('-')
                    ->width('160px')
                    ->alignCenter(),
                TextColumn::make('read')
                    ->label(__('DIBACA'))
                    ->numeric()
                    ->sortable()
                    ->width('90px')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label(__('DIBUAT'))
                    ->dateTime('Y-m-d H:i:s')
                    ->width('160px')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label(__('STATUS'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 1 ? __('Tayang') : __('Konsep'))
                    ->colors([
                        'success' => 1,
                        'warning' => 0,
                    ])
                    ->width('100px')
                    ->alignCenter(),
 
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label(__('Kategori')),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('Lihat'))
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip(fn (\App\Models\Post $record) => $record->category?->slug === 'uncategorized'
                        ? __('Lihat/salin tautan langsung (tersembunyi dari daftar publik)')
                        : __('Lihat di halaman publik'))
                    ->url(fn (\App\Models\Post $record) => route('berita.show', $record->slug))
                    ->openUrlInNewTab(),

                EditAction::make()
                    ->iconButton()
                    ->tooltip(__('Ubah')),

                DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('Hapus')),
            ])
            ->actionsColumnLabel(__('AKSI'))
            ->searchPlaceholder(__('Cari tulisan...'))
            ->emptyStateHeading(__('Tidak ada tulisan ditemukan'))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('Hapus Terpilih')),
                ])->label(__('Aksi Masal')),
            ]);
    }
}
