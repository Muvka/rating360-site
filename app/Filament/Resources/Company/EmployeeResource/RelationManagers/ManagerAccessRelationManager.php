<?php

namespace App\Filament\Resources\Company\EmployeeResource\RelationManagers;

use App\Filament\RelationManagers\Company\BaseEmployeesRelationManager;
use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Employee;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ManagerAccessRelationManager extends BaseEmployeesRelationManager
{
    protected static string $relationship = 'managerAccess';

    public function table(Table $table): Table
    {
        return $table
            ->columns(EmployeeResource::getRelationTableSchema())
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->recordSelect(function (Select $select) {
                    return $select->searchable()
                        ->getSearchResultsUsing(
                            fn (string $search) => Employee::where('last_name', 'like', "%{$search}%")
                                ->limit(20)
                                ->get()
                                ->pluck('full_name', 'id'))
                        ->getOptionLabelUsing(fn ($value): ?string => Employee::find($value)
                            ->full_name);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Employee $record): string => route('filament.admin.resources.company.employees.edit', $record->id)),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->company_level_id && $ownerRecord->company_level_id !== 5;
    }
}
