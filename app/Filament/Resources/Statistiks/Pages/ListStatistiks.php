<?php

namespace App\Filament\Resources\Statistiks\Pages;

use App\Filament\Resources\Statistiks\StatistikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListStatistiks extends ListRecords
{
    protected static string $resource = StatistikResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('Tambah Statistik'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
