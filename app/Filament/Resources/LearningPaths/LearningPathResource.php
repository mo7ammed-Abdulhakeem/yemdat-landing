<?php

namespace App\Filament\Resources\LearningPaths;

use App\Filament\Concerns\AuthorizesViaPermission;
use App\Filament\Resources\LearningPaths\Pages\CreateLearningPath;
use App\Filament\Resources\LearningPaths\Pages\EditLearningPath;
use App\Filament\Resources\LearningPaths\Pages\ListLearningPaths;
use App\Filament\Resources\LearningPaths\Schemas\LearningPathForm;
use App\Filament\Resources\LearningPaths\Tables\LearningPathsTable;
use App\Models\LearningPath;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LearningPathResource extends Resource
{
    use AuthorizesViaPermission;

    protected static ?string $model = LearningPath::class;

    protected static ?string $recordTitleAttribute = 'title_en';

    // Content authors who can manage posts can also curate learning paths.
    protected static function permissionKey(): ?string
    {
        return 'posts';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title_en', 'title_ar'];
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Learning Paths';

    public static function form(Schema $schema): Schema
    {
        return LearningPathForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LearningPathsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLearningPaths::route('/'),
            'create' => CreateLearningPath::route('/create'),
            'edit' => EditLearningPath::route('/{record}/edit'),
        ];
    }
}
