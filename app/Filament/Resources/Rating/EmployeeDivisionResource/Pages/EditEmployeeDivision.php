<?php

namespace App\Filament\Resources\Rating\EmployeeDivisionResource\Pages;

use App\Filament\Resources\Rating\EmployeeDivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeDivision extends EditRecord
{
    protected static string $resource = EmployeeDivisionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
