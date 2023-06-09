<?php

namespace App\Filament\Resources\Shared\CompanyResource\Pages;

use App\Filament\Resources\Shared\CompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
