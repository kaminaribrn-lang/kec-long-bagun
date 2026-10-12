<?php

namespace App\Filament\Resources\DokumenPubliks\Pages;

use App\Filament\Resources\DokumenPubliks\DokumenPublikResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDokumenPublik extends CreateRecord
{
    protected static string $resource = DokumenPublikResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
