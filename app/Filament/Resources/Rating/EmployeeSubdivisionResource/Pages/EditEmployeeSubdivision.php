<?php

namespace App\Filament\Resources\Rating\EmployeeSubdivisionResource\Pages;

use App\Filament\Resources\Rating\EmployeeSubdivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeSubdivision extends EditRecord
{
    protected static string $resource = EmployeeSubdivisionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
