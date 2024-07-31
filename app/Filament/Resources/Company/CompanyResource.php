<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\CompanyResource\Pages;
use App\Filament\Resources\Company\CompanyResource\RelationManagers\EmployeesRelationManager;
use App\Models\Company\Company;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $label = 'Компания';

    protected static ?string $pluralLabel = 'Компании';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('ООО "Пример"')
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
                    ->action(fn (Company $record) => static::deleteAction($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn (Collection $records) => $records->each(
                        fn (Company $record) => static::deleteAction($record))
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(Company $record): bool
    {
        if ($record->employees->count()) {
            Notification::make()
                ->title('Внимание')
                ->body('Компанию "'.$record->name.'" нельзя удалить, пока у неё есть сотрудники!')
                ->danger()
                ->send();

            return false;
        } else {
            $record->delete();

            return true;
        }
    }
}
