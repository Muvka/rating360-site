<?php

namespace App\Filament\Resources\Rating\RatingResource\Pages;

use App\Filament\Resources\Rating\RatingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRating extends EditRecord
{
    protected static string $resource = RatingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
