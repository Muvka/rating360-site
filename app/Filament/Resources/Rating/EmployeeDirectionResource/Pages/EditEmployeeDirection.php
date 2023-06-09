<?php

namespace App\Filament\Resources\Rating\EmployeeDirectionResource\Pages;

use App\Filament\Resources\Rating\EmployeeDirectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeDirection extends EditRecord
{
    protected static string $resource = EmployeeDirectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
