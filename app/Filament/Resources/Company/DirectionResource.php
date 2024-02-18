<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\DirectionResource\Pages;
use App\Filament\Resources\Company\DirectionResource\RelationManagers\EmployeesRelationManager;
use App\Models\Company\Direction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Collection;
use stdClass;

class DirectionResource extends Resource
{
    protected static ?string $model = Direction::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

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
                    ->action(fn (Direction $record) => static::deleteAction($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn (Collection $records) => $records->each(
                        fn (Direction $record) => static::deleteAction($record))
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
            'index' => Pages\ListDirections::route('/'),
            'create' => Pages\CreateDirection::route('/create'),
            'edit' => Pages\EditDirection::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(Direction $record): bool
    {
        if ($record->employees->count()) {
            Notification::make()
                ->title('Внимание')
                ->body('Направление "'.$record->name.'" нельзя удалить, пока у него есть сотрудники!')
                ->danger()
                ->send();

            return false;
        } else {
            $record->delete();

            return true;
        }
    }
}
