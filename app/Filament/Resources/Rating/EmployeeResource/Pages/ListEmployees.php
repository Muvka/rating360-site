<?php

namespace App\Filament\Resources\Rating\EmployeeResource\Pages;

use App\Filament\Resources\Rating\EmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }
}
