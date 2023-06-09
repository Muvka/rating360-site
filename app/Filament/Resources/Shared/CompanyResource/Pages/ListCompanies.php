<?php

namespace App\Filament\Resources\Shared\CompanyResource\Pages;

use App\Filament\Resources\Shared\CompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
