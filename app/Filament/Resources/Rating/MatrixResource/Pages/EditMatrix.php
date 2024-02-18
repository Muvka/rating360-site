<?php

namespace App\Filament\Resources\Rating\MatrixResource\Pages;

use App\Filament\Resources\Rating\MatrixResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatrix extends EditRecord
{
    protected static string $resource = MatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
