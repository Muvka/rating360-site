<?php

namespace App\Filament\Resources\Rating\CompetenceResource\Pages;

use App\Filament\Resources\Rating\CompetenceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetence extends EditRecord
{
    protected static string $resource = CompetenceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
