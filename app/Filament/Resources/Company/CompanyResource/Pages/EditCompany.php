<?php

namespace App\Filament\Resources\Company\CompanyResource\Pages;

use App\Filament\Resources\Company\CompanyResource;
use App\Models\Company\Company;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->using(fn(Company $record) => CompanyResource::deleteAction($record))
                ->successRedirectUrl(CompanyResource::getUrl()),
        ];
    }
}
