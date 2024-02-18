<?php

namespace App\Filament\Resources\Company\LevelResource\Pages;

use App\Filament\Resources\Company\LevelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLevels extends ListRecords
{
    protected static string $resource = LevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
