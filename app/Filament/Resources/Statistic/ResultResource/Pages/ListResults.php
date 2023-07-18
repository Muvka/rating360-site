<?php

namespace App\Filament\Resources\Statistic\ResultResource\Pages;

use App\Filament\Resources\Statistic\ResultResource;
use App\Filament\Widgets\Statistic\ResultChart;
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

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
