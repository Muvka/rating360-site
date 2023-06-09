<?php

namespace App\Filament\Resources\Rating\EmployeeSubdivisionResource\Pages;

use App\Filament\Resources\Rating\EmployeeSubdivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeSubdivisions extends ListRecords
{
    protected static string $resource = EmployeeSubdivisionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
