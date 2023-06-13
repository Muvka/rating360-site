<?php

namespace App\Filament\Resources\Company\DirectionResource\Pages;

use App\Filament\Resources\Company\DirectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDirections extends ListRecords
{
    protected static string $resource = DirectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
