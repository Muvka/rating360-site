<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\LevelResource\Pages;
use App\Models\Company\Level;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $label = 'Уровень сотрудника';

    protected static ?string $pluralLabel = 'Уровни сотрудника';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(4)
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Специалист')
                            ->maxLength(64)
                            ->required()
                            ->columnSpanFull(),
                        Toggle::make('is_manager')
                            ->label('Является руководителем'),
                        Toggle::make('requires_manager')
                            ->label('Руководитель обязателен'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Номер')->rowIndex(),
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_manager')
                    ->label('Является руководителем'),
                ToggleColumn::make('requires_manager')
                    ->label('Руководитель обязателен'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
