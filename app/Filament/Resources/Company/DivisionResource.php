<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\DivisionResource\Pages;
use App\Filament\Resources\Company\DivisionResource\RelationManagers\EmployeesRelationManager;
use App\Models\Company\Division;
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

class DivisionResource extends Resource
{
    protected static ?string $model = Division::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';

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
                    ->action(fn (Division $record) => static::deleteAction($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn (Collection $records) => $records->each(
                        fn (Division $record) => static::deleteAction($record))
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
            'index' => Pages\ListDivisions::route('/'),
            'create' => Pages\CreateDivision::route('/create'),
            'edit' => Pages\EditDivision::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(Division $record): bool
    {
        if ($record->employees->count()) {
            Notification::make()
                ->title('Внимание')
                ->body('Отдел "'.$record->name.'" нельзя удалить, пока в нём есть сотрудники!')
                ->danger()
                ->send();

            return false;
        } else {
            $record->delete();

            return true;
        }
    }
}
