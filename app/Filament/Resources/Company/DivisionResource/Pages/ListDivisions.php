<?php

namespace App\Filament\Resources\Company\DivisionResource\Pages;

use App\Filament\Resources\Company\DivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDivisions extends ListRecords
{
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
