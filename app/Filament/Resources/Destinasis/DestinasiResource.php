<?php

namespace App\Filament\Resources\Destinasis;

use App\Filament\Resources\Destinasis\Pages\CreateDestinasi;
use App\Filament\Resources\Destinasis\Pages\EditDestinasi;
use App\Filament\Resources\Destinasis\Pages\ListDestinasis;
use App\Filament\Resources\Destinasis\Schemas\DestinasiForm;
use App\Filament\Resources\Destinasis\Tables\DestinasisTable;
use App\Models\Destinasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DestinasiResource extends Resource
{
    protected static ?string $model = Destinasi::class;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    public static function getNavigationGroup(): ?string
    {
        return 'Kecamatan';
    }

    public static function getModelLabel(): string
    {
        return __('Destinasi');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Destinasi & Acara Adat');
    }

    public static function getNavigationLabel(): string
    {
        return __('Destinasi & Acara Adat');
    }

    public static function form(Schema $schema): Schema
    {
        return DestinasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinasisTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinasis::route('/'),
            'create' => CreateDestinasi::route('/create'),
            'edit' => EditDestinasi::route('/{record}/edit'),
        ];
    }
}
