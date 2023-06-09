<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\EmployeeSubdivisionResource\Pages;
use App\Models\Rating\EmployeeSubdivision;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class EmployeeSubdivisionResource extends Resource
{
    protected static ?string $model = EmployeeSubdivision::class;

    protected static ?string $navigationGroup = 'Cотрудники';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationIcon = 'heroicon-o-view-list';

    protected static ?string $label = 'Подразделение';

    protected static ?string $pluralLabel = 'Подразделения';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Отдел web-разработки')
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
            'index' => Pages\ListEmployeeSubdivisions::route('/'),
            'create' => Pages\CreateEmployeeSubdivision::route('/create'),
            'edit' => Pages\EditEmployeeSubdivision::route('/{record}/edit'),
        ];
    }
}
