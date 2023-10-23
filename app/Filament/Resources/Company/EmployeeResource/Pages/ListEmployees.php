<?php

namespace App\Filament\Resources\Company\EmployeeResource\Pages;

use App\Filament\Resources\Company\EmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }
}
