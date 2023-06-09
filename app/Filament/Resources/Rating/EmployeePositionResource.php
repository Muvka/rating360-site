<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\EmployeePositionResource\Pages;
use App\Filament\Resources\Rating\EmployeePositionResource\RelationManagers;
use App\Models\Rating\EmployeePosition;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class EmployeePositionResource extends Resource
{
    protected static ?string $model = EmployeePosition::class;

    protected static ?string $navigationGroup = 'Cотрудники';

    protected static ?int $navigationSort = 60;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $label = 'Должность';

    protected static ?string $pluralLabel = 'Должности';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Инженер-проектировщик систем электроснабжения')
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
            'index' => Pages\ListEmployeePositions::route('/'),
            'create' => Pages\CreateEmployeePosition::route('/create'),
            'edit' => Pages\EditEmployeePosition::route('/{record}/edit'),
        ];
    }
}
