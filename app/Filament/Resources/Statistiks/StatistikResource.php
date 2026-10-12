<?php

namespace App\Filament\Resources\Statistiks;

use App\Filament\Resources\Statistiks\Pages\CreateStatistik;
use App\Filament\Resources\Statistiks\Pages\EditStatistik;
use App\Filament\Resources\Statistiks\Pages\ListStatistiks;
use App\Filament\Resources\Statistiks\Schemas\StatistikForm;
use App\Filament\Resources\Statistiks\Tables\StatistiksTable;
use App\Models\Statistik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StatistikResource extends Resource
{
    protected static ?string $model = Statistik::class;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function getNavigationGroup(): ?string
    {
        return 'Kecamatan';
    }

    public static function getModelLabel(): string
    {
        return __('Statistik');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Statistik');
    }

    public static function getNavigationLabel(): string
    {
        return __('Statistik');
    }

    public static function form(Schema $schema): Schema
    {
        return StatistikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StatistiksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStatistiks::route('/'),
            'create' => CreateStatistik::route('/create'),
            'edit' => EditStatistik::route('/{record}/edit'),
        ];
    }
}
