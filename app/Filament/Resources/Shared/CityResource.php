<?php

namespace App\Filament\Resources\Shared;

use App\Filament\Resources\Shared\CityResource\Pages;
use App\Filament\Resources\Shared\CityResource\RelationManagers\EmployeesRelationManager;
use App\Models\Shared\City;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationGroup = 'Общее';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $label = 'Город';

    protected static ?string $pluralLabel = 'Города';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Киров')
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
                Tables\Actions\DeleteAction::make()
                    ->action(fn (City $record) => static::deleteAction($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn (Collection $records) => $records->each(
                        fn (City $record) => static::deleteAction($record))
                    ),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EmployeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(City $record): bool
    {
        if ($record->employees->count()) {
            Notification::make()
                ->title('Внимание')
                ->body('Город "'.$record->name.'" нельзя удалить, пока есть сотрудники, привязанные к городу!')
                ->danger()
                ->send();

            return false;
        } else {
            $record->delete();

            return true;
        }
    }
}
