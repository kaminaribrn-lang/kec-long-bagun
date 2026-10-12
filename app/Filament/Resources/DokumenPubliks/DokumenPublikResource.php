<?php

namespace App\Filament\Resources\DokumenPubliks;

use App\Filament\Resources\DokumenPubliks\Pages\CreateDokumenPublik;
use App\Filament\Resources\DokumenPubliks\Pages\EditDokumenPublik;
use App\Filament\Resources\DokumenPubliks\Pages\ListDokumenPubliks;
use App\Filament\Resources\DokumenPubliks\Schemas\DokumenPublikForm;
use App\Filament\Resources\DokumenPubliks\Tables\DokumenPubliksTable;
use App\Models\DokumenPublik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DokumenPublikResource extends Resource
{
    protected static ?string $model = DokumenPublik::class;

    protected static ?string $recordTitleAttribute = 'judul';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getNavigationGroup(): ?string
    {
        return 'Kecamatan';
    }

    public static function getModelLabel(): string
    {
        return __('Dokumen PPID');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Dokumen PPID');
    }

    public static function getNavigationLabel(): string
    {
        return __('Dokumen PPID');
    }

    public static function form(Schema $schema): Schema
    {
        return DokumenPublikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DokumenPubliksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDokumenPubliks::route('/'),
            'create' => CreateDokumenPublik::route('/create'),
            'edit' => EditDokumenPublik::route('/{record}/edit'),
        ];
    }
}
