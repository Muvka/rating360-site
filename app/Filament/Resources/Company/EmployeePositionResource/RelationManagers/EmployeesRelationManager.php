<?php

namespace App\Filament\Resources\Company\EmployeePositionResource\RelationManagers;

use App\Filament\RelationManagers\Company\BaseEmployeesRelationManager;
use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Employee;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeesRelationManager extends BaseEmployeesRelationManager
{
    public function table(Table $table): Table
    {
        return $table
            ->columns(EmployeeResource::getRelationTableSchema())
            ->filters([
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Employee $record): string => route('filament.admin.resources.company.employees.edit', $record->id)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
