<?php

namespace App\Filament\Resources\Rating\EmployeePositionResource\Pages;

use App\Filament\Resources\Rating\EmployeePositionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePosition extends EditRecord
{
    protected static string $resource = EmployeePositionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
