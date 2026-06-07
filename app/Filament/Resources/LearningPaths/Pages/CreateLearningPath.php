<?php

namespace App\Filament\Resources\LearningPaths\Pages;

use App\Filament\Resources\LearningPaths\LearningPathResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningPath extends CreateRecord
{
    protected static string $resource = LearningPathResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
