<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\CompetenceResource\Pages;
use App\Filament\Resources\Rating\CompetenceResource\RelationManagers;
use App\Models\Rating\Competence;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

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
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Навыки постановки целей')
                            ->maxLength(255)
                            ->required(),
                        Toggle::make('manager_only')
                            ->label('Для руководителей'),
                        TableRepeater::make('markers')
                            ->relationship()
                            ->label('Маркеры')
                            ->headers(['Текст', 'Ценность', 'Ответ'])
                            ->createItemButtonLabel('Добавить маркер')
                            ->emptyLabel('Нет маркеров')
                            ->columnWidths([
                                'rating_value_id' => '20%',
                                'answer_type' => '20%',
                            ])
                            ->required()
                            ->orderable()
                            ->schema([
                                Textarea::make('text')
                                    ->label('Текст')
                                    ->disableLabel()
                                    ->placeholder('ведет за собой, показывает личный положительный пример')
                                    ->rows(3)
                                    ->maxLength(65535)
                                    ->required(),
                                Select::make('rating_value_id')
                                    ->relationship('value', 'name')
                                    ->label('Ценность')
                                    ->disableLabel()
                                    ->placeholder('Выберите'),
                                Select::make('answer_type')
                                    ->label('Ответы')
                                    ->disableLabel()
                                    ->disablePlaceholderSelection()
                                    ->default('default')
                                    ->options([
                                        'default' => 'Из списка',
                                        'text' => 'Текст',
                                    ])
                                    ->required(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('№')
                    ->getStateUsing(
                        static function (stdClass $rowLoop, HasTable $livewire): string {
                            return (string) (
                                $rowLoop->iteration +
                                ($livewire->tableRecordsPerPage * (
                                        $livewire->page - 1
                                    ))
                            );
                        }
                    ),
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
