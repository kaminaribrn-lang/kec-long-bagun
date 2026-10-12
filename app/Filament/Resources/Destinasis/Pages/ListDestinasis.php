<?php

namespace App\Filament\Resources\Destinasis\Pages;

use App\Filament\Resources\Destinasis\DestinasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListDestinasis extends ListRecords
{
    protected static string $resource = DestinasiResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('Tambah Destinasi'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
