<?php

namespace App\Filament\Resources\User\RatingTemplateResource\RelationManagers;

use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CompetencesRelationManager extends RelationManager
{
    protected static string $relationship = 'competences';

    protected static ?string $label = 'Компетенция';

    protected static ?string $pluralLabel = 'Компетенции';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->maxLength(255)
                    ->required(),
                TableRepeater::make('markers')
                    ->relationship()
                    ->minItems(1)
                    ->defaultItems(1)
                    ->disableItemMovement(false)
                    ->label('Поведенческие маркеры')
                    ->headers(['Маркер', 'Ценность', 'Ответ'])
                    ->emptyLabel('Нет маркеров')
                    ->createItemButtonLabel('Добавить маркер')
                    ->orderable()
                    ->columnWidths([
                        'value' => '20%',
                        'answer_type' => '20%',
                    ])
                    ->required()
                    ->schema([
                        Textarea::make('text')
                            ->label('Текст')
                            ->disableLabel()
                            ->placeholder('ведет за собой, показывает личный положительный пример')
                            ->rows(2)
                            ->maxLength(65535)
                            ->required(),
                        Select::make('value')
                            ->label('Ценность')
                            ->disableLabel()
                            ->options([
                                'respect' => 'Уважение и доверие',
                                'responsibility' => 'Ответственность',
                                'development' => 'Развитие',
                                'team_leadership' => 'Командное лидерство',
                            ]),
                        Select::make('answer_type')
                            ->label('Ответы')
                            ->disableLabel()
                            ->default('default')
                            ->options([
                                'default' => 'Из списка',
                                'text' => 'Текст',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название'),
            ])
            ->defaultSort('sort')
            ->reorderable()
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('6xl')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort'] = 999999;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('6xl'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
