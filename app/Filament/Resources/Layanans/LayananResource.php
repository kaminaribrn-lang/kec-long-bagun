<?php

namespace App\Filament\Resources\Layanans;

use App\Filament\Resources\Layanans\Pages\CreateLayanan;
use App\Filament\Resources\Layanans\Pages\EditLayanan;
use App\Filament\Resources\Layanans\Pages\ListLayanans;
use App\Filament\Resources\Layanans\Schemas\LayananForm;
use App\Filament\Resources\Layanans\Tables\LayanansTable;
use App\Models\Layanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function getNavigationGroup(): ?string
    {
        return 'Kecamatan';
    }

    public static function getModelLabel(): string
    {
        return __('Layanan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Layanan');
    }

    public static function getNavigationLabel(): string
    {
        return __('Layanan');
    }

    public static function form(Schema $schema): Schema
    {
        return LayananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LayanansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLayanans::route('/'),
            'create' => CreateLayanan::route('/create'),
            'edit' => EditLayanan::route('/{record}/edit'),
        ];
    }
}
