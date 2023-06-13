<?php

namespace App\Filament\Resources\Company\CompanyResource\Pages;

use App\Filament\Resources\Company\CompanyResource;
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
