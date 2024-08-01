<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\CompetenceResource\Pages;
use App\Models\Rating\Competence;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CompetenceResource extends Resource
{
    protected static ?string $model = Competence::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Компетенция';

    protected static ?string $pluralLabel = 'Компетенции';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Навыки постановки целей')
                            ->maxLength(255)
                            ->required(),
                        Textarea::make('description')
                            ->label('Описание')
                            ->placeholder('Описание компетенции')
                            ->rows(4)
                            ->maxLength(65535),
                        Toggle::make('manager_only')
                            ->label('Для руководителей'),
                        TableRepeater::make('markers')
                            ->relationship()
                            ->label('Маркеры')
                            ->headers([
                                Header::make('text')
                                    ->label('Текст'),
                                Header::make('rating_value_id')
                                    ->label('Ценность')
                                    ->width('20%'),
                                Header::make('answer_type')
                                    ->label('Ответ')
                                    ->width('20%'),
                            ])
                            ->addActionLabel('Добавить маркер')
                            ->emptyLabel('Нет маркеров')
                            ->required()
                            ->reorderable()
                            ->schema([
                                Textarea::make('text')
                                    ->label('Текст')
                                    ->hiddenLabel()
                                    ->placeholder('ведет за собой, показывает личный положительный пример')
                                    ->rows(3)
                                    ->maxLength(65535)
                                    ->required(),
                                Select::make('rating_value_id')
                                    ->relationship('value', 'name')
                                    ->label('Ценность')
                                    ->hiddenLabel()
                                    ->placeholder('Выберите'),
                                Select::make('answer_type')
                                    ->label('Ответы')
                                    ->hiddenLabel()
                                    ->selectablePlaceholder(false)
                                    ->default('default')
                                    ->options([
                                        'default' => 'Из списка',
                                        'text' => 'Текст',
                                    ])
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Номер')->rowIndex(),
                TextColumn::make('name')
                    ->label('Название')
                    ->sortable(),
                ToggleColumn::make('manager_only')
                    ->label('Для руководителей')
                    ->sortable(),
            ])
            ->reorderable()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetences::route('/'),
            'create' => Pages\CreateCompetence::route('/create'),
            'edit' => Pages\EditCompetence::route('/{record}/edit'),
        ];
    }
}
