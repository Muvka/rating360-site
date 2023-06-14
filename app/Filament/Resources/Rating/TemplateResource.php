<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\TemplateResource\RelationManagers\CompetencesRelationManager;
use App\Filament\Resources\Rating\TemplateResource\Pages;
use App\Models\Rating\Template;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationIcon = 'heroicon-o-template';

    protected static ?string $label = 'Шаблон оценки';

    protected static ?string $pluralLabel = 'Шаблоны оценок';

    protected static ?string $navigationLabel = 'Шаблоны оценок';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema(static::getGeneralFormSchema()),
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
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Название'),
            ])
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
//            CompetencesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }

    public static function getGeneralFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Название')
                ->placeholder('Общий шаблон')
                ->maxLength(128)
                ->required(),
            TableRepeater::make('markers')
                ->relationship()
                ->label('Маркеры')
                ->headers(['Компетенция', 'Текст', 'Ценность', 'Ответы'])
                ->createItemButtonLabel('Добавить маркер')
                ->emptyLabel('Нет маркеров')
                ->orderable()
                ->columnWidths([
                    'rating_competence_id' => '25%',
                    'text' => '45%',
                    'rating_value_id' => '15%',
                    'answer_type' => '15%',
                ])
                ->schema([
                    Select::make('rating_competence_id')
                        ->relationship('competence', 'name')
                        ->label('Компетенция')
                        ->disableLabel()
                        ->required(),
                    Textarea::make('text')
                        ->label('Текст')
                        ->disableLabel()
                        ->placeholder('ведет за собой, показывает личный положительный пример')
                        ->rows(2)
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
                ]),
        ];
    }

    public static function getCompetenceFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Название')
                ->maxLength(255)
                ->required(),
            TableRepeater::make('templateMarkers')
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
                    Select::make('rating_value_id')
                        ->relationship('value', 'name')
                        ->label('Ценность')
                        ->disableLabel()
                        ->placeholder('Выберите'),
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
        ];
    }
}
