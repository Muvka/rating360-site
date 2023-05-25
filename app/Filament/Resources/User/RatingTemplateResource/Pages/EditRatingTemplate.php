<?php

namespace App\Filament\Resources\User\RatingTemplateResource\Pages;

use App\Filament\Resources\User\RatingTemplateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRatingTemplate extends EditRecord
{
    protected static string $resource = RatingTemplateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
