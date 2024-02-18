<?php

namespace App\Filament\Resources\Rating\ValueResource\Pages;

use App\Filament\Resources\Rating\ValueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValue extends EditRecord
{
    protected static string $resource = ValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
