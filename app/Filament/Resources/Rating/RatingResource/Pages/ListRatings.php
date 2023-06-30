<?php

namespace App\Filament\Resources\Rating\RatingResource\Pages;

use App\Filament\Resources\Rating\RatingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRatings extends ListRecords
{
    protected static string $resource = RatingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
