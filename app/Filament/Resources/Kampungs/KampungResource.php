<?php

namespace App\Filament\Resources\Kampungs;

use App\Filament\Resources\Kampungs\Pages\CreateKampung;
use App\Filament\Resources\Kampungs\Pages\EditKampung;
use App\Filament\Resources\Kampungs\Pages\ListKampungs;
use App\Filament\Resources\Kampungs\Schemas\KampungForm;
use App\Filament\Resources\Kampungs\Tables\KampungsTable;
use App\Models\Kampung;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KampungResource extends Resource
{
    protected static ?string $model = Kampung::class;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    public static function getNavigationGroup(): ?string
    {
        return 'Kecamatan';
    }

    public static function getModelLabel(): string
    {
        return __('Kampung');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Kampung');
    }

    public static function getNavigationLabel(): string
    {
        return __('Kampung');
    }

    public static function form(Schema $schema): Schema
    {
        return KampungForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KampungsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKampungs::route('/'),
            'create' => CreateKampung::route('/create'),
            'edit' => EditKampung::route('/{record}/edit'),
        ];
    }
}
