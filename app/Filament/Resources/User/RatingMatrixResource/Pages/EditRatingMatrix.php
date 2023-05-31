<?php

namespace App\Filament\Resources\User\RatingMatrixResource\Pages;

use App\Filament\Resources\User\RatingMatrixResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRatingMatrix extends EditRecord
{
    protected static string $resource = RatingMatrixResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
