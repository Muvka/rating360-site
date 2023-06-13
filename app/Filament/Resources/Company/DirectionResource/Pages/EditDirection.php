<?php

namespace App\Filament\Resources\Company\DirectionResource\Pages;

use App\Filament\Resources\Company\DirectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDirection extends EditRecord
{
    protected static string $resource = DirectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
