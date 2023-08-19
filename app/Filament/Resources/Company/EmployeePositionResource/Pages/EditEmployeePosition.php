<?php

namespace App\Filament\Resources\Company\EmployeePositionResource\Pages;

use App\Filament\Resources\Company\EmployeePositionResource;
use App\Models\Company\Position;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePosition extends EditRecord
{
    protected static string $resource = EmployeePositionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->using(fn (Position $record) => EmployeePositionResource::deleteAction($record))
                ->successRedirectUrl(EmployeePositionResource::getUrl()),
        ];
    }
}
