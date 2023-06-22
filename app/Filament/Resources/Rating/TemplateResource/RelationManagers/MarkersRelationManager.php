<?php

namespace App\Filament\Resources\Rating\TemplateResource\RelationManagers;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MarkersRelationManager extends RelationManager
{
    protected static string $relationship = 'markers';

    protected static ?string $label = 'Маркер';

    protected static ?string $pluralLabel = 'Маркеры';

    protected static ?string $title = 'Маркер';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns()
                    ->schema([
                        Select::make('rating_competence_id')
                            ->relationship('competence', 'name')
                            ->label('Компетенция')
                            ->columnSpanFull()
                            ->required(),
                        Select::make('rating_value_id')
                            ->relationship('value', 'name')
                            ->label('Ценность')
                            ->placeholder('Выберите'),
                        Select::make('answer_type')
                            ->label('Ответы')
                            ->disablePlaceholderSelection()
                            ->default('default')
                            ->options([
                                'default' => 'Из списка',
                                'text' => 'Текст',
                            ])
                            ->required(),
                        Textarea::make('text')
                            ->label('Текст')
                            ->placeholder('ведет за собой, показывает личный положительный пример')
                            ->rows(5)
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('competence.name')
                    ->label('Компетенция')
                    ->sortable(),
                TextColumn::make('text')
                    ->label('Текст')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('value.name')
                    ->label('Ценность')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->reorderable()
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
