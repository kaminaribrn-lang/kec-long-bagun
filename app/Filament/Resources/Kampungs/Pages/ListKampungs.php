<?php

namespace App\Filament\Resources\Kampungs\Pages;

use App\Filament\Resources\Kampungs\KampungResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListKampungs extends ListRecords
{
    protected static string $resource = KampungResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('Tambah Kampung'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
