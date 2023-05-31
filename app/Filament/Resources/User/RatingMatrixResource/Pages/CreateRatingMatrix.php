<?php

namespace App\Filament\Resources\User\RatingMatrixResource\Pages;

use App\Filament\Resources\User\RatingMatrixResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateRatingMatrix extends CreateRecord
{
    use HasWizard;

    protected static string $resource = RatingMatrixResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Общие')
                ->schema([
                    Card::make()
                        ->schema(RatingMatrixResource::getGeneralFormSchema())
                ]),
            Step::make('Шаблоны')
                ->schema([
                    Card::make()
                        ->schema([
                            Repeater::make('templates')
                                ->label('Шаблоны')
                                ->disableLabel()
                                ->relationship()
                                ->minItems(1)
                                ->defaultItems(1)
                                ->disableItemMovement(false)
                                ->createItemButtonLabel('Добавить шаблон')
                                ->orderable()
                                ->columns()
                                ->required()
                                ->schema(RatingMatrixResource::getTemplateFormSchema()),
                        ]),
                ]),
        ];
    }
}
