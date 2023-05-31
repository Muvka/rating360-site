<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\RatingMatrixResource\Pages;
use App\Filament\Resources\User\RatingMatrixResource\RelationManagers\TemplatesRelationManager;
use App\Models\UserRatingMatrix;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class RatingMatrixResource extends Resource
{
    protected static ?string $model = UserRatingMatrix::class;

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationIcon = 'heroicon-o-view-grid';

    protected static ?string $label = 'Матрица';

    protected static ?string $pluralLabel = 'Матрицы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema(static::getGeneralFormSchema())
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
            TemplatesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatingMatrices::route('/'),
            'create' => Pages\CreateRatingMatrix::route('/create'),
            'edit' => Pages\EditRatingMatrix::route('/{record}/edit'),
        ];
    }

    public static function getGeneralFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Название')
                ->placeholder('Общая матрица')
                ->maxLength(128)
                ->required(),
        ];
    }

    public static function getTemplateFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label('Сотрудник')
                ->relationship('employee', 'name')
                ->searchable()
                ->required(),
            TextInput::make('division')
                ->label('Отдел')
                ->placeholder('Проектирование объектов из панелей')
                ->maxLength(128)
                ->required(),
            TextInput::make('subdivision')
                ->label('Подразделение')
                ->placeholder('Проектный отдел')
                ->maxLength(128)
                ->required(),
            TextInput::make('position')
                ->label('Должность')
                ->placeholder('Инженер-проектировщик систем электроснабжения')
                ->maxLength(128)
                ->required(),
            TextInput::make('level')
                ->label('Уровень сотрудника')
                ->placeholder('Специалист')
                ->maxLength(128)
                ->required(),
            TextInput::make('company')
                ->label('Компания')
                ->placeholder('Масштаб ООО')
                ->maxLength(128)
                ->required(),
            TextInput::make('city')
                ->label('Город')
                ->placeholder('Киров')
                ->maxLength(128)
                ->required(),
            Fieldset::make()
                ->label('Направления')
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Repeater::make('directions')
                        ->relationship()
                        ->label('Направления')
                        ->disableLabel()
                        ->defaultItems(1)
                        ->disableItemMovement()
                        ->createItemButtonLabel('Добавить направление')
                        ->required()
                        ->schema([
                            TextInput::make('name')
                                ->label('Название')
                                ->disableLabel()
                                ->placeholder('Девелоперы')
                                ->maxLength(128)
                                ->required(),
                        ]),
                ]),

        ];
    }
}
