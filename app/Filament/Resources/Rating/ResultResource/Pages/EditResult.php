<?php

namespace App\Filament\Resources\Rating\ResultResource\Pages;

use App\Filament\Resources\Rating\ResultResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResult extends EditRecord
{
    protected static string $resource = ResultResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
