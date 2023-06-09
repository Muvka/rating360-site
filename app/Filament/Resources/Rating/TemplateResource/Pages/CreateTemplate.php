<?php

namespace App\Filament\Resources\Rating\TemplateResource\Pages;

use App\Filament\Resources\Rating\TemplateResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateTemplate extends CreateRecord
{
    use HasWizard;

    protected static string $resource = TemplateResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Общие')
                ->schema([
                    Card::make()
                        ->schema(TemplateResource::getGeneralFormSchema())
                ]),
            Step::make('Компетенции')
                ->schema([
                    Card::make()
                        ->schema([
                            Repeater::make('competences')
                                ->label('Компетенции')
                                ->disableLabel(true)
                                ->relationship()
                                ->minItems(1)
                                ->defaultItems(1)
                                ->disableItemMovement(false)
                                ->createItemButtonLabel('Добавить компетенцию')
                                ->orderable()
                                ->required()
                                ->schema(TemplateResource::getCompetenceFormSchema()),
                        ])
                ]),
        ];
    }
}
