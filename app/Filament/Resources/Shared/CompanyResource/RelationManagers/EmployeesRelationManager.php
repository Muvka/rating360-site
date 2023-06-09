<?php

namespace App\Filament\Resources\Shared\CompanyResource\RelationManagers;

use App\Filament\Resources\Rating\EmployeeResource;
use App\Models\Rating\Employee;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $label = 'Cотрудник';

    protected static ?string $pluralLabel = 'Сотрудники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(EmployeeResource::getRelationTableSchema(false))
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Employee $record): string => route('filament.resources.rating/employees.edit', $record->id)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
