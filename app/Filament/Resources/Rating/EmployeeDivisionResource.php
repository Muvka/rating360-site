<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\EmployeeDivisionResource\Pages;
use App\Models\Rating\EmployeeDivision;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class EmployeeDivisionResource extends Resource
{
    protected static ?string $model = EmployeeDivision::class;

    protected static ?string $navigationGroup = 'Cотрудники';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationIcon = 'heroicon-o-view-list';

    protected static ?string $label = 'Отдел';

    protected static ?string $pluralLabel = 'Отделы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Проектирование объектов из панелей')
                            ->maxLength(255)
                            ->required(),
                    ]),
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
                    ->searchable()
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeDivisions::route('/'),
            'create' => Pages\CreateEmployeeDivision::route('/create'),
            'edit' => Pages\EditEmployeeDivision::route('/{record}/edit'),
        ];
    }
}
