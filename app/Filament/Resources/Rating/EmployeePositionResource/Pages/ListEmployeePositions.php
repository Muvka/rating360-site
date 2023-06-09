<?php

namespace App\Filament\Resources\Rating\EmployeePositionResource\Pages;

use App\Filament\Resources\Rating\EmployeePositionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePositions extends ListRecords
{
    protected static string $resource = EmployeePositionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
