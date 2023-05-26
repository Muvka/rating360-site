<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\RatingTemplateResource\Pages;
use App\Filament\Resources\User\RatingTemplateResource\RelationManagers\CompetencesRelationManager;
use App\Models\UserRatingTemplate;
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

class RatingTemplateResource extends Resource
{
    protected static ?string $model = UserRatingTemplate::class;

    protected static ?int $navigationSort = 20;

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
            CompetencesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatingTemplates::route('/'),
            'create' => Pages\CreateRatingTemplate::route('/create'),
            'edit' => Pages\EditRatingTemplate::route('/{record}/edit'),
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
        ];
    }

    public static function getCompetenceFormSchema(): array
    {
        return [
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
        ];
    }
}
