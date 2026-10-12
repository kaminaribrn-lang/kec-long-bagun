<?php

namespace App\Filament\Resources\Kampungs\Pages;

use App\Filament\Resources\Kampungs\KampungResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKampung extends EditRecord
{
    protected static string $resource = KampungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
