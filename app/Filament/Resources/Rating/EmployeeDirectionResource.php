<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\EmployeeDirectionResource\Pages;
use App\Models\Rating\EmployeeDirection;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class EmployeeDirectionResource extends Resource
{
    protected static ?string $model = EmployeeDirection::class;

    protected static ?string $navigationGroup = 'Cотрудники';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationIcon = 'heroicon-o-trending-up';

    protected static ?string $label = 'Направление';

    protected static ?string $pluralLabel = 'Направления';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Девелоперы')
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
            'index' => Pages\ListEmployeeDirections::route('/'),
            'create' => Pages\CreateEmployeeDirection::route('/create'),
            'edit' => Pages\EditEmployeeDirection::route('/{record}/edit'),
        ];
    }
}
