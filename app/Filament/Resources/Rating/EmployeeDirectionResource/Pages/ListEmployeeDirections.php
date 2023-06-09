<?php

namespace App\Filament\Resources\Rating\EmployeeDirectionResource\Pages;

use App\Filament\Resources\Rating\EmployeeDirectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeDirections extends ListRecords
{
    protected static string $resource = EmployeeDirectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
