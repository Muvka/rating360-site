<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\EmployeePositionResource\Pages;
use App\Filament\Resources\Company\EmployeePositionResource\RelationManagers\EmployeesRelationManager;
use App\Models\Company\Position;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class EmployeePositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationGroup = 'Компании';

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
                    ->action(fn (Position $record) => static::deleteAction($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn (Collection $records) => $records->each(
                        fn (Position $record) => static::deleteAction($record))
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
            'index' => Pages\ListEmployeePositions::route('/'),
            'create' => Pages\CreateEmployeePosition::route('/create'),
            'edit' => Pages\EditEmployeePosition::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(Position $record): bool
    {
        if ($record->employees->count()) {
            Notification::make()
                ->title('Внимание')
                ->body('Должность "'.$record->name.'" нельзя удалить, пока у неё есть сотрудники!')
                ->danger()
                ->send();

            return false;
        } else {
            $record->delete();

            return true;
        }
    }
}
