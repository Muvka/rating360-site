<?php

namespace App\Filament\Resources\Company\EmployeeResource\RelationManagers;

use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class ManagerAccessRelationManager extends RelationManager
{
    protected static string $relationship = 'managerAccess';

    protected static ?string $title = 'Сотрудники';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

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
