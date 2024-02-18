<?php

namespace App\Filament\Resources\Company\SubdivisionResource\Pages;

use App\Filament\Resources\Company\SubdivisionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubdivisions extends ListRecords
{
    protected static string $resource = SubdivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
