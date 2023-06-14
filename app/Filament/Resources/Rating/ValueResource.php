<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\ValueResource\Pages;
use App\Models\Rating\Value;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ValueResource extends Resource
{
    protected static ?string $model = Value::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $label = 'Ценность';

    protected static ?string $pluralLabel = 'Ценности';

    protected static ?string $navigationLabel = 'Ценность';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(4)
                    ->schema([
                        Placeholder::make('id')
                            ->label('Идентификатор')
                            ->content(fn(?Value $record): ?string => $record?->id),
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Уважение и доверие')
                            ->maxLength(128)
                            ->required()
                            ->columnSpan(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Идентификатор'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValues::route('/'),
            'create' => Pages\CreateValue::route('/create'),
            'edit' => Pages\EditValue::route('/{record}/edit'),
        ];
    }
}
