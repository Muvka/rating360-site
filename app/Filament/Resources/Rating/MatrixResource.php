<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\MatrixResource\Pages;
use App\Filament\Resources\Rating\MatrixResource\RelationManagers\MatrixTemplatesRelationManager;
use App\Models\Rating\Matrix;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MatrixResource extends Resource
{
    protected static ?string $model = Matrix::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $label = 'Матрица';

    protected static ?string $pluralLabel = 'Матрицы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Общая матрица')
                            ->maxLength(128)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Номер')->rowIndex(),
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
            MatrixTemplatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatrices::route('/'),
            'create' => Pages\CreateMatrix::route('/create'),
            'edit' => Pages\EditMatrix::route('/{record}/edit'),
        ];
    }
}
