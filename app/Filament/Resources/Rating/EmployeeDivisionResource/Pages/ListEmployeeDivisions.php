<?php

namespace App\Filament\Resources\Rating\EmployeeDivisionResource\Pages;

use App\Filament\Resources\Rating\EmployeeDivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeDivisions extends ListRecords
{
    protected static string $resource = EmployeeDivisionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
