<?php

namespace App\Filament\Resources\Company\DivisionResource\Pages;

use App\Filament\Resources\Company\DivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDivision extends EditRecord
{
    protected static string $resource = DivisionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
