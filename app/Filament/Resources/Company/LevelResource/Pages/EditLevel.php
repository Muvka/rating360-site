<?php

namespace App\Filament\Resources\Company\LevelResource\Pages;

use App\Filament\Resources\Company\LevelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLevel extends EditRecord
{
    protected static string $resource = LevelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
