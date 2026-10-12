<?php

namespace App\Filament\Resources\DokumenPubliks\Pages;

use App\Filament\Resources\DokumenPubliks\DokumenPublikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListDokumenPubliks extends ListRecords
{
    protected static string $resource = DokumenPublikResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('Tambah Dokumen PPID'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
