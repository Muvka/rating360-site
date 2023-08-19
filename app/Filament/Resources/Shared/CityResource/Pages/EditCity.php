<?php

namespace App\Filament\Resources\Shared\CityResource\Pages;

use App\Filament\Resources\Shared\CityResource;
use App\Models\Shared\City;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->using(fn (City $record) => CityResource::deleteAction($record))
                ->successRedirectUrl(CityResource::getUrl()),
        ];
    }
}
