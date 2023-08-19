<?php

namespace App\Filament\Resources\Company\EmployeeResource\RelationManagers;

use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Employee;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class ManagerAccessRelationManager extends RelationManager
{
    protected static string $relationship = 'managerAccess';

    protected static ?string $label = 'Сотрудник';

    protected static ?string $pluralLabel = 'Сотрудники';

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
                    ->url(fn (Employee $record): string => route('filament.resources.company/employees.edit', $record->id)),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord): bool
    {
        return $ownerRecord->company_level_id && $ownerRecord->company_level_id !== 5;
    }
}
