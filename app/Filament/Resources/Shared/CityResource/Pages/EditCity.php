<?php

namespace App\Filament\Resources\Shared\CityResource\Pages;

use App\Filament\Resources\Shared\CityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
