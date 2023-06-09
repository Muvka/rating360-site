<?php

namespace App\Filament\Resources\Rating\TemplateResource\Pages;

use App\Filament\Resources\Rating\TemplateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemplate extends EditRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
