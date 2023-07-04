<?php

namespace App\Filament\Resources\Company\SubdivisionResource\Pages;

use App\Filament\Resources\Company\SubdivisionResource;
use App\Models\Company\Subdivision;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubdivision extends EditRecord
{
    protected static string $resource = SubdivisionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->using(fn(Subdivision $record) => SubdivisionResource::deleteAction($record))
                ->successRedirectUrl(SubdivisionResource::getUrl()),
        ];
    }
}
