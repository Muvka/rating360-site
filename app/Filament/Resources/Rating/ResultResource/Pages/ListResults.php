<?php

namespace App\Filament\Resources\Rating\ResultResource\Pages;

use App\Filament\Resources\Rating\ResultResource;
use App\Filament\Widgets\Rating\ResultChart;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResults extends ListRecords
{
    protected static string $resource = ResultResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
//            ResultChart::class,
        ];
    }
}
