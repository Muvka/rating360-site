<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\CompanyResource\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\Company\CompanyResource\Pages;
use App\Models\Company\Company;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

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
}