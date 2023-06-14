<?php

namespace App\Filament\Resources\Rating\ValueResource\Pages;

use App\Filament\Resources\Rating\ValueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListValues extends ListRecords
{
    protected static string $resource = ValueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
