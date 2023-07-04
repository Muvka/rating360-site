<?php

namespace App\Filament\Resources\Company\EmployeeResource\RelationManagers;

use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Employee;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class FunctionalSubordinatesRelationManager extends RelationManager
{
    protected static string $relationship = 'functionalSubordinates';

    protected static ?string $label = 'Функциональный';

    protected static ?string $pluralLabel = 'Функциональные';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(EmployeeResource::getRelationTableSchema())
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Employee $record): string => route('filament.resources.company/employees.edit', $record->id)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord): bool
    {
        return $ownerRecord->company_employee_level_id && $ownerRecord->company_employee_level_id !== 5;
    }
}
