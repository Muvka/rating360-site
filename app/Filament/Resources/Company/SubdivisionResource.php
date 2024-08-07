<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\SubdivisionResource\Pages;
use App\Filament\Resources\Company\SubdivisionResource\RelationManagers\EmployeesRelationManager;
use App\Models\Company\Subdivision;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class SubdivisionResource extends Resource
{
    protected static ?string $model = Subdivision::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

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
                    ->action(fn (Subdivision $record) => static::deleteAction($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn (Collection $records) => $records->each(
                        fn (Subdivision $record) => static::deleteAction($record))
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
            'index' => Pages\ListSubdivisions::route('/'),
            'create' => Pages\CreateSubdivision::route('/create'),
            'edit' => Pages\EditSubdivision::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(Subdivision $record): bool
    {
        if ($record->employees->count()) {
            Notification::make()
                ->title('Внимание')
                ->body('Подразделение "'.$record->name.'" нельзя удалить, пока у него есть сотрудники!')
                ->danger()
                ->send();

            return false;
        } else {
            $record->delete();

            return true;
        }
    }
}
