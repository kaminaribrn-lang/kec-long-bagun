<?php

namespace App\Filament\Resources\Kampungs\Pages;

use App\Filament\Resources\Kampungs\KampungResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKampung extends CreateRecord
{
    protected static string $resource = KampungResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
